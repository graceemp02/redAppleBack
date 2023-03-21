<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: token, Content-Type, X-Requested-With");
include "mydbCon.php";
$arr = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST)) {
        $newFullName = $_POST["name"];
        $newEmail = $_POST["email"];
        $newPhone = $_POST["phone"];
        $newPassword = $_POST["pwd"];
        $newCompanyName = $_POST["companyName"];
        $newCompanyEmail = $_POST["companyEmail"];
        $newCompanyId = $_POST["companyId"];
        $newCompanyPass = $_POST["companyPwd"];

        //Inserting Customer
        $query = "INSERT INTO `customers` (`Id`, `FullName`, `email`, `phone`, `password`, `company_name`, `company_email`, `company_id`, `company_pass`, `dateCreation`) VALUES (NULL, '$newFullName', '$newEmail', '$newPhone', '$newPassword', '$newCompanyName', '$newCompanyEmail', '$newCompanyId', '$newCompanyPass', current_timestamp())";
        ($result = mysqli_query($dbCon, $query)) or
            die("database error:" . mysqli_error($dbCon));

        // Getting the customer id
        $query2 = "SELECT * FROM `customers` WHERE `FullName` = '$newFullName'";
        ($result2 = mysqli_query($dbCon, $query2)) or
            die("database error:" . mysqli_error($dbCon));
        $details = mysqli_fetch_assoc($result2); 
        $customerId = $details["Id"];

        // Adding new client details in company, inspector, and gaes data
        $query3 = "INSERT INTO `companyData` (`client_id`, `client_name`) VALUES ('$customerId', '$newFullName')";
        ($result3 = mysqli_query($dbCon, $query3)) or
            die("database error:" . mysqli_error($dbCon));
        $query4 = "INSERT INTO `gaesData` (`customer_id`, `customer_name`) VALUES ('$customerId', '$newFullName')";
        ($result4 = mysqli_query($dbCon, $query4)) or
            die("database error:" . mysqli_error($dbCon));
        $query5 = "INSERT INTO `inspectorData` (`customer_id`, `customer_name`) VALUES ('$customerId', '$newFullName')";
        ($result5 = mysqli_query($dbCon, $query5)) or
            die("database error:" . mysqli_error($dbCon));
        if($result5){
            $arr['res'] = 'true';
        } else {
            $arr['res'] = 'false';
        }
    }
}

print json_encode($arr);
?>
