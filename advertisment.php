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
        $arr["path"] = $adPic["ad_pic"];
        $arr["time"] = $adPic["ad_time"];
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["fileToUpload"]) && isset($_POST["cid"])) {
        $cid = $_POST["cid"];
        $target_dir = "img/";
        $target_file =
            $target_dir .
            basename($cid . "_" . $_FILES["fileToUpload"]["name"]);
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

        if (isset($_POST["api"])) {
            $api = $_POST["api"];
            $sql1 = "SELECT * FROM `advertisement_img` WHERE `machine_api`='$api'";
            ($result1 = mysqli_query($dbCon, $sql1)) or
                die("database error:" . mysqli_error($dbCon));
            $old = mysqli_fetch_assoc($result1);
            $oldAdPic = $old["ad_pic"];
            $sql2 = "SELECT * FROM `advertisement_img` WHERE `ad_pic`='$oldAdPic' AND `client_id` = $cid";
            ($result2 = mysqli_query($dbCon, $sql2)) or
                die("database error:" . mysqli_error($dbCon));
            if (mysqli_num_rows($result2) == 1) {
                $adPic = mysqli_fetch_assoc($result2);
                $oldPic = $adPic["ad_pic"];
                if (file_exists($oldPic)) {
                    unlink($oldPic);
                }
            }

            $query2 = "UPDATE `advertisement_img` SET `ad_pic`='$target_file' WHERE `machine_api`= '$api'";
        } else {
            $sql3 = "SELECT * FROM `advertisement_img` WHERE `client_id`='$cid'";
            ($result3 = mysqli_query($dbCon, $sql3)) or
                die("database error:" . mysqli_error($dbCon));

            while ($row = mysqli_fetch_assoc($result3)) {
                $oldAdPic = $row["ad_pic"];
                if (file_exists($oldAdPic)) {
                    unlink($oldAdPic);
                }
            }
            $query2 = "UPDATE `advertisement_img` SET `ad_pic`='$target_file' WHERE `client_id`= '$cid'";
        }
        ($result = mysqli_query($dbCon, $query2)) or
            die("database error:" . mysqli_error($dbCon));
        if (
            $result &&
            move_uploaded_file(
                $_FILES["fileToUpload"]["tmp_name"],
                $target_file
            )
        ) {
            $arr["res"] = "true";
        } else {
            $arr["res"] =
                "Sorry, there was an error uploading your file. Please try again";
        }
    } elseif (isset($_POST["time"])) {
        $time = $_POST["time"];
        if (isset($_POST["cid"])) {
            $cid = $_POST["cid"];
            $sql = "UPDATE `advertisement_img` SET `ad_time`='$time' WHERE `client_id` = '$cid'";
        } elseif (isset($_POST["api"])) {
            $api = $_POST["api"];
            $sql = "UPDATE `advertisement_img` SET `ad_time`='$time' WHERE `machine_api` = '$api'";
        }
        ($result2 = mysqli_query($dbCon, $sql)) or
            die("database error:" . mysqli_error($dbCon));
        if ($result2) {
            $arr["res"] = "true";
        } else {
            $arr["res"] = "Sorry, Time is not Updated. Please try again.";
        }
    }
}

print json_encode($arr);
?>
