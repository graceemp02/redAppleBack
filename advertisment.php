<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
// header('Access-Control-Allow-Headers:  Content-Type');
include "mydbCon.php";

$arr = [];

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["api"]) && isset($_GET["cid"])) {

        $cid = $_GET["cid"];
        $api = $_GET["api"];
        $query = "SELECT * FROM `advertisement_img` WHERE `machine_api` = '$api'";
        ($result = mysqli_query($dbCon, $query)) or
            die("database error:" . mysqli_error($dbCon));
        $adPic = mysqli_fetch_assoc($result);
        $arr["path"] = $adPic['ad_pic'];
        // return image path here
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $arr["chk"] = "line 20";
    if (isset($_FILES["fileToUpload"]) && isset($_POST["cid"])) {
        $cid = $_POST["cid"];
        $target_dir = "img/";
        $target_file =
            $target_dir . basename($cid . $_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image

        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $arr["res"] = "Sorry, only JPG, JPEG & PNG files are allowed.";
            print json_encode($arr);
            return;
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            // $target_file = . $target_file
            $arr["res"] = "Sorry, change your file name and try again.";
            print json_encode($arr);
            return;
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 2000000) {
            $arr["res"] =
                "Sorry, your file is too large. <a href='https://smallseotools.com/image-compressor/' target='_blank'>Click here to reduce size</a>";
            print json_encode($arr);
            return;
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" &&
            $imageFileType != "png" &&
            $imageFileType != "jpeg"
        ) {
            $arr["res"] = "Sorry, only JPG, JPEG & PNG files are allowed.";
            print json_encode($arr);
            return;
            $uploadOk = 0;
        }
        echo $uploadOk;
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 1) {
            if (
                move_uploaded_file(
                    $_FILES["fileToUpload"]["tmp_name"],
                    $target_file
                )
            ) {
                echo "its uploaded";
                if (isset($_POST["api"])) {
                    echo "its inside api";
                    $api = $_POST["api"];
                    $query2 = "UPDATE `advertisement_img` SET `ad_pic`='$target_file' WHERE `machine_api`= '$api'";
                } else {
                    echo "its inside client";
                    $query2 = "UPDATE `advertisement_img` SET `ad_pic`='$target_file' WHERE `client_id`= '$cid'";
                }
                ($result = mysqli_query($dbCon, $query2)) or
                    die("database error:" . mysqli_error($dbCon));
                if ($result) {
                    $arr["res"] = "true";
                } else {
                    $arr["res"] = "false";
                }
            } else {
                $arr["res"] =
                    "Sorry, there was an error uploading your file. Please try again";
            }
        } else {
        }
    } elseif (isset($_POST["time"])) {
        // code before sql query
        if (isset($_POST["cid"])) {
            $cid = $_POST["cid"];
            // sql for all machine of cid customer
        } elseif (isset($_POST["api"])) {
            $api = $_POST["api"];
            // sql for specific machine
        }
        //code after sql query
    }
}

print json_encode($arr);
?>
