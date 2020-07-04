<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

//若沒填寫商品種類時的行為
/*if( $_POST['discountName'] == ''){
    header("Refresh: 1; url=./market.php");
    $objResponse['success'] = false;
    $objResponse['code'] = 400;
    $objResponse['info'] = "請填寫種類";
    echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
}*/

//新增類別

    $sql = "INSERT INTO `marketing` (`discountName`,`discountAllowed`,`discountMethod`, `id`) VALUES (?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $arrParam = [
        $_POST['discountName'],
        $_POST['discountAllowed'],
        $_POST['discountMethod'], 
        $_POST['id']
    ];
    $stmt->execute($arrParam);
    
    if($stmt->rowCount() > 0) {
        header("Refresh: 0; url=./market.php");
        //$objResponse['success'] = true;
        //$objResponse['code'] = 200;
        //$objResponse['info'] = "新增成功";
        ?><script>alert("新增成功！回到行銷活動列表")</script><?php
        //echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
        exit();
    } else {
        header("Refresh: 0; url=./market.php");
        //$objResponse['success'] = false;
        //$objResponse['code'] = 400;
        //$objResponse['info'] = "新增失敗";
        ?><script>alert("新增失敗！回到行銷活動列表")</script><?php
        //echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }

