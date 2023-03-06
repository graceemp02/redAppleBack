<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
// header('Access-Control-Allow-Headers: token, Content-Type, X-Requested-With');
include "../mydbCon.php";

$arr = [];

function handleFile($name, $file, $id, $target_dir = "files/")
{
    $arr = [];
    include "../mydbCon.php";

    $target_file =
        $target_dir . basename($id . "_" . "$name" . "_" . $file["name"]);

    // Check allowed extensions
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (
        $fileType != "jpg" &&
        $fileType != "png" &&
        $fileType != "jpeg" &&
        $fileType != "pdf"
    ) {
        $arr["res"] = "file";
        print json_encode($arr);
        die();
    }

    //Check Allowed file size
    if ($file["size"] > 5000000) {
        $arr["res"] = "size";
        print json_encode($arr);
        die();
    }

    //Check & Remove already uploaded file
    $query = "SELECT $name FROM `gaesData` WHERE `customer_id`= '$id'";
    ($result = mysqli_query($dbCon, $query)) or
        die("database error:" . mysqli_error($dbCon));
    $oldData = mysqli_fetch_assoc($result);
    if ($oldData[$name]) {
        unlink($oldData[$name]);
    }
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    } else {
        $arr["res"] = "error";
        print json_encode($arr);
        die();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
        $noSystems = $_POST["noSystems"];
        $checqueNo = $_POST["checqueNo"];
        $paymentAmount = $_POST["paymentAmount"];
        $schedule = handleFile("schedule", $_FILES["schedule"], $id);
        $agreement = handleFile("agreement", $_FILES["agreement"], $id);
        $query = "UPDATE `gaesData` SET `noSystems` = '$noSystems',`schedule`='$schedule', `checqueNo`='$checqueNo',`paymentAmount`='$paymentAmount',`agreement`='$agreement' WHERE `customer_id` = '$id'";
        ($result = mysqli_query($dbCon, $query)) or
            die("database error:" . mysqli_error($dbCon));
            $arr['res'] = $result;
    }
}
print json_encode($arr);
?>