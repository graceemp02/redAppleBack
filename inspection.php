<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
// header('Access-Control-Allow-Headers: token, Content-Type, X-Requested-With');
include "mydbCon.php";
$arr;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["date"]) && isset($_POST["api"])) {
        $api = $_POST["api"];
        $date = $_POST["date"];
        $query = "UPDATE `machines` SET `inspectionDate` = '$date' WHERE `apiToken` = '$api'";
        ($result = mysqli_query($dbCon, $query)) or
            die("database error:" . mysqli_error($dbCon));
        $arr = $result;
    }
} else {
    if (isset($_GET["api"])) {
        $ar = [];
        $api = $_GET["api"];
        $query = "SELECT `inspectionDate` FROM `machines` WHERE `apiToken` = '$api'";
        ($result = mysqli_query($dbCon, $query)) or
            die("database error:" . mysqli_error($dbCon));
        $data = mysqli_fetch_assoc($result);
        $ar["res"] = date_format(new DateTime($data["inspectionDate"]) ,"F d, Y");
        print json_encode($ar);
        die();
    }
}

print json_encode($arr);
?>
