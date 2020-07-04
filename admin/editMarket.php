<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線
?>
<!DOCTYPYE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>editMarkekt</title>
    <style>
    .border {
        border: 1px solid;
    }
    </style>
</head>
<body>
<?php require_once('./templates/title.php'); ?>
<hr />
<h3>行銷活動列表</h3>
<form name="myForm" method="POST" action="updateMarket.php">
    <table class="border table table-striped table-hover">
        <thead>
            <tr>
                <th class="border">種類名稱</th>
                <th class="border">行銷允許</th>
                <th class="border">行銷折扣</th>
                <th class="border">新增時間</th>
                <th class="border">更新時間</th>
            </tr>
        </thead>
        <tbody>
        <?php
        //SQL 敘述
        $sql = "SELECT `marketing`.`id`,`marketing`.`discountID`, 
                        `marketing`.`discountName`,`marketing`.`discountAllowed`,
                        `marketing`.`discountMethod`, `marketing`.`created_at`, 
                        `marketing`.`updated_at`
                FROM  `marketing`
                WHERE `marketing`.`id` = ? ";

        $arrParam = [
            (int)$_GET['id']
        ];

        //查詢
        $stmt = $pdo->prepare($sql);
        $stmt->execute($arrParam);

        //資料數量大於 0，則列出相關資料
        if($stmt->rowCount() > 0) {
            $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
            <tr>
                <td class="border">
                    <input type="text" name="discountName" value="<?php echo $arr[0]['discountName']; ?>" maxlength="100" />
                </td>
                <td class="border">
                <select name="discountAllowed" class="form-control">
                    <option value="open">open</option>
                    <option value="close">close</option>
                </select>
                </td>
                <td class="border"> <textarea name="discountMethod" value="<?php echo $arr[0]['discountMethod']; ?>"></textarea></td>
                <td class="border"><?php echo $arr[0]['created_at']; ?></td>
                <td class="border"><?php echo $arr[0]['updated_at']; ?></td>
            </tr>
        <?php
        } else {
        ?>
            <tr>
                <td class="border" colspan="5">沒有資料</td>
            </tr>
            <tr>
            <td class="border" colspan="5"><a class="btn mano_check far fa-file" href="./market.php">返回</a></td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
            <?php if($stmt->rowCount() > 0){ ?>
                <td class="border" colspan="5"><input class="btn mano_check far fa-file" type="submit" name="smb" value="更新">
               <a class="btn mano_check far fa-file" href="./market.php">返回</a></td>
            
            <?php } ?>
            </tr>
        </tfoo>
    </table>
    <input type="hidden" name="id" value="<?php echo (int)$_GET['id']; ?>">
</form>
</body>
</html>