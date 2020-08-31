<?php

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

/*---------------------目前天氣---------------------------*/

$api1_data=clink($api_url);

$current = $api1_data->records->location;
//var_dump ($api1_data); 

$cate=array();
$defaultname="請選擇";
$title = array('天氣狀況', '降雨率', '溫度_下限', '舒適度','溫度_上限');
$city="";


if(isset($_POST['number'])){
    $id=$_POST['number'];
    //echo $id."<br>";

    
    foreach($current as $item=> $value){
        if($value->locationName=="$id"){
            echo $value->locationName;
            $id=$item;
            echo "<br>";
        }
    }

    for($i=0;$i<5;$i++){
        $cate[$i]=$current[$id]->weatherElement[$i]->time[0]->parameter->parameterName;
        echo $title[$i]."=>".$cate[$i]."<br>";

    }
    $defaultname=$_POST['number'];
    $city=$defaultname;
    
    $day1= date("Y-m-d",strtotime("1 day"));//明天
    $day2=date("Y-m-d",strtotime("2 day"));//後天

    /*----------------------取得選取的縣市-----------------------------*/

    $api3_data=clink($api3_url);
    $current3=$api3_data->records->locations[0]->location;
    //print_r($current3[$id]->weatherElement[10]->time[0]->startTime);

    $id3="";
    foreach($current3 as $item=> $value){
    // echo $value->locationName;
        if($value->locationName=="$defaultname"){
            echo $value->locationName;
            $id3=$item;
            echo "<br>";

        }
    }     // echo $value->locationName;


    /*----------------------明後天天氣-----------------------------*/
    


    $w3=$current3[$id3]->weatherElement[10]->time; //綜合描述
   
    // $Arr2=explode('。',$a); 
    // var_dump($Arr2);
    // echo $Arr2[2]."<br>";



    $i=0;
    $feture_two_day=array();
    $feture_two_day_descript=array();
    foreach($w3 as $item=>$value){

        $get_date=$value->startTime; //2020-08-29 09:00:00
        $get_time=substr($get_date, 11, -6); //2020-08-29
        $get_date=substr($get_date, 0, 10);  //09:00:00
        
        // echo $get_date;
        // echo "<br>";

        if(($get_date==$day1 or $get_date==$day2) and ($get_time=="06" or $get_time=="18")){ //過濾日期



            // $feture_two_day[$i]=$value->startTime; //將讀到的時間存進陣列 2020-08-30 06:00:00
            // $feture_two_day_descript[$i]=$value->elementValue[0]->value;//將讀到的內容存進陣列 晴時多雲。降雨機率 10%。溫度攝氏27至35度。舒適至悶熱。東北風 風速3級(每秒5公尺)。相對濕度68%。
            // $twoday_t=$value->startTime;
            // $twoday_descript=$value->elementValue[0]->value;
            //echo  $twoday_t;
            //echo "  描述: ".$twoday_descript;
            //truncate table twoday;
            $i++;
        }
        
    }

    // //var_dump($feture_two_day_descript);

    // /*--------------------- 一周天氣------------------------------*/
    // echo "***************************************";
    // echo "<br>";

    // foreach($w3 as $item=>$value){
        
    //     echo $value->startTime;   
    //         echo "  描述: ".$value->elementValue[0]->value;
    //         echo "<br>";
        
    // }
    // echo "<br>";


    /*--------------------- 過去1小時 累積雨量數據------------------------------*/

    // $api4_data=clink($api4_url);
    // $current4=$api4_data->records->location;



    // foreach($current4 as $value){
    // // echo $value->locationName;
    // $area= $value->parameter[0]->parameterValue;
    //     if($area=="$defaultname"){
        
    //         echo  $value->locationName;
    //         echo  $value->weatherElement[0]->elementValue;
    //     }
    // }


    // echo "a-----"."<br>";
    // print_r($current4[$id]->parameter[0]->parameterValue);
    // echo "<br>"."b-----"."<br>";
    // print_r($current4[$id]->weatherElement[0]->elementValue);


    // /*--------------------- 過去24小時 累積雨量數據------------------------------*/
    // echo "<br>"."<br>";
    // $api5_data=clink($api5_url);
    // $current5=$api5_data->records->location;

    // $current5[$id]->weatherElement[0]->elementValue;

    // foreach($current5 as $value){
    //     //echo $value->locationName;
    // $area= $value->parameter[0]->parameterValue;

    //     if($area=="$defaultname"){
    //         echo $area;
    //         echo  $value->locationName;
    //         echo  $value->weatherElement[0]->elementValue;
    //     }
    // }
    
}



?>






<!------------------------------------------------->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Document</title>
</head>
<body>

<form method="post" action="index.php"> 
    <select name="number" id="number">
    <option value="" selected disabled hidden><?= $defaultname;?></option>
    <?php  foreach($current as  $value){ ?>
    <option value=<?=$value->locationName;?>><?=$value->locationName;?></option>
    <?php }?>
    </select>
    <button type="submit">ok</button>

</form>
<p><?= $city?></p>
<img src=<?= '/RD1_Assignment/p2/image/'.$city.'.jpeg'?> alt="Girl in a jacket" width="100" height="100">



</body>
</html>




 


