<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

//建立種類列表

$sql = "SELECT DISTINCT `rel_item_cat`.`categoryId`AS'CID',`category`.`categoryName` AS 'CN'
        FROM `rel_item_cat` 
        INNER JOIN `category`
        ON `rel_item_cat`.`categoryId`=`category`.`categoryId`
        ORDER BY `rel_item_cat`.`categoryId` ";
    $arrParam = [];
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0) {
    $arrOnlyCat = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>



<!DOCTYPYE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>多類別設定</title>
    <style>
    .border {
        border: 1px solid;
    }
    img.previous_images{
        width: 100px;
    }
    </style>
</head>
<body >
<?php require_once('./templates/title.php'); ?>
<hr />
<h3 style="text-align:center;">多類別設定</h3>

<form name="myForm" method="POST" action="./deleteMultipleCat.php" >
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th class="border" style="white-space: nowrap;">選擇</th>
            <th class="border">類別名稱</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT DISTINCT `rel_item_cat`.`categoryId`,`items`.`itemId`,`relItemId`,`category`.`categoryName`AS 'CN'
            FROM `rel_item_cat` 
            INNER JOIN `items`
            INNER JOIN `category`
            ON `items`.`itemId` = ? AND
            `items`.`itemId` = `rel_item_cat`.`itemId` 
            AND `rel_item_cat`.`categoryId`=`category`.`categoryId`";
    $stmt = $pdo->prepare($sql);
    $arrParam = [
        $_GET['itemId']
    ];
    $stmt->execute($arrParam);
    if($stmt->rowCount() > 0) {
        $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        for($i = 0; $i < count($arr); $i++) {
    ?>
        <tr>
            <td class="border shrink">
                <input type="checkbox" name="chk[]" value="<?php echo $arr[$i]['relItemId']; ?>" />
            </td>
            <td class="border expand">      
             <?php echo $arr[$i]['CN']; ?>
            </td>
        </tr>

    <?php
        }
    } else {
    ?>

        <tr><td class="border" colspan="2">沒有類別</td></tr>

<?php
}
?>
    
    <tfoot>
    <td colspan="2" class="border"><button class="btn mano_delete"type="submit" name="smb_delete" value="刪除"><i class="fas fa-trash-alt"></i>&nbsp刪除</button></td>
    </tfoot>
    </table>
    <input type="hidden" name="itemId" value="<?php echo (int)$_GET['itemId']; ?>">
</form>

<hr />

<form name="myForm" method="POST" action="./insertMultipleCat.php" enctype="multipart/form-data">
    <table class="border table table-striped table-hover">
        <thead>
            <tr>
            <th class="border" style="white-space: nowrap;">選擇</th>
            <th class="border">類別名稱</th>
            </tr>
        </thead>
        <tbody>
        <?php for($i = 0; $i < count($arrOnlyCat); $i++) { ?>
            <tr>
            <td class="border shrink">
                <input type="checkbox" name="chk2[]" value="<?php echo $arrOnlyCat[$i]['CID']; ?>" />
            </td>
            <td class="border expand">
             <?php echo $arrOnlyCat[$i]['CN']; ?>
            </td>
            </tr>
    <?php
        }
    ?>
    
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="border"><button class="btn mano_check" type="submit" name="smb_add" value="新增"><i class="fas fa-file">&nbsp新增</button></td>
            </tr>
        </tfoot>
    </table>
    <input type="hidden" name="itemId" value="<?php echo (int)$_GET['itemId']; ?>">
    <input type="hidden" name="itemName" value="<?php echo $_GET['itemName']; ?>">
</form>
</body>
</html>