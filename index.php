
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


</head>

<style>


    body{
            display:flex;
            height:100%;
            justify-content:center;
            align-items:center;
              
        }
        .select_area{
            width:450px;
            height:300px;
            background-color:rgba(0,0,0,.5);
            border-radius:10px;
            border:10px solid #fff;
          
        }

        .todayWeather{
            font-family: 'Noto Sans TC',sans-serif;
            padding-top: 20px;
        }
        h4{
            padding-bottom: 20px;

        }
        #row1{
            padding-top:60px;
        }
        #col1{
            width:450px;
            height:300px;
            border-radius:10px;
            border:10px solid #FFED97;
            background-color:#FFFFCE;

        }
        #col2{
                height: 450px;
                height:300px;
                border-radius: 10px;
                border: 10px solid rgb(142, 217, 240);
                margin:30px;
   
   
        }
        p{
            color: #7c795d; 
            font-family: 'Trocchi', serif; 
            font-size: 25px;
            font-weight: normal; 
            line-height: 48px; 
            margin: 0; 
            text-align:center;
            padding-top:20px;
        }
        #number{
            width:150px;
            height:30px;
            border:2px solid #aaa;
            border-radius:5px;
           
        }
        .form{
            
            margin-top:10px;
            padding-left: 90px;
        }
        .form .btn{
            
            border-radius:5px;
            border:2px #D0D0D0;
            background-color:#ADADAD;
            margin-top:50px;
            margin-left: 40px;
          
            
        }

        .form-contain{
            margin:0 auto;
        }
      
        
   


</style>
<body>

<header id="header" class="text-center tm-text-gray">

</header>

<div class="container-fluid">
  <h1></h1>
  
  
  <div class="container">
    <!-- Control the column width, and how they should appear on different devices -->
    <div class="row" id="row1">
    <div class="col-sm-4" ></div>

    <div class="col-sm-4" class="col1" id="col1">
      <p>請選擇地區</p>
      <hr>
      <div class="form-contain">
        <form method="post" class="form" action="index2.php"> 
        <select name="number" id="number">
        <option value="" selected disabled hidden><?= $defaultname;?></option>
        <?php  foreach($current as  $value){ ?>
        <option value=<?=$value->locationName;?>><?=$value->locationName;?></option>
        <?php }?>
        </select>
        <br>
        <button type="submit" class="btn">確認</button>
        </form>
      </div>
      </div>
     
      
    </div>
    <div class="col-sm-4" ></div>
    <br>
    
    

  </div>
</div>


</body>
</html>









 


