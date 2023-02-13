<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
// header('Access-Control-Allow-Headers: token, Content-Type, X-Requested-With');
include "../mydbCon.php";

$arr = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"])) {
        $usernameInput = $_POST["username"];
        $query = "SELECT * FROM `customers` WHERE `company_id`='$usernameInput'";
        ($result = mysqli_query($dbCon, $query)) or
            die("database error:" . mysqli_error($dbCon));
        $userCheck = mysqli_num_rows($result);
        if ($userCheck) {
            $passwordInput = $_POST["password"];
            $passcheck = mysqli_fetch_assoc($result);
            if ($passwordInput == $passcheck["company_pass"]) {
                $arr["res"] = "true";
                $arr["id"] = $passcheck["Id"];
                $arr["name"] = $passcheck["FullName"];
            } else {
                $arr["res"] = "Password is Incorrect";
            }
        } else {
            $arr["res"] = "Username is Incorrect";
        }
    }
}
print json_encode($arr);
?>
