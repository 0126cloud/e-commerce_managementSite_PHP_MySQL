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
<h3>付款方式列表</h3>
<form name="myForm" enctype="multipart/form-data" method="POST" action="updatePaymentType.php">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th style="white-space: nowrap;">開放狀態</th>
                <th class="border">付款方式名稱</th>
                <th class="border">付款方式圖片</th> 
                <th class="border">新增時間</th>
                <th class="border">更新時間</th>
            </tr>
        </thead>
        <tbody>
        <?php
        //SQL 敘述
        $sql = "SELECT `paymentMethod`,`paymentAllowed`, `paymentTypeImg`, `created_at`, `updated_at`
                FROM `payment`
                WHERE `paymentId` = ?";
        $stmt = $pdo->prepare($sql);
        $arrParam = [
            (int)$_GET['paymentId']
        ];
        $stmt->execute($arrParam);
        if($stmt->rowCount() > 0) {
            $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
            <tr>
            <td class="shrink">              
                <select id="inputStatus" name="paymentAllowed" class="form-control" value="<?php echo $arr[0]['paymentAllowed']; ?>">
                    <option value="開">開</option>
                    <option value="關">關</option>
                </select>
            </td> 
                <td class="border shrink">
                    <input type="text" name="paymentMethod" value="<?php echo $arr[0]['paymentMethod']; ?>" maxlength="100" />
                </td>
                <td class="border shrink">
                    <img class="payment_type_icon" src="../images/payment_types/<?php echo $arr[0]['paymentTypeImg']; ?>" />
                    <input type="file" name="paymentTypeImg" value="" />
                </td>
                
                <td class="border"style="white-space: nowrap;"><?php echo $arr[0]['created_at']; ?></td>
                <td class="border expand"><?php echo $arr[0]['updated_at']; ?></td>
            </tr>
        <?php
        } else {
        ?>
            <tr>
                <td colspan="4">沒有資料</td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="border" colspan="5"><button class="btn mano_check" type="submit" name="smb" value=""><i class='far fa-file'></i>&nbsp更新</button></td>
            </tr>

        </tfoot>
    </table>
    <input type="hidden" name="paymentId" value="<?php echo (int)$_GET['paymentId']; ?>">
</form>
</body>
</html>