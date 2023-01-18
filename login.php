<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET');
    // header('Access-Control-Allow-Headers: token, Content-Type, X-Requested-With');
include('mydbCon.php');
session_start();
$arr = [];
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['logout'])) {
        $_SESSION['loggedIn'] = false;
        session_unset();
        header("Location: /admin");
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username'])) {
        $usernameInput = $_POST['username'];
        $query = "SELECT * FROM adminprofile WHERE `Username`='$usernameInput'";
        $result = mysqli_query($dbCon, $query) or die("database error:" . mysqli_error($dbCon));
        $userCheck = mysqli_num_rows($result);
        if ($userCheck) {
            $passwordInput = $_POST['password'];
            $passcheck = mysqli_fetch_assoc($result);
            if ($passwordInput == $passcheck['password']) {
                $_SESSION['loggedIn'] = true;
                $_SESSION['username'] = $passcheck['FullName'];
                $_SESSION['userId'] = $passcheck['Id'];
                $arr['res'] = 'true';
                $arr['name'] = $passcheck['FullName'];
                $arr['id'] = $passcheck['Id'];
            } else { 
                $arr['res'] = 'Password Incorrent';
            }
        } else { 
            $arr['res'] = 'Username Does not Exist';
        }
    }
}
print(json_encode($arr));
?>

