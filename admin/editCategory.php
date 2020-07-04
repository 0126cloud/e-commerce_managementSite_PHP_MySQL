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
    img.itemImg {
        width: 250px;
    }
    </style>
</head>
<body>
<?php require_once('./templates/title.php'); ?>
<hr />
<h3>商品列表</h3>
<form name="myForm" enctype="multipart/form-data" method="POST" action="updateCategory.php">
    <table class="border table table-striped table-hover">
        <thead>
            <tr>
                <th class="border">種類名稱</th>
                <th class="border">類別開關</th>
                <th class="border">類別類型</th>
                <th class="border">新增時間</th>
                <th class="border">更新時間</th>
            </tr>
        </thead>
        <tbody>
        <?php
        //SQL 敘述
        $sql = "SELECT`categoryName`, `active`,  `categoryType`, `created_at`, `updated_at`
                FROM  `category`
                WHERE `category`.`categoryId` = ? ";

        $arrParam = [
            (int)$_GET['editCategoryId']
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
                    <input type="text" name="categoryName" value="<?php echo $arr[0]['categoryName']; ?>" maxlength="100" />
                </td>
                <td class="border">
                <select name="active" value="<?php echo $arr[0]['active']; ?>">
                    <option value="open">open</option>
                    <option value="closed">closed</option>
                </select>
                </td>
                <td class="border">
                <select name="categoryType" value="<?php echo $arr[0]['categoryType']; ?>">
                    <option value="社群">社群</option>
                    <option value="商城">商城</option>
                </select>
                </td>
                <td class="border"><?php echo $arr[0]['created_at']; ?></td>
                <td class="border"><?php echo $arr[0]['updated_at']; ?></td>
            </tr>
            
        <?php
        } else {
        ?>
            <tr>
                <td colspan="3">沒有資料</td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="border" colspan="5"><input type="submit" name="smb" value="更新"></td>
            </tr>
        </tfoo>
    </table>
    <input type="hidden" name="editCategoryId" value="<?php echo (int)$_GET['editCategoryId']; ?>">
</form>
</body>
</html>