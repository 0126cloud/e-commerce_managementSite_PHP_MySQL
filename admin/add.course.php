<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
//exit();


//回傳狀態
$objResponse = [];

if( $_FILES["courseImg"]["error"] === 0 ) {
    //為上傳檔案命名
    $strDatetime = "course_".date("YmdHis");
        
    //找出副檔名
    $extension = pathinfo($_FILES["courseImg"]["name"], PATHINFO_EXTENSION);

    //建立完整名稱
    $courseImg = $strDatetime.".".$extension;

    //若上傳失敗，則回報錯誤訊息
    if( !move_uploaded_file($_FILES["courseImg"]["tmp_name"], "../images/course/{$courseImg}") ) {
        $objResponse['success'] = false;
        $objResponse['code'] = 500;
        $objResponse['info'] = "上傳圖片失敗";
        echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }
}

//SQL 敘述
$sql = "INSERT INTO `course` (`courseName`, `courseDesc`, `courseImg`, `coursePrice`, `coursePeriod`, `courseLocation`,`courseQty`, `courseCategoryId`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

//繫結用陣列
$arrParam = [
    $_POST['courseName'],
    $_POST['courseDesc'],
    $courseImg,
    $_POST['coursePrice'],
    $_POST['coursePeriod'],
    $_POST['courseLocation'],
    $_POST['courseQty'],
    $_POST['courseCategoryId']
];

$stmt = $pdo->prepare($sql);
$stmt->execute($arrParam);

if($stmt->rowCount() > 0) {
    header("Refresh: 1; url=./admin.course.search.php");
    // $objResponse['success'] = true;
    // $objResponse['code'] = 200;
    // $objResponse['info'] = "新增成功";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    ?><script>alert("新增成功! 回到課程列表")</script><?php 
    exit();
} else {
    header("Refresh: 1; url=./admin.course.search.php");
    // $objResponse['success'] = false;
    // $objResponse['code'] = 500;
    // $objResponse['info'] = "沒有新增資料";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    ?><script>alert("沒有新增資料")</script><?php 
    exit();
}