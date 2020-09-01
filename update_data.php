<?php
// 9/1

//36小時天氣
$api_url = 'https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001?Authorization=CWB-E7799364-6F85-44B7-BF07-F8C9C143E0B8&format=JSON';
//一周天氣
$api3_url="https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-091?Authorization=CWB-E7799364-6F85-44B7-BF07-F8C9C143E0B8";
//1小時雨量
$api4_url="https://opendata.cwb.gov.tw/api/v1/rest/datastore/O-A0002-001?Authorization=CWB-E7799364-6F85-44B7-BF07-F8C9C143E0B8&format=JSON&elementName=RAIN&parameterName=CITY";
//24小時雨量
$api5_url="https://opendata.cwb.gov.tw/api/v1/rest/datastore/O-A0002-001?Authorization=CWB-E7799364-6F85-44B7-BF07-F8C9C143E0B8&format=JSON&elementName=HOUR_24&parameterName=CITY";

function clink($url){
    $data = file_get_contents($url);
    file_put_contents($cache_file, $data);
    $data = json_decode($data);
    return $data;
}
//**連結資料庫  明後天天氣**/
$api1_data=clink($api_url);
$current = $api1_data->records->location;
//var_dump ($api1_data); 

$cate=array();
$title = array('天氣狀況', '降雨率', '溫度1', '舒適度','溫度2');
$today=array();

foreach($current as $item=> $value){

    $city= $value->locationName;
    $stime= $value->weatherElement[0]->time[0]->startTime;
    $a=$value->weatherElement;
    foreach($a as $item2=> $value2){
        $today[$item2]= $title[$item2].$value2->time[0]->parameter->parameterName." ";
    }
    $output=implode("",$today);
   // echo $output;

   $s_time="SELECT city,time1,descript FROM today WHERE city='$city' and time1='$stime'";
   require("conn.php");
   $result1=mysqli_query($link,$s_time);
   $row1=mysqli_fetch_assoc($result1);

   if(($row1['city']=="") and ($row1['time1']=="")){  //判斷有沒有存在
        $sql="insert into today(city,time1,descript) values('$city','$stime','$output')";
        mysqli_query($link,$sql);
   }
}

//**連結資料庫  一小時累積雨量**/
$api4_data=clink($api4_url);
$current4=$api4_data->records->location;

foreach($current4 as $value){
    
    $location=$value->locationName;
    $stime=$value->time->obsTime;
    $area= $value->parameter[0]->parameterValue;
    $rain= $value->weatherElement[0]->elementValue;

    $s_time="SELECT time1,locationname FROM onehrian WHERE time1=$stime and locationname=$location";
   
    require("conn.php");
    $result1=mysqli_query($link,$s_time);
    $row1=mysqli_fetch_assoc($result1);

    if(($row1['time1']=="") and ($row1['locationname']=="")){  //判斷有沒有存在
        $sql="insert into onehrian(city,time1,locationname,hour1) values('$area','$stime','$location','$rain')";
        
        mysqli_query($link,$sql);
    }

}



/**連結資料庫  一週天氣**/
$api3_data=clink($api3_url);
$current3=$api3_data->records->locations[0]->location;

foreach($current3 as $value){
    $name=($value->locationName);
    $a=$value->weatherElement[10]->time;
    
    foreach($a as $value2){
        
        $twoday_t=$value2->startTime; //日期時間
        $twoday_descript=$value2->elementValue[0]->value; //敘述
        

        $s_time="SELECT city,time1 FROM week WHERE time1='$twoday_t'";
        require("conn.php");
        $result1=mysqli_query($link,$s_time);
        $row1=mysqli_fetch_assoc($result1);

        if(($row1['time1']=="") and ($row1['city']=="")){  //判斷有沒有存在
            $sql="insert into week(city,time1,descript) values('$name','$twoday_t','$twoday_descript')";
        //    require("conn.php");
            mysqli_query($link,$sql);
        }
        
    }
}

//**連結資料庫 24小時累積雨量**//
$api5_data=clink($api5_url);
$current5=$api5_data->records->location;

foreach($current5 as $value){
    //echo $value->locationName;
    $area= $value->parameter[0]->parameterValue;
  
    $location =$value->locationName;
    $rain =  $value->weatherElement[0]->elementValue;
    $stime= $value->time->obsTime;
    
    
    $s_time="SELECT time1,locationname FROM hrain WHERE time=$stime and locationname=$location";
    require("conn.php");
    $result1=mysqli_query($link,$s_time);
    $row1=mysqli_fetch_assoc($result1);

    if(($row1['time1']=="") and ($row1['locationname']=="")){  //判斷有沒有存在
        $sql="insert into hrain(city,time1,locationname,hour24) values('$area','$stime','$location','$rain')";
        //require("conn.php");
        mysqli_query($link,$sql);
    }
}





?>