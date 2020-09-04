<?php
//36小時天氣
$api_url = 'https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001?Authorization=CWB-E7799364-6F85-44B7-BF07-F8C9C143E0B8&format=JSON';

function clink($url){
    $data = file_get_contents($url);
    file_put_contents($cache_file, $data);
    $data = json_decode($data);
    return $data;
}
$defaultname="請選擇";
$api1_data=clink($api_url);
$current = $api1_data->records->location;

?>
<!------------------------------------------------->

<!DOCTYPE html>
<html lang="en">
<head>
<title>Weather</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<link rel=stylesheet type="text/css" href="style.css">
</head>

<body>
<div class="container-fluid">
<h1>個人氣象站</h1>
  <div class="container">
    <div class="row" id="row1">
    <div class="col-sm-4" ></div>
    <div class="col-sm-4" class="col1" id="col1">
      <p id="ca">請選擇地區</p><hr>
        <div class="form-contain">
            <form method="post" class="form" action="today.php"> 
                <select name="number" id="number">
                    <option value="" selected disabled hidden><?= $defaultname;?></option>
                    <?php  foreach($current as  $value){ ?>
                        <option value=<?=$value->locationName;?>><?=$value->locationName;?></option>
                    <?php }?>
                </select><br>
                <button type="submit" class="btn">確認</button>
            </form>
        </div>
    </div> 
    </div>
    <div class="col-sm-4" ></div><br>
  </div>
</div>
</body>
</html>









 


