<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
// header('Access-Control-Allow-Headers: token, Content-Type, X-Requested-With');
include "mydbCon.php";

$arr = [];
$default = "default";
$query6 = "SELECT * FROM `ranges` WHERE `machineToken`='$default'";
($result1 = mysqli_query($dbCon, $query6)) or
    die("database error:" . mysqli_error($dbCon));
$range = mysqli_fetch_assoc($result1);

$humidity["level_0"] = $range["humidity_green"];
$humidity["level_1"] = $range["humidity_yellow"];
$humidity["level_2"] = $range["humidity_orange"];
$humidity["level_3"] = $range["humidity_darkOrange"];
$humidity["level_4"] = $range["humidity_red"];

$AQI["level_0"] = $range["AQI_green"];
$AQI["level_1"] = $range["AQI_yellow"];
$AQI["level_2"] = $range["AQI_orange"];
$AQI["level_3"] = $range["AQI_darkOrange"];
$AQI["level_4"] = $range["AQI_red"];

$humidityHidden["level_0"] = $range["humidityHidden_green"];
$humidityHidden["level_1"] = $range["humidityHidden_yellow"];
$humidityHidden["level_2"] = $range["humidityHidden_orange"];
$humidityHidden["level_3"] = $range["humidityHidden_darkOrange"];
$humidityHidden["level_4"] = $range["humidityHidden_red"];

$TVOC["level_0"] = $range["VOC_green"];
$TVOC["level_1"] = $range["VOC_yellow"];
$TVOC["level_2"] = $range["VOC_orange"];
$TVOC["level_3"] = $range["VOC_darkOrange"];
$TVOC["level_4"] = $range["VOC_red"];

$CO2["level_0"] = $range["CO2_green"];
$CO2["level_1"] = $range["CO2_yellow"];
$CO2["level_2"] = $range["CO2_orange"];
$CO2["level_3"] = $range["CO2_darkOrange"];
$CO2["level_4"] = $range["CO2_red"];

$PM2_5["level_0"] = $range["PM2_green"];
$PM2_5["level_1"] = $range["PM2_yellow"];
$PM2_5["level_2"] = $range["PM2_orange"];
$PM2_5["level_3"] = $range["PM2_darkOrange"];
$PM2_5["level_4"] = $range["PM2_red"];

$PM_10["level_0"] = $range["PM10_green"];
$PM_10["level_1"] = $range["PM10_yellow"];
$PM_10["level_2"] = $range["PM10_orange"];
$PM_10["level_3"] = $range["PM10_darkOrange"];
$PM_10["level_4"] = $range["PM10_red"];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $machineCustomer = $_POST["cid"];
    $query4 = "SELECT * FROM `customers` WHERE `Id`=$machineCustomer";
    ($result4 = mysqli_query($dbCon, $query4)) or
        die("database error:" . mysqli_error($dbCon));
    $customer = mysqli_fetch_assoc($result4);
    $machineCustomerName = $customer["FullName"];

    $machineName = $_POST["name"];
    $machineLocation = $_POST["location"];
    $machineInspection = $_POST["date"];
    $machineToken = $_POST["api"];

    $query9 = "SELECT * FROM `machines` WHERE `apiToken`='$machineToken'";
    ($result9 = mysqli_query($dbCon, $query9)) or
        die("database error:" . mysqli_error($dbCon));
    if (mysqli_num_rows($result9) > 0) {
        $machineToken = substr(
            str_shuffle(
                "0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
            ),
            rand(0, 51),
            10
        );
    }
    $query1 = "INSERT INTO `machines` ( `customerId`, `name`, `location`, `apiToken`, `inspectionDate`) VALUES ( '$machineCustomer', '$machineName', '$machineLocation', '$machineToken', '$machineInspection')";
    ($result1 = mysqli_query($dbCon, $query1)) or
        die("database error:" . mysqli_error($dbCon));

    $query10 = "SELECT * FROM `machines` WHERE `apiToken`='$machineToken'";
    ($result10 = mysqli_query($dbCon, $query10)) or
        die("database error:" . mysqli_error($dbCon));
    $machineData = mysqli_fetch_assoc($result10);
    $machineId = $machineData["Id"];

    $query2 = "INSERT INTO `Control_Final` ( `machine_id`, `Machine Name`, `Machine API Token`, `Customer Name`, `R1_Low_Fan`, `R2_High_Fan`, `R3_UVC`, `R4_Bipol`, `R5_Return_Damper`, `R6_Supply_Damper`, `R7_Air_Conditioning`, `R8_Heat`, `R9_Spare`, `R10_Spare`, `Manual_Mode`, `Override_1`, `Override_2`, `Override_RST`, `Shift_Start_Time`, `Shift_End_Time`, `Sys_Override_Time`) 
                                    VALUES ( '$machineId', '$machineName', '$machineToken', '$machineCustomerName', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";
    ($result2 = mysqli_query($dbCon, $query2)) or
        die("database error:" . mysqli_error($dbCon));

    $query3 = "INSERT INTO `Display_Final` (`ID`, `machine_id`, `Machine Name`, `Machine API Token`, `Customer Name`, `R1_Status`, `R2_Status`, `R3_Status`, `R4_Status`, `R5_Status`, `R6_Status`, `R7_Status_Spare`, `R8_Status_Spare`, `R9_Status_Spare`, `R10_Status_Spare`, `AQI`, `In_Temperature`, `In_Humidity`, `In_CO2`, `In_VOC`, `In_PM_2_5`, `In_PM_10`, `In_CO`, `Out_Temperature`, `Out_Humidity`, `Out_O3`, `Out_SO2`, `Out_CO`, `Out_CO2`, `Out_NO2`, `Out_PM_2_5`, `Out_PM_10`, `Out_Radon_Spare`, `R1_Man_Status`, `R2_Man_Status`, `R3_Man_Status`, `R4_Man_Status`, `R5_Man_Status`, `R6_Man_Status`, `R7_Man_Status`, `R8_Man_Status`, `R9_Man_Status`, `R10_Man_Status`, `Manual_Mode_Blink_Indicator`, `System_Violated`, `Replace_Filter`) 
                                    VALUES (NULL, '$machineId', '$machineName', '$machineToken', '$machineCustomerName', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";

    ($result3 = mysqli_query($dbCon, $query3)) or
        die("database error:" . mysqli_error($dbCon));

    $query4 = "INSERT INTO `inspections` (`id`, `machine_id`, `machineToken`) VALUES (NULL, '$machineId', '$machineToken')";
    ($result4 = mysqli_query($dbCon, $query4)) or
        die("database error:" . mysqli_error($dbCon));

    $query7 = "INSERT INTO `advertisement_img` (`id`, `client_id`, `client_name`, `machine_id`, `machine_name`, `machine_api`, `ad_pic`) VALUES (NULL, '$machineCustomer', '$machineCustomerName', '$machineId', '$machineName', '$machineToken', NULL)";
    ($result7 = mysqli_query($dbCon, $query7)) or
        die("database error:" . mysqli_error($dbCon));
}
print json_encode($arr);
?>
