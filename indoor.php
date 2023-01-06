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

        $query2 = "SELECT * FROM `ranges` WHERE `machineToken`='$api'";
        ($result2 = mysqli_query($dbCon, $query2)) or
            die("database error:" . mysqli_error($dbCon));
        $range = mysqli_fetch_assoc($result2);

        $temp["max"] = 100;
        $temp["barMax"] = $temp["max"] + 0.2 * $temp["max"];
        $temp["min"] = 0;
        $Intemp["val"] = $control["In_Temperature"];
        $Intemp["perc"] =
            (100 * ($Intemp["val"] - $temp["min"])) / $temp["barMax"];
        if ($Intemp["perc"] > 100) {
            $Intemp["perc"] = 100;
        }

        $hum["max"] = $range["humidity_red"];
        $hum["barMax"] = $hum["max"] + 0.2 * $hum["max"];
        $hum["min"] = $range["humidity_green"];
        $Inhum["val"] = $control["In_Humidity"];
        $Inhum["perc"] = (100 * ($Inhum["val"] - $hum["min"])) / $hum["barMax"];

        if ($Inhum["perc"] > 100) {
            $Inhum["perc"] = 100;
        }

        $VOC["max"] = $range["VOC_red"];
        $VOC["barMax"] = $VOC["max"] + 0.2 * $VOC["max"];
        $VOC["min"] = $range["VOC_green"];
        $InVOC["val"] = $control["In_VOC"];
        $InVOC["perc"] = (100 * ($InVOC["val"] - $VOC["min"])) / $VOC["barMax"];
        if ($InVOC["perc"] > 100) {
            $InVOC["perc"] = 100;
        }

        $CO2["max"] = $range["CO2_red"];
        $CO2["barMax"] = $CO2["max"] + 0.2 * $CO2["max"];
        $CO2["min"] = $range["CO2_green"];
        $InCO2["val"] = $control["In_CO2"];
        $InCO2["perc"] = (100 * ($InCO2["val"] - $CO2["min"])) / $CO2["barMax"];
        if ($InCO2["perc"] > 100) {
            $InCO2["perc"] = 100;
        }

        $CO["max"] = 2500;
        $CO["barMax"] = $CO["max"] + 0.2 * $CO["max"];
        $CO["min"] = 0;
        $InCO["val"] = $control["In_CO"];
        $InCO["perc"] = (100 * ($InCO["val"] - $CO["min"])) / $CO["barMax"];
        if ($InCO["perc"] > 100) {
            $InCO["perc"] = 100;
        }

        $PM25["max"] = $range["PM2_red"];
        $PM25["barMax"] = $PM25["max"] + 0.2 * $PM25["max"];
        $PM25["min"] = $range["PM2_green"];
        $InPM25["val"] = $control["In_PM_2_5"];
        $InPM25["perc"] =
            (100 * ($InPM25["val"] - $PM25["min"])) / $PM25["barMax"];
        if ($InPM25["perc"] > 100) {
            $InPM25["perc"] = 100;
        }

        $PM10["min"] = $range["PM10_green"];
        $PM10["max"] = $range["PM10_red"];
        $PM10["barMax"] = $PM10["max"] + 0.2 * $PM10["max"];
        $InPM10["val"] = $control["In_PM_10"];
        $InPM10["perc"] =
            (100 * ($InPM10["val"] - $PM10["min"])) / $PM10["barMax"];
        if ($InPM10["perc"] > 100) {
            $InPM10["perc"] = 100;
        }

        // Temperature and Humidity at top
        $arr["temp"] = $Intemp["val"];
        $arr["hum"] = $Inhum["val"];

        //  VOC Bar ht in % and val
        $arr["vocHt"] = $InVOC["perc"];
        $arr["vocVl"] = $InVOC["val"];

        //  CO2 Bar ht in % and val
        $arr["co2Ht"] = $InCO2["perc"];
        $arr["co2Vl"] = $InCO2["val"];

        //  CO Bar ht in % and val
        $arr["coHt"] = $InCO["perc"];
        $arr["coVl"] = $InCO["val"];

        //  pm25 Bar ht in % and val
        $arr["pm25Ht"] = $InPM25["perc"];
        $arr["pm25Vl"] = $InPM25["val"];

        //  pm10 Bar ht in % and val
        $arr["pm10Ht"] = $InPM10["perc"];
        $arr["pm10Vl"] = $InPM10["val"];
    }
}

print json_encode($arr);
?>

