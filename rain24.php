<?php

//24小時雨量
$api5_url="https://opendata.cwb.gov.tw/api/v1/rest/datastore/O-A0002-001?Authorization=CWB-E7799364-6F85-44B7-BF07-F8C9C143E0B8&format=JSON&elementName=HOUR_24&parameterName=CITY";
require("conn.php");
function clink($url){
    $data = file_get_contents($url);
    file_put_contents($cache_file, $data);
    $data = json_decode($data);
    return $data;
}
//**連結資料庫 24小時累積雨量**//
$api5_data=clink($api5_url);
$current5=$api5_data->records->location;
 
if($_GET['city']){
    $city=$_GET['city'];
    //echo $city;   
}    

?>

<!DOCTYPE html>
<html>
<title>rain</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel=stylesheet type="text/css" href="style2.css">
<body>

<div class="w3-container">
    <div class="w3-bar w3-border w3-light-grey" >
        <a href="rain.php?city=<?= $city?>" class="w3-bar-item w3-button" style="background-color:#A3D1D1; text-decoration:none;">一小時前累積雨量</a>
        <a href="rain24.php?city=<?= $city?>" class="w3-bar-item w3-button" style="background-color:#FFD1A4; text-decoration:none;">24小時前累積雨量</a>
        <a href="today.php?number=<?= $city?>" class="w3-bar-item w3-button" style="background-color:#FFC0CB; text-decoration:none;">天氣</a>
        <a href="index.php" class="w3-bar-item w3-button" style="background-color:#ACD6FF; text-decoration:none;">回首頁</a>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-4" id="box1" style="background-color:lavender;">
                <?php echo '<img src="/RD1_Assignment/image/'.$city.'.jpeg " width="350" height="250"/ >';?>
                <h2>[<?=$city?>]</h2><br>
            </div>
            <div class="col-sm-8" id="box1" style="background-color:lavender;">
                <div class="today">
                    <p>------[過去24小時 累積雨量數據]------</p>
                    <?php
                        foreach($current5 as $value){
                            //echo $value->locationName;
                            $area= $value->parameter[0]->parameterValue;
                            if($area=="$city"){
                                echo $area;
                                echo  $value->locationName;
                                echo  $value->weatherElement[0]->elementValue;
                                echo "&nbsp";
                            }
                        }

                        foreach($current5 as $value){
                            //echo $value->locationName;
                            $area= $value->parameter[0]->parameterValue;
                            $location =$value->locationName;
                            $rain =  $value->weatherElement[0]->elementValue;
                            $stime= $value->time->obsTime;
                            
                            $s_time="SELECT time1,locationname FROM hrain WHERE time=$stime and locationname=$location";
                            //require("conn.php");
                            $result1=mysqli_query($link,$s_time);
                            $row1=mysqli_fetch_assoc($result1);
                        
                            if(($row1['time1']=="") and ($row1['locationname']=="")){  //判斷有沒有存在
                                $sql="insert into hrain(city,time1,locationname,hour24) values('$area','$stime','$location','$rain')";
                                //require("conn.php");
                                mysqli_query($link,$sql);
                            }
                        }

                    ?>
                </div>
            </div> 
        </div>
    </div>
</div>
</body>
</html>