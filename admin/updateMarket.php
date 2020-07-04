<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

//$objResponse = [];
/*$arrParam = [];

$sql = "UPDATE `marketing` SET ";

$sql.= "`discountName` = ? ,";
$arrParam[] = $_POST['discountName'];
$sql.= "`discountAllowed` = ?, ";
$arrParam[] = $_POST['discountAllowed'];
$sql.= "`discountMethod` = ?, ";
$arrParam[] = $_POST['discountMethod'];*/



//若沒填寫商品種類時的行為
/*if( $_POST['discountName'] == '' ){
    header("Refresh: 1; url=./editMarket.php?id={$_POST['id']}");
    $objResponse['success'] = false;
    $objResponse['code'] = 400;
    $objResponse['info'] = "請填寫商品種類";
    echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
}*/

$sql = "UPDATE `marketing` 
        SET ";
$sql.= " `discountName` = ? ,";
$arrParam[] = $_POST['discountName'];
$sql.= " `discountAllowed` = ?, ";
$arrParam[] = $_POST['discountAllowed'];
$sql.= " `discountMethod` = ? ";
$arrParam[] = $_POST['discountMethod'];
$sql.="WHERE `id` = ?";
$arrParam[]=(int)$_POST['id'];

//echo $arrParam;

$stmt = $pdo->prepare($sql);
$stmt->execute($arrParam);

if($stmt->rowCount() > 0) {
    header("Refresh: 0; url=./market.php");
    //$objResponse['success'] = true;
    //$objResponse['code'] = 204;
    //$objResponse['info'] = "更新成功";
    ?><script> 
    alert("新增成功！回到行銷活動列表")</script> <?php
    exit();
} else {
    header("Refresh: 0; url=./editMarket.php?id={$_POST['id']}");
    //$objResponse['success'] = false;
    //$objResponse['code'] = 400;
    //$objResponse['info'] = "沒有任何更新";
    ?><script> 
    alert("沒有新增資料")</script> <?php
    //echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
}