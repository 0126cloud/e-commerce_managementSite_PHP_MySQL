<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

//回傳狀態
$objResponse = [];

$count = 0;

//使用迴圈走訪陣列元素
for($i = 0; $i < count($_POST["chk2"]); $i++){
    $strDatetime = date("YmdHis");
    //SQL 敘述
    $sql = "INSERT INTO `rel_item_cat`(`relItemId`, `categoryId`, `itemId`, `itemName`) 
    VALUES (?,?,?,?)";

    //繫結用陣列
    $arrParam = [
        $strDatetime,
        $_POST["chk2"][$i],
        $_POST["itemId"],
        $_POST["itemName"]
    ];

    $stmt = $pdo->prepare($sql);
    $count += $stmt->execute($arrParam);
}

if($count > 0) {
    header("Refresh: 1; url=./multipleCat.php?itemId={$_POST["itemId"]}");
    // $objResponse['success'] = true;
    // $objResponse['code'] = 200;
    ?><script>alert('新增成功')</script><?php
    // $objResponse['info'] = "新增成功";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
} else {
    header("Refresh: 1; url=./multipleCat.php?itemId={$_POST["itemId"]}");
    // $objResponse['success'] = false;
    // $objResponse['code'] = 500;
    ?><script>alert('沒有新增資料')</script><?php
    // $objResponse['info'] = "沒有新增資料";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
}