<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
// exit();


//回傳狀態
$objResponse = [];

//用在繫結 SQL 用的陣列
$arrParam = [];


//SQL 語法
$sql = "UPDATE `course` SET ";

//itemName SQL 語句和資料繫結
$sql.= "`courseName` = ? ,";
$arrParam[] = $_POST['courseName'];

if( $_FILES["courseImg"]["error"] === 0 ) {
    //為上傳檔案命名
    $strDatetime = "course_".date("YmdHis");
        
    //找出副檔名
    $extension = pathinfo($_FILES["courseImg"]["name"], PATHINFO_EXTENSION);

    //建立完整名稱
    $itemImg = $strDatetime.".".$extension;

    //若上傳成功 (有夾帶檔案上傳)，則將上傳檔案從暫存資料夾，移動到指定的資料夾或路徑
    if( move_uploaded_file($_FILES["courseImg"]["tmp_name"], "../images/course/{$courseImg}") ) {
        //先查詢出特定 id (itemId) 資料欄位中的大頭貼檔案名稱
        $sqlGetImg = "SELECT `courseImg` FROM `course` WHERE `courseId` = ? ";
        $stmtGetImg = $pdo->prepare($sqlGetImg);

        //加入繫結陣列
        $arrGetImgParam = [
            (int)$_POST['courseId']
        ];

        //執行 SQL 語法
        $stmtGetImg->execute($arrGetImgParam);

        //若有找到 itemImg 的資料
        if($stmtGetImg->rowCount() > 0) {
            //取得指定 id 的商品資料 (1筆)
            $arrImg = $stmtGetImg->fetchAll(PDO::FETCH_ASSOC);

            //若是 itemImg 裡面不為空值，代表過去有上傳過
            if($arrImg[0]['courseImg'] !== NULL){
                //刪除實體檔案
                @unlink("./images/course/".$arrImg[0]['courseImg']);
            } 

            //itemImg SQL 語句字串
            $sql.= "`courseImg` = ? ,";

            //僅對 itemImg 進行資料繫結
            $arrParam[] = $courseImg;
            
        }
    }
}

//itemPrice SQL 語句和資料繫結
$sql.= "`courseDesc` = ? , ";
$arrParam[] = $_POST['courseDesc'];

$sql.= "`coursePrice` = ? , ";
$arrParam[] = $_POST['coursePrice'];

//itemQty SQL 語句和資料繫結
$sql.= "`courseQty` = ? , ";
$arrParam[] = $_POST['courseQty'];

//itemCategoryId SQL 語句和資料繫結
$sql.= "`courseCategoryId` = ? ";
$arrParam[] = $_POST['courseCategoryId'];


$sql.= "WHERE `courseId` = ? ";
$arrParam[] = (int)$_POST['courseId'];


$stmt = $pdo->prepare($sql);
$stmt->execute($arrParam);


if( $stmt->rowCount()> 0 ){
    header("Refresh: 1; url=./admin.course.search.php");
    // $objResponse['success'] = true;
    // $objResponse['code'] = 204;
    // $objResponse['info'] = "更新成功";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    ?><script>alert("更新成功! 回到課程列表")</script><?php 
    exit();
} else {
    header("Refresh: 1; url=./edit.course.php?courseId={$_POST['courseId']}");
    // $objResponse['success'] = false;
    // $objResponse['code'] = 400;
    // $objResponse['info'] = "沒有任何更新";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    ?><script>alert("沒有任何更新")</script><?php 
    exit();
}