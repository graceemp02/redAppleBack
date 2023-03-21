<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
// header('Access-Control-Allow-Headers: token, Content-Type, X-Requested-With');
include "../mydbCon.php";

$arr = array([]);
$count = 0;
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(isset($_GET['api'])){
        $api = $_GET['api'];
        if (isset($_POST["fromDate"]) && isset($_POST["toDate"])) {
        $fromDate = $_POST["fromDate"];
        $toDate = $_POST["toDate"];
    }else {
        $fromDate = date("Y-m-d", strtotime("first day of this month"));
        $toDate = date("Y-m-d");
    }
    $query1 = "SELECT * FROM `display_Log` WHERE `Machine API Token` = '$api'";
    ($result1 = mysqli_query($dbCon, $query1)) or
        die("database error:" . mysqli_error($dbCon));
        while($a = mysqli_fetch_assoc($result1)){
            $datetime=new DateTime($a['timeStamp']);
            $dateinput = $datetime->format('Y-m-d');
            $date = $datetime->format('d/m/Y');
            $time = $datetime->format('H:i'); 
             if(($dateinput>=$fromDate)&&($dateinput<=$toDate)){
                 $arr[$count] = $a;
                 $arr[$count]['date'] = $date;
                 $arr[$count]['time'] = $time;
                 $count++;
                }
        }
        
    }
}
print json_encode($arr);
?>
