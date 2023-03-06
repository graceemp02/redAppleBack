<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET');
    // header('Access-Control-Allow-Headers: token, Content-Type, X-Requested-With');
include('../mydbCon.php');

$arr = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'])) {
        $emailInput = $_POST['email'];
        $query = "SELECT * FROM customers WHERE `email`='$emailInput'";
        $result = mysqli_query($dbCon, $query) or die("database error:" . mysqli_error($dbCon));
        $userCheck = mysqli_num_rows($result);
        if ($userCheck) {
            $passwordInput = $_POST['password'];
            $passcheck = mysqli_fetch_assoc($result);
            if ($passwordInput == $passcheck['password']) {
                $arr['res'] = 'true';
                $arr['email'] = $passcheck['email'];
                $arr['id'] = $passcheck['Id'];
            } else { 
                $arr['res'] = 'Password Incorrent';
            }
        } else { 
            $arr['res'] = 'Email Does not Exist';
        }
    }
}
print(json_encode($arr));
?>

