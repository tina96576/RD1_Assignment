<?php 


    /*---------------------明後天日期------------------------------*/

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


    // /*----------------------明後天天氣-----------------------------*/

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

            $feture_two_day[$i]=$value->startTime; //將讀到的時間存進陣列 2020-08-30 06:00:00
            $feture_two_day_descript[$i]=$value->elementValue[0]->value;//將讀到的內容存進陣列 晴時多雲。降雨機率 10%。溫度攝氏27至35度。舒適至悶熱。東北風 風速3級(每秒5公尺)。相對濕度68%。
            echo $value->startTime;   
            echo "  描述: ".$value->elementValue[0]->value;
            echo "<br>";
            $i++;
        }
        
    }

    // //var_dump($feture_two_day_descript);

    // /*--------------------- 一周天氣------------------------------*/
    echo "***************************************";
    echo "<br>";

    foreach($w3 as $item=>$value){
        
        echo $value->startTime;   
            echo "  描述: ".$value->elementValue[0]->value;
            echo "<br>";
        
    }
    echo "<br>";


    /*--------------------- 過去1小時 累積雨量數據------------------------------*/

    $api4_data=clink($api4_url);
    $current4=$api4_data->records->location;



    foreach($current4 as $value){
    // echo $value->locationName;
    $area= $value->parameter[0]->parameterValue;
        if($area=="$defaultname"){
        
            echo  $value->locationName;
            echo  $value->weatherElement[0]->elementValue;
        }
    }


    // echo "a-----"."<br>";
    // print_r($current4[$id]->parameter[0]->parameterValue);
    // echo "<br>"."b-----"."<br>";
    // print_r($current4[$id]->weatherElement[0]->elementValue);


    // /*--------------------- 過去24小時 累積雨量數據------------------------------*/
    echo "<br>"."<br>";
    $api5_data=clink($api5_url);
    $current5=$api5_data->records->location;

    $current5[$id]->weatherElement[0]->elementValue;

    foreach($current5 as $value){
        //echo $value->locationName;
    $area= $value->parameter[0]->parameterValue;

        if($area=="$defaultname"){
            echo $area;
            echo  $value->locationName;
            echo  $value->weatherElement[0]->elementValue;
        }
    }




?>