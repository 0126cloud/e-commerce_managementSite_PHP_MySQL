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
<h3>商品列表</h3>
<form name="myForm" enctype="multipart/form-data" method="POST" action="updateOrder.php">
    <table class="border table table-striped table-hover">
        <thead>
            <tr>
                <th class="border">付款狀態</th>
                <th class="border">更新時間</th>
            </tr>
        </thead>
        <tbody>
        <?php
        //SQL 敘述
        $sql = "SELECT `orderId`,`paymentStatus`, `updated_at`
                FROM `orders`
                WHERE `orderId` = ?";
        $stmt = $pdo->prepare($sql);
        $arrParam = [
            (int)$_GET['orderId']
        ];
        $stmt->execute($arrParam);

        if($stmt->rowCount() > 0) {
            $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
            <tr>
                <td class="border">
                    <select name="paymentStatus" id="paymentStatus" value="<?php echo $arr[0]['paymentStatus']; ?>">
                        <option value="未付款">未付款</option>
                        <option value="已付款">已付款</option>
                    </select>
                </td>
                <td class="border"><?php echo $arr[0]['updated_at']; ?></td>
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
                <td class="border" colspan="4"><input type="submit" name="smb" value="更新"></td>
            </tr>
        </tfoo>
    </table>
    <input type="hidden" name="orderId" value="<?php echo (int)$_GET['orderId']; ?>">
</form>
</body>
</html>