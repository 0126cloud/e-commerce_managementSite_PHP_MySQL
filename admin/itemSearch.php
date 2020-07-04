<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線
error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>我的php 程式</title>


    <style>
        img.itemImg {
            width: 100px;
           height: 100px;
        }
    </style>
</head>
<body>
<?php require_once('./templates/title.php'); ?>
<table class="border table table-striped table-hover">

<form action="itemSearch.php" method="POST" entype= "multipart/form-data">
<input name="search" type="text" placeholder="找茶" style="margin-bottom: 10px;">
<button class="btn mano_edit" type="submit" value="Search" style="margin-left: 10px;"><i class="far fa-file"></i> Search</button>
</form>
<form action="admin.php">
<button class="btn mano_edit" type="submit" value="Search" style="margin-left: 10px;"><i class="far fa-file"></i> 回商品列表</button>
</form>

        <thead>
            <tr>
                <!-- <th class="border">勾選</th> -->
                <th class="border">商品名稱</th>
                <th class="border">商品圖片</th>
                <th class="border">商品價格</th>
                <th class="border">商品數量</th>
                <th class="border">商品種類</th>
                <!-- <th class="border">新增時間</th>
                <th class="border">更新時間</th> -->
                <th class="border">功能</th>
            </tr>
        </thead>
        <tbody>
<?php

// if(empty($_POST['search'])) {
//     exit();
// }

$sql = "SELECT * FROM `items` WHERE `itemName`
LIKE ?";

$arr = ['%'.$_POST['search'].'%']; // ?代值，%需加.

$stmt = $pdo->prepare($sql);
$result = $stmt->execute($arr);

if($stmt->rowCount() > 0) {
    
$arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
for($i = 0; $i < count($arr); $i++) {
    ?>
        <tr>
            <!-- <td class="border">
                <input type="checkbox" name="chk[]" value="<?php //echo $arr2[$i]['itemId']; ?>" />
            </td> -->
            <td class="border"><?php echo $arr[$i]['itemName']; ?></td>
            <td class="border"><img class="itemImg" src="../images/items/<?php echo $arr[$i]['itemImg']; ?>" /></td>
            <td class="border"><?php echo $arr[$i]['itemPrice']; ?></td>
            <td class="border"><?php echo $arr[$i]['itemQty']; ?></td>
            <td class="border"><?php echo $arr[$i]['itemCategoryId']; ?></td>
            <!-- <td class="border"><?php //echo $arr[$i]['created_at']; ?></td>
            <td class="border"><?php //echo $arr[$i]['updated_at']; ?></td> -->

            <td class="border">
                    <a class="btn mano_check far fa-file" href="./edit.php?itemId=<?php echo $arr[$i]['itemId']; ?>">商品編輯</a>
                    <!-- <a  class="btn mano_edit far fa-images" style="margin: 12px 2px;" href="./multipleImages.php?itemId=<?php //echo $arr[$i]['itemId']; ?>">多圖設定</a><br> -->
                    <a  class="btn mano_edit far fa-folder-open"href="#" onclick="window.open('./multipleCat.php?itemId=<?php echo $arr[$i]['itemId']; ?>', '多分類設定', config='height=900,width=450,left=1000');">多分類設定</a>
                    <!-- <a  class="btn mano_edit fas fa-edit"href="./comments.php?itemId=<?php //echo $arr[$i]['itemId']; ?>">回覆評論</a> -->
                </td>

            <!-- <td class="border">
                <a href="./edit.php?itemId=<?php //echo $arr2[$i]['itemId']; ?>">商品編輯</a> | 
                <a href="./multipleImages.php?itemId=<?php //echo $arr2[$i]['itemId']; ?>">多圖設定</a> | 
                <a href="./comments.php?itemId=<?php //echo $arr2[$i]['itemId']; ?>">回覆評論</a>
            </td> -->
        </tr>
    <?php
        }
    } else {
    ?>
        <tr>
            <td class="border" colspan="9">沒有資料</td>
        </tr>
    <?php
    }
    ?>
</body>
</html>