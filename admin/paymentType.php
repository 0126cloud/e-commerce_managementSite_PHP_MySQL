<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線
?>
<!DOCTYPYE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>我的 PHP 程式</title>
    <style>
    .border {
        border: 1px solid;
    }
    img.payment_type_icon{
        width: 50px;
    }
    </style>
</head>
<body>
<?php require_once('./templates/title.php'); ?>
<hr />
<h3>編輯付款方式</h3>

<form name="myForm" method="POST" action="./deletePaymentType.php">
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th class="border">選擇</th>
            <th class="border">開放狀態</th>
            <th class="border">付款方式名稱</th>
            <th class="border">付款方式圖片</th>
            <th class="border">功能</th>
        </tr>
    </thead>
    <tbody>
<?php
$sql = "SELECT `paymentId`, `paymentAllowed`,`paymentMethod`, `paymentTypeImg`
        FROM `payment`
        ORDER BY `paymentId` ASC";
$stmt = $pdo->prepare($sql);
$arrParam = [];
$stmt->execute($arrParam);
if($stmt->rowCount() > 0) {
    $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
    for($i = 0; $i < count($arr); $i++) {
?>
    <tr>
        <td class="border">
            <input type="checkbox" name="chk[]" value="<?php echo $arr[$i]['paymentId']; ?>" />
        </td>
        <td><?php echo $arr[$i]['paymentAllowed'] ?></td>
        <td class="border"><?php echo $arr[$i]['paymentMethod'] ?></td>
        <td class="border">
            <img class="payment_type_icon" src="../images/payment_types/<?php echo $arr[$i]['paymentTypeImg'] ?>">
        </td>
        <td class="border">
            <a href="editPaymentType.php?paymentId=<?php echo $arr[$i]['paymentId']; ?>" class="btn mano_edit fas fa-edit" style="width:80px;height:38px; line-height:23px;">編輯</a>
            <button type="submit" name="smb_delete" class="btn mano_delete " value=""><i class="fas fa-trash-alt"></i>&nbsp刪除</button>
        </td>
        
    </tr>
  
<?php
    }
} else {
?>

<tr><td class="border" colspan="4">尚未建立付款方式</td></tr>

<?php
}
?>
</table>
<button type="submit" name="smb_delete" class="btn mano_delete " value=""><i class="fas fa-trash-alt"></i>&nbsp將所選項目刪除</button>
</form>

<hr />

<form name="myForm" method="POST" action="./insertPaymentType.php" enctype="multipart/form-data">
<table class="table table-striped table-hover">
    <thead>
        <tr>
        <th class="border shrink">付款方式名稱</th>
        <th class="border">付款方式圖片</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="border shrink">
                <input type="text" name="paymentMethod" value="" maxlength="100" />
            </td>
            <td class="border expand">
                <input type="file" name="paymentTypeImg" value="" />
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td><button class="btn mano_check" type="submit" name="smb_add" ><i class='far fa-file'></i>&nbsp新增</button></td>
        </tr>
    </tfoot>
</table>

</form>
</body>
</html>