<?php
require_once('./checkAdmin.php'); 
require_once('../db.inc.php'); 
//echo 4;
//var_dump($_GET);
//var_dump($_POST);
if(isset($_POST['chk'][0])){
    //echo 6;
    $strDiscountIds = "";;
    $strDiscountIds.= $_POST['chk'][0];
    
    $sql = "DELETE FROM `marketing` WHERE `id` in ( '{$strDiscountIds}' )";
    //var_dump($sql);

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0) {
        header("Refresh: 0; url=./market.php");
        //$objResponse['success'] = true;
        //$objResponse['code'] = 200;
        //$objResponse['info'] = "刪除成功";
        ?><script>alert("刪除成功！回到行銷活動列表")</script><?php
        //echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
        //echo 21;
        exit();
    } else {
        header("Refresh: 0; url=./market.php");
        //$objResponse['success'] = false;
        //$objResponse['code'] = 400;
        //$objResponse['info'] = "刪除失敗";
        ?><script>alert("刪除失敗！回到行銷活動列表")</script><?php
        //echo 28;
        //echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }
}
//echo 29;
