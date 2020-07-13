<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

$objResponse = [];

//若沒填寫商品種類時的行為
if( $_POST['paymentStatus'] == '' ){
    header("Refresh: 1; url=./editorder.php?orderId={$_POST["orderId"]}");
    ?><script> 
    alert("請確實修改內容哦")</script> <?php
    exit();
}

$sql = "UPDATE `orders` SET `paymentStatus` = ? WHERE `orderId` = ?";
$stmt = $pdo->prepare($sql);
$arrParam = [
    $_POST['paymentStatus'], 
    $_POST["orderId"]
];
$stmt->execute($arrParam);
if($stmt->rowCount() > 0) {
    header("Refresh: 1; url=./orders.php");
    ?><script> 
    alert("更新成功！回到訂單列表哦")</script> <?php
    exit();
} else {
    header("Refresh: 1; url=./orders.php");
    ?><script> 
    alert("沒有任何更新！回到訂單列表哦")</script> <?php
    exit();
}