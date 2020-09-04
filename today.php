<?php
//36小時天氣
$api_url = 'https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001?Authorization=CWB-E7799364-6F85-44B7-BF07-F8C9C143E0B8&format=JSON';
//一周天氣
$api3_url="https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-091?Authorization=CWB-E7799364-6F85-44B7-BF07-F8C9C143E0B8";
function clink($url){
    $data = file_get_contents($url);
    file_put_contents($cache_file, $data);
    $data = json_decode($data);
    return $data;
}

require("conn.php");

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
        $result1=mysqli_query($link,$s_time);
        $row1=mysqli_fetch_assoc($result1);

        if(($row1['time1']=="") and ($row1['city']=="")){  //判斷有沒有存在
            $sql="insert into week(city,time1,descript) values('$name','$twoday_t','$twoday_descript')";
        //    require("conn.php");
            mysqli_query($link,$sql);
        }  
    }
}

$api1_data=clink($api_url);
$current = $api1_data->records->location;

if(isset($_POST['number'])){
    $id=$_POST['number'];//縣市
    //echo $id."<br>";
    $defaultname=$_POST['number'];
    $city=$defaultname;

    foreach($current as $item=> $value){
        if($value->locationName=="$id"){
            $city=$value->locationName;
            $id=$item;  
        }  
    }  
    
} 
echo '<img src="/RD1_Assignment/image/'.$city.'.jpeg " width="500" height="300"/>';
?>

<!DOCTYPE html>
<html>
<title>today</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<body>
<style>
    .w3-button{
        border-radius:10px;
        margin:10px;
    }

</style>

<div class="w3-container">
<h2><?=$city?></h2>
<p></p>
<div class="w3-bar w3-border w3-light-grey" >

<a href="rain.php?city=<?= $city?>" class="w3-bar-item w3-button" style="background-color:#A3D1D1;">累積雨量</a>
<a href="index.php" class="w3-bar-item w3-button" style="background-color:#ACD6FF;">回首頁</a>
</div>


<div class="container-fluid">
  <div class="row">
    <div class="col-sm-4" id="box1" style="background-color:lavender;">

    <p>------[今日天天氣]------</p>
    <?php
        $cate=array();
        $title = array('天氣狀況', '降雨率', '溫度1', '舒適度','溫度2');
        for($i=0;$i<5;$i++){ 
            $cate[$i]=$current[$id]->weatherElement[$i]->time[0]->parameter->parameterName;
            echo $title[$i].": ".$cate[$i]."<br>";
        }
    ?>
    </div>
    <div class="col-sm-4"  id="box2" style="background-color:lavenderblush;">
    <p>------[明後天天氣]------</p>
    <?php
        $day1= date("Y-m-d",strtotime("1 day"));//明天
        $day2=date("Y-m-d",strtotime("2 day"));//後天
        /*----------------------取得選取的縣市-----------------------------*/
        $id3="";
        foreach($current3 as $item=> $value){
        // echo $value->locationName;
            if($value->locationName=="$defaultname"){
                $id3=$item;
            }
        }     
        /*----------------------明後天天氣-----------------------------*/
       
        $w3=$current3[$id3]->weatherElement[10]->time; //綜合描述

        foreach($w3 as $item=>$value){
            $get_date=$value->startTime; //2020-08-29 09:00:00
            $get_time=substr($get_date, 11, -6); //2020-08-29
            $get_date=substr($get_date, 0, 10);  //09:00:00
            
            if(($get_date==$day1 or $get_date==$day2) and ($get_time=="06" or $get_time=="18")){ //過濾日期
                echo $value->startTime;   
                echo "  描述: ".$value->elementValue[0]->value;
                echo "<br>";
            }   
        }
    
    ?>

    </div>

    <div class="col-sm-4"  id="box2" style="background-color:#ADD8E6;">
    <p>------[未來一週天氣]------</p>
    <?php
       
        $w3=$current3[$id3]->weatherElement[10]->time; //綜合描述
        foreach($w3 as $item=>$value){
            $get_date=$value->startTime; //2020-08-29 09:00:00
            $get_time=substr($get_date, 11, -6); //2020-08-29
            $get_date=substr($get_date, 0, 10);  //09:00:00

            echo $value->startTime;   
            echo "  描述: ".$value->elementValue[0]->value;
            echo "<br>";
            echo "<br>";   
        }
    ?>

    </div>
    
  </div>
</div>


</div>
</body>
</html>
