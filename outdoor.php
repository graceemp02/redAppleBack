<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
// header('Access-Control-Allow-Headers: token, Content-Type, X-Requested-With');
include "mydbCon.php";
$arr = [];
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["api"])) {
        $api = $_GET["api"];
        $query = "SELECT * FROM `Display_Final` WHERE `Machine API Token`='$api'";
        ($result = mysqli_query($dbCon, $query)) or
            die("database error:" . mysqli_error($dbCon));
        $control = mysqli_fetch_assoc($result);

        $temp["max"] = 100;
        $temp["min"] = 0;
        $Outtemp["val"] = $control["Out_Temperature"];
        $Outtemp["perc"] =
            (100 * ($Outtemp["val"] - $temp["min"])) / $temp["max"];

        $hum["max"] = 100;
        $hum["min"] = 0;
        $Outhum["val"] = $control["Out_Humidity"];
        $Outhum["perc"] = (100 * ($Outhum["val"] - $hum["min"])) / $hum["max"];

        $O3["max"] = 10000;
        $O3["min"] = 0;
        $OutO3["val"] = $control["Out_O3"];
        $OutO3["perc"] = (100 * ($OutO3["val"] - $O3["min"])) / $O3["max"];

        $SO2["max"] = 10000;
        $SO2["min"] = 0;
        $OutSO2["val"] = $control["Out_SO2"];
        $OutSO2["perc"] = (100 * ($OutSO2["val"] - $SO2["min"])) / $SO2["max"];

        $CO2["max"] = 2500;
        $CO2["min"] = 0;
        $OutCO2["val"] = $control["Out_CO2"];
        $OutCO2["perc"] = (100 * ($OutCO2["val"] - $CO2["min"])) / $CO2["max"];

        $CO["max"] = 2500;
        $CO["min"] = 0;
        $OutCO["val"] = $control["Out_CO"];
        $OutCO["perc"] = (100 * ($OutCO["val"] - $CO["min"])) / $CO["max"];

        $PM25["max"] = 100;
        $PM25["min"] = 0;
        $OutPM25["val"] = $control["Out_PM_2_5"];
        $OutPM25["perc"] =
            (100 * ($OutPM25["val"] - $PM25["min"])) / $PM25["max"];

        $PM10["max"] = 100;
        $PM10["min"] = 0;
        $OutPM10["val"] = $control["Out_PM_10"];
        $OutPM10["perc"] =
            (100 * ($OutPM10["val"] - $PM10["min"])) / $PM10["max"];

        $NO2["max"] = 100;
        $NO2["min"] = 0;
        $OutNO2["val"] = $control["Out_NO2"];
        $OutNO2["perc"] = (100 * ($OutNO2["val"] - $NO2["min"])) / $NO2["max"];

        $Radon["max"] = 100;
        $Radon["min"] = 0;
        $OutRadon["val"] = $control["Out_Radon_Spare"];
        $OutRadon["perc"] =
            (100 * ($OutRadon["val"] - $Radon["min"])) / $Radon["max"];
        

            // Top Temp and Hum Values for outdoor
            $arr['temp'] = $Outtemp['val'];
            $arr['hum'] = $Outhum['val'];

            // O3 bar height and value
            $arr['o3Ht']=$OutO3['perc'];
            $arr['o3Vl']=$OutO3['val'];

            // SO2 bar height and value
            $arr['so2Ht']=$OutSO2['perc'];
            $arr['so2Vl']=$OutSO2['val'];

            // NO2 bar height and value
            $arr['no2Ht']=$OutNO2['perc'];
            $arr['no2Vl']=$OutNO2['val'];

            // CO2 bar height and value
            $arr['co2Ht']=$OutCO2['perc'];
            $arr['co2Vl']=$OutCO2['val'];

            // CO bar height and value
            $arr['coHt']=$OutCO['perc'];
            $arr['coVl']=$OutCO['val'];

            // PM25 bar height and value
            $arr['pm25Ht']=$OutPM25['perc'];
            $arr['pm25Vl']=$OutPM25['val'];

            // PM10 bar height and value
            $arr['pm10Ht']=$OutPM10['perc'];
            $arr['pm10Vl']=$OutPM10['val'];

    }
}

print json_encode($arr);
?>
