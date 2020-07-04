<?php
session_start();
require_once("./checkSession.php");
require_once('./db.inc.php');

if(!isset($_POST["paymentId"])){
    header("Refresh: 3; url=./myCart.php");
    // echo "請選擇付款方式…3秒後回購物車列表";
    ?><script> 
    alert("請選擇付款方式哦！回到購物車列表")</script> <?php
    exit();
}

//先取得訂單編號
$sqlOrder = "INSERT INTO `orders` (`username`,`paymentId`,`totalPrice`) VALUES (?,?,?)";
$stmtOrder = $pdo->prepare($sqlOrder);
$arrParamOrder = [
    $_SESSION["username"],
    $_POST["paymentId"],
    $_POST["total"]
];
$stmtOrder->execute($arrParamOrder);
$orderId = $pdo->lastInsertId();

$count = 0;

//新增購物車中的每一個項目
$sqlItemList = "INSERT INTO `order_lists` (`orderId`,`itemId`,`courseId`,`checkPrice`,`checkQuantity`,`checkSubtotal`) VALUES (?,?,?,?,?,?)";
$stmtItemList = $pdo->prepare($sqlItemList);
for($i = 0; $i < count($_POST["itemId"]); $i++){
    $arrParamItemList = [
        $orderId,
        $_POST["itemId"][$i],
        $_POST["courseId"][$i],
        $_POST["itemPrice"][$i],
        $_POST["cartQty"][$i],
        $_POST["subtotal"][$i]
    ];
    $stmtItemList->execute($arrParamItemList);
    $count += $stmtItemList->rowCount();
}

if($count > 0) {
    header("Refresh: 3; url=./order.php");

    //帳號完成後，注銷購物車資訊
    unset($_SESSION["cart"]);
    ?><script> 
    alert("訂單新增成功！轉到訂單列表哦")</script> <?php
    exit();
} else {
    header("Refresh: 3; url=./myCart.php");
    ?><script> 
    alert("訂單新增失敗！回到購物車列表哦")</script> <?php
    exit();
}
