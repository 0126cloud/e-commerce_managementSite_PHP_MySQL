<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

//若沒填寫商品種類時的行為
if( $_POST['categoryName'] == '' ){
    header("Refresh: 3; url=./category.php");
    // $objResponse['success'] = false;
    // $objResponse['code'] = 400;
    // $objResponse['info'] = "請填寫商品種類";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    ?><script>alert("請填寫商品種類")</script><?php
    exit();
}

//新增類別
if( isset($_POST['categoryId']) ){
    $sql = "INSERT INTO `category` (`categoryName`, `categoryParentId`, `active`, `categoryType`) VALUES (?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $arrParam = [
        $_POST['categoryName'], 
        $_POST['categoryId'],
        $_POST['active'],
        $_POST['categoryType'],
    ];
    $stmt->execute($arrParam);
    if($stmt->rowCount() > 0) {
        header("Refresh: 3; url=./category.php");
        // $objResponse['success'] = true;
        // $objResponse['code'] = 200;
        // $objResponse['info'] = "新增成功";
        // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
        ?><script>alert("新增成功")</script><?php
        exit();
    } else {
        header("Refresh: 3; url=./category.php");
        // $objResponse['success'] = false;
        // $objResponse['code'] = 400;
        // $objResponse['info'] = "新增失敗";
        // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
        ?><script>alert("新增失敗")</script><?php
        exit();
    }

} else {
    $sql = "INSERT INTO `category` (`categoryName`) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    $arrParam = [$_POST['categoryName']];
    $stmt->execute($arrParam);
    if($stmt->rowCount() > 0) {
        header("Refresh: 3; url=./category.php");
        $objResponse['success'] = true;
        $objResponse['code'] = 200;
        $objResponse['info'] = "新增成功";
        echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
        exit();
    } else {
        header("Refresh: 3; url=./category.php");
        $objResponse['success'] = false;
        $objResponse['code'] = 400;
        $objResponse['info'] = "新增失敗";
        echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }
}