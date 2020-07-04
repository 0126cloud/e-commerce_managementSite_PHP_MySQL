<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

$count = 0;

for($i = 0; $i < count($_POST['chk']); $i++){
    //加入繫結陣列
    $arrParam = [
        $_POST['chk'][$i]
    ];

    //找出特定 relItemId 的資料
    $sqlRel = "DELETE FROM `rel_item_cat` WHERE `relItemId` = ? ";
    $stmt_rel = $pdo->prepare($sqlRel);
    $count += $stmt_rel -> execute($arrParam);
}

if($count > 0) {
    header("Refresh: 0; url=./multipleCat.php?itemId={$_POST["itemId"]}");
    // $objResponse['success'] = true;
    // $objResponse['code'] = 204;
    ?><script>alert('刪除成功')</script><?php
    // $objResponse['info'] = "刪除成功";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
} else {
    header("Refresh: 0; url=./multipleCat.php?itemId={$_POST["itemId"]}");
    // $objResponse['success'] = false;
    // $objResponse['code'] = 500;
    ?><script>alert('刪除失敗')</script><?php
    // $objResponse['info'] = "刪除失敗";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
}