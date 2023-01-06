<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: token, Content-Type, X-Requested-With");
include "mydbCon.php";
$arr = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST)) {
        $newId = $_POST["id"];
        $newFullName = $_POST["name"];
        $newEmail = $_POST["email"];
        $newPhone = $_POST["phone"];
        $newCompanyName = $_POST["cName"];
        $newCompanyId = $_POST["cId"];
        $query = "UPDATE `customers` SET `FullName` = '$newFullName', `email` = '$newEmail', `phone` = '$newPhone', `company_name` = '$newCompanyName', `company_id` = '$newCompanyId' WHERE `customers`.`Id` = '$newId'";
        ($result = mysqli_query($dbCon, $query)) or
            die("database error:" . mysqli_error($dbCon));
        if($result) $arr['res'] = 'true';
    else $arr['res'] = 'false';
    }
}

print json_encode($arr);
?>

 