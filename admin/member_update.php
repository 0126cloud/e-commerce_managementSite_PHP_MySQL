<?php
//引入判斷是否登入機制
// require_once('./checkSession.php');

//引用資料庫連線
require_once('../db.inc.php');

//切割 primary key 的字串成陣列
$arrPk = explode(",", $_POST['pk']);

//SQL 語法
$sql = "UPDATE `member` SET ";
$sql.= "`memberId` = ? ,";
$sql.= "`memberName` = ? ,";
$sql.= "`class` = ? ,";
$sql.= "`paymentAddress` = ? ,";
$sql.= "`paymentCity` = ? ";
$sql.= "`paymentZip` = ? ,";
$sql.= "`phone` = ? ,";
$sql.= "`email` = ? ,";
$sql.= "`pwd` = ? ,";
$sql.= "`paymentChoice` = ? ";
$sql.= "`shipAddress` = ? ,";
$sql.= "`rollDate` = ? ";
$sql.= "WHERE `id` = ? ";

//更新筆數
$count = 0;

for($i = 0; $i < count($arrPk); $i++) {
    $arrParam = [
        $_POST['memberId_' . $arrPk[$i]],
        $_POST['memberName_' . $arrPk[$i]],
        $_POST['class_' . $arrPk[$i]],
        $_POST['paymentAddress_' . $arrPk[$i]],
        $_POST['paymentCity_' . $arrPk[$i]],
        $_POST['paymentZip_' . $arrPk[$i]],
        $_POST['phone_' . $arrPk[$i]],
        $_POST['email_' . $arrPk[$i]],
        $_POST['pwd_' . $arrPk[$i]],
        $_POST['paymentChoice_' . $arrPk[$i]],
        $_POST['shipAddress_' . $arrPk[$i]],
        $_POST['rollDate_' . $arrPk[$i]],
        $_POST['memberImg_' . $arrPk[$i]],
        $arrPk[$i],
    ];

    $pdo_stmt = $pdo->prepare($sql);
    $pdo_stmt->execute($arrParam);
    $count += $pdo_stmt->rowCount();
}

if( $count > 0 ){
    header("Refresh: 3; url={$_SERVER['HTTP_REFERER']}");
    echo "更新成功";
} else {
    header("Refresh: 3; url={$_SERVER['HTTP_REFERER']}");
    echo "沒有任何更新";
}