<?php
require_once("../../db_connect.php");

$id=$_GET["id"];
$sql="UPDATE coupons SET is_deleted = 1 WHERE id=$id"; 

if ($conn->query($sql) === TRUE) {
    echo "刪除成功";
} else {
    echo "刪除資料錯誤:" . $conn->error;
}


$conn->close();


header("location: coupons.php");