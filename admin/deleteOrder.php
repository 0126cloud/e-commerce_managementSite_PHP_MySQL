<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

//刪除訂單
if(isset($_GET['orderId'])){
    $strOrderIds = "";;
    $strOrderIds.= $_GET['orderId'];
    
    $sql = "DELETE FROM `orders` WHERE `orderId` in ( {$strOrderIds} )";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $sql2 = "DELETE FROM `order_lists` WHERE `orderId` in ( {$strOrderIds} )";
    $stmt = $pdo->prepare($sql2);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        header("Refresh: 3; url=./orders.php");
        // $objResponse['success'] = true;
        // $objResponse['code'] = 200;
        // $objResponse['info'] = "刪除成功";
        // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
        ?><script> 
        alert("刪除成功！回到訂單列表哦")</script> <?php
        exit();
    } else {
        header("Refresh: 3; url=./orders.php");
        // $objResponse['success'] = false;
        // $objResponse['code'] = 400;
        // $objResponse['info'] = "刪除失敗";
        // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
        ?><script> 
        alert("刪除失敗！回到訂單列表哦")</script> <?php
        exit();
    }
}