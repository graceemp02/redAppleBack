<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
// header('Access-Control-Allow-Headers: token, Content-Type, X-Requested-With');
include "../mydbCon.php";

$arr = [];

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];

        $query = "SELECT * FROM `companydata` WHERE `client_id` = '$id'";
        ($result = mysqli_query($dbCon, $query)) or
            die("database error:" . mysqli_error($dbCon));
        $data = mysqli_fetch_assoc($result);

        foreach ($data as $key => $value) {
            if ($key == "outAirAssessmentName") {
                $arr["res"] = "true";
                print json_encode($arr);
                die();
            }
            if (!$value) {
                $arr["res"] = "false";
                print json_encode($arr);
                die();
            }
            if (strpos($key, "Status") && $value != 2) {
                $arr["res"] = "false";
                print json_encode($arr);
                die();
            }
        }
    }
}
print json_encode($arr);

?>
