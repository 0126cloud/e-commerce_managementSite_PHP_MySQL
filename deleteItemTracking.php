<?php
session_start();

//引入判斷是否登入機制
require_once('./checkSession.php');

//引用資料庫連線
require_once('./db.inc.php');
// $sql = "DELETE FROM `item_tracking` WHERE `id` = ? ";

$count = 0;

for($i = 0; $i < count($_POST['chk']); $i++){
    //加入繫結陣列
    $arrParam = [
        $_POST['chk'][$i]
    ];
    $sql = "SELECT `itemId` FROM `item_tracking` WHERE `itemId` = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($arrParam);

    if($stmt->rowCount() > 0) {
        $arr = $stmt->fetchAll();
        //找出特定 itemId 的資料

        $sql = "DELETE FROM `item_tracking` WHERE `itemId` = ? ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($arrParam);

        //累計每次刪除的次數
        $count += $stmt->rowCount();
    }
}

if($count > 0) {
    header("Refresh: 3; url=./itemTracking.php");
    $objResponse['success'] = true;
    $objResponse['code'] = 204;
    $objResponse['info'] = "已刪除商品追蹤";
    echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
} else {
    header("Refresh: 3; url=./itemTracking.php");
    $objResponse['success'] = false;
    $objResponse['code'] = 500;
    $objResponse['info'] = "刪除失敗";
    echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
}