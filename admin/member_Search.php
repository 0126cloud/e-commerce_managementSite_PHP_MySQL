<?php 
// require_once('./checkSession.php');
error_reporting(0);
//引用資料庫連線
require_once('../db.inc.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>memberSearch</title>
    <style>
    .border {
        border: 1px solid;
    }
    .w100px{
        width:100px;
    }
    </style>
</head>
<body>
<?php require_once('./templates/title.php'); ?>
<hr />
    <!-- 會員搜尋功能 -->
<form action="member_Search.php" method="POST" entype= "multipart/form-data">
<table class="search table table-stripe table-hover">
    <tr>
    <td>
    <label for="memberOther">會員資料</label>
    <select name="memberSearch" id="">
        <option value="memberId">會員編號</option>
        <option value="memberName">姓名</option>
        <option value="class">會員等級</option>
        <option value="paymentAddress">帳單地址</option>
        <option value="paymentCity">帳單城市</option>
        <option value="paymentZip">帳單郵遞區號</option>
        <option value="phone">連絡電話</option>
        <option value="email">電子信箱</option>
        <option value="paymentChoice">付款方式</option>
        <option value="shipAddress">運送地址</option>
    </select>
    <input name="search" type="text" placeholder="會員關鍵字搜尋">
<input type="submit" value="搜尋">
    </td>
    </tr>
</table>
</form>

<form name="myForm" method="POST" action="member_deleteIds.php">
<table class="border search table table-stripe table-hover">
        <thead>
        <tr>
                <th class="border">選擇</th>
                <th class="border">會員編號</th>
                <th class="border">姓名</th>
                <th class="border">會員等級</th>
                <th class="border">帳單地址</th>
                <th class="border">帳單城市</th>
                <th class="border">帳單郵遞區號</th>
                <th class="border">連絡電話</th>
                <th class="border">電子信箱</th>
                <th class="border">付款方式</th>
                <th class="border">運送地址</th>
                <th class="border">會員註冊時間</th>
                <th class="border">會員大頭貼</th>
                <th class="border">功能</th>
            </tr>
        </thead>
        <tbody>


<br/>
<br/>
<!-- sql搜尋會員 -->
<?php

// echo "<pre>";
// print_r($s);
// echo "</pre>";
// exit();

$s = $_POST['memberSearch'];

$sql2 = "SELECT *
         FROM `member`
         WHERE `{$s}` LIKE '%{$_POST['search']}%'
         ORDER BY `memberId` ASC";


$stmt2 = $pdo->prepare($sql2);
$stmt2->execute();

if($stmt2->rowCount() > 0) {
    $arr = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    for($i = 0; $i < count($arr); $i++) {
?>
    <tr>
                <td class="border">
                    <input type="checkbox" name="chk[]" value="<?php echo $arr[$i]['id']; ?>" />
                </td>
                <td class="border"><?php echo $arr[$i]['memberId']; ?></td>
                <td class="border"><?php echo $arr[$i]['memberName']; ?></td>
                <td class="border"><?php echo $arr[$i]['class']; ?></td>
                <td class="border"><?php echo $arr[$i]['paymentAddress']; ?></td>
                <td class="border"><?php echo $arr[$i]['paymentCity']; ?></td>
                <td class="border"><?php echo nl2br($arr[$i]['paymentZip']); ?></td>
                <td class="border"><?php echo $arr[$i]['phone']; ?></td>
                <td class="border"><?php echo $arr[$i]['email']; ?></td>
                <td class="border"><?php echo $arr[$i]['paymentChoice']; ?></td>
                <td class="border"><?php echo $arr[$i]['shipAddress']; ?></td>
                <td class="border"><?php echo $arr[$i]['rollDate']; ?></td>
                <td class="border">
                <?php if($arr[$i]['memberImg'] !== NULL) { ?>
                    <img class="w100px" src="./files/<?php echo $arr[$i]['memberImg']; ?>">
                <?php } ?>
                </td>
                <td class="border">
                    <a href="./member_edit.php?editId=<?php echo $arr[$i]['id']; ?>">編輯</a>
                    <a href="./member_delete.php?deleteId=<?php echo $arr[$i]['id']; ?>">刪除</a>
                </td>
            </tr>
<?php
    }
} else {
?>
    <tr>
        <td class="border" colspan="15">沒有資料</td>
    </tr>

<?php
}
?>
</tbody>
</table>
<input class="btn mano_delete fas fa-trash-alt" type="submit" name="smb" value="刪除">
</form>
</body>
</html>