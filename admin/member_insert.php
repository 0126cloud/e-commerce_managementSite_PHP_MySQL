<?php
header("Content-Type: text/html; chartset=utf-8");

//引入判斷是否登入機制
// require_once('./checkSession.php');

//引用資料庫連線
require_once('../db.inc.php');

//SQL 敘述
$sql = "INSERT INTO `member` 
        (`memberId`, `memberName`, `class`, `paymentAddress`, `paymentCity`, `paymentZip`, `phone`, `email`, `pwd`, `paymentChoice`, `shipAddress`, `memberImg`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

if( $_FILES["memberImg"]["error"] === 0 ) {
    //為上傳檔案命名
    $memberImg = date("YmdHis");
    
    //找出副檔名
    $extension = pathinfo($_FILES["memberImg"]["name"], PATHINFO_EXTENSION);

    //建立完整名稱
    $imgFileName = $memberImg.".".$extension;

    //若上傳成功，則將上傳檔案從暫存資料夾，移動到指定的資料夾或路徑
    if( !move_uploaded_file($_FILES["memberImg"]["tmp_name"], "./files/".$imgFileName) ) {
        header("Refresh: 3; url=./member_admin.php");
        echo "圖片上傳失敗";
        exit();
    }
}

//繫結用陣列
$arr = [
    $_POST['memberId'],
    $_POST['memberName'],
    $_POST['class'],
    $_POST['paymentAddress'],
    $_POST['paymentCity'],
    $_POST['paymentZip'],
    $_POST['phone'],
    $_POST['email'],
    sha1($_POST['pwd']),
    $_POST['paymentChoice'],
    $_POST['shipAddress'],
    $imgFileName
];

$pdo_stmt = $pdo->prepare($sql);
$pdo_stmt->execute($arr);
if($pdo_stmt->rowCount() === 1) {
    header("Refresh: 3; url=./member_admin.php");
    echo "新增成功";
} else {
    header("Refresh: 3; url=./member_admin.php");
    echo "新增失敗";
}