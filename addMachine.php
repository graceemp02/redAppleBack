<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
// header('Access-Control-Allow-Headers: token, Content-Type, X-Requested-With');
include "mydbCon.php";

$arr = [];

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

    $query2 = "INSERT INTO `Control_Final` ( `machine_id`, `Machine Name`, `Machine API Token`, `Customer Name`) 
                                    VALUES ( '$machineId', '$machineName', '$machineToken', '$machineCustomerName')";
    ($result2 = mysqli_query($dbCon, $query2)) or
        die("database error:" . mysqli_error($dbCon));

    $query3 = "INSERT INTO `Display_Final` (`machine_id`, `Machine Name`, `Machine API Token`, `Customer Name`) 
                                    VALUES ( '$machineId', '$machineName', '$machineToken', '$machineCustomerName')";

    ($result3 = mysqli_query($dbCon, $query3)) or
        die("database error:" . mysqli_error($dbCon));

    $query4 = "INSERT INTO `inspections` (`machine_id`, `machineToken`) VALUES ( '$machineId', '$machineToken')";
    ($result4 = mysqli_query($dbCon, $query4)) or
        die("database error:" . mysqli_error($dbCon));

    $query7 = "INSERT INTO `advertisement_img` (`client_id`, `client_name`, `machine_id`, `machine_name`, `machine_api`) VALUES ( '$machineCustomer', '$machineCustomerName', '$machineId', '$machineName', '$machineToken')";
    ($result7 = mysqli_query($dbCon, $query7)) or
        die("database error:" . mysqli_error($dbCon));

    $query8 = "INSERT INTO `ranges` (`machineId`, `machineName`, `machineToken`) 
                                    VALUES ('$machineId', '$machineName', '$machineToken')";
                                    $result8 = mysqli_query($dbCon, $query8) or die("database error:" . mysqli_error($dbCon));
        if($result8){
            $arr['res'] = 'true';
        }
}
print json_encode($arr);
?>
