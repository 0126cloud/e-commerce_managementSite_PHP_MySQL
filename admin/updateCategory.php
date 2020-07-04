<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

$objResponse = [];

//若沒填寫商品種類時的行為
if( $_POST['categoryName'] == '' ){
    header("Refresh: 3; url=./editCategory.php?editCategoryId={$_POST["editCategoryId"]}");
    // $objResponse['success'] = false;
    // $objResponse['code'] = 400;
    // $objResponse['info'] = "請填寫商品種類";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    ?><script>alert("請填寫商品種類")</script><?php
    exit();
}

$sql = "UPDATE `category`
        SET `categoryName` = ?,  `active` = ?, `categoryType` = ?
        WHERE `categoryId` = ?";

// $sql = "UPDATE `category` 
//         SET ";
// $sql.= " `categoryName` = ?,";
// $arrParam[] = $_POST['categoryName'];
// $sql.= " `active` = ?, ";
// $arrParam[] = $_POST['active'];
// $sql.= " `categoryType` = ? ";
// $arrParam[] = $_POST['categoryType'];
// $sql.="WHERE `categoryId` = ?";
// $arrParam[]=(int)$_POST['editCategoryId'];


$arrParam = [
    $_POST['categoryName'], 
    $_POST["active"],
    $_POST['categoryType'],
    (int)$_POST['editCategoryId']
];

$stmt = $pdo->prepare($sql);
$stmt->execute($arrParam);



if($stmt->rowCount() > 0) {
    header("Refresh: 3; url=./category.php");
    // $objResponse['success'] = true;
    // $objResponse['code'] = 204;
    // $objResponse['info'] = "更新成功";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    ?><script>alert("更新成功")</script><?php
    exit();
} else {
    header("Refresh: 3; url=./category.php");
    // $objResponse['success'] = false;
    // $objResponse['code'] = 400;
    // $objResponse['info'] = "沒有任何更新";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    ?><script>alert("沒有任何更新")</script><?php
    exit();
}