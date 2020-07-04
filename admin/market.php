<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線


$sqlTotal = "SELECT count(1) FROM `marketing`"; //SQL 敘述
$total = $pdo->query($sqlTotal)->fetch(PDO::FETCH_NUM)[0]; //取得總筆數
$numPerPage = 5; //每頁幾筆
$totalPages = ceil($total/$numPerPage); // 總頁數
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; //目前第幾頁
$page = $page < 1 ? 1 : $page; //若 page 小於 1，則回傳 1



//商品種類 SQL 敘述
$sqlTotalDiscounts = "SELECT count(1) FROM `marketing`";

//取得商品種類總筆數
$totalDiscounts = $pdo->query($sqlTotalDiscounts)->fetch(PDO::FETCH_NUM)[0];
?>
<!DOCTYPYE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Marketing</title>
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
<h3>行銷活動列表</h3>

<form action="marketSearch.php" method="GET" enctype= "multipart/form-data">
<input name="search" type="text" placeholder="找活動">
<input class="btn mano_check far fa-file" type="submit" value="搜尋">
</form>
<br>
<?php 
if($totalDiscounts > 0) {
?>
<form name="myForm" entype= "multipart/form-data" method="POST" action="./deleteMarket.php">
    <table class="border table table-striped table-hover">
        <thead>
            <tr>
                <th class="border">勾選</th>
                <th class="border">行銷ID</th>
                <th class="border">行銷名稱</th>
                <th class="border">行銷時段</th>
                <th class="border">行銷允許</th>
                <th class="border">行銷折扣</th>
                <th class="border">更新時間</th>
                <th class="border">功能</th>
            </tr>
        </thead>
        <tbody>
        <?php
        //SQL 敘述
        $sql = "SELECT `marketing`.`id`,`marketing`.`discountID`, `marketing`.`discountName`, `marketing`.`discountPeriod`, `marketing`.`discountAllowed`, 
                        `marketing`.`discountMethod`, `marketing`.`updated_at`
                FROM `marketing` 
                ORDER BY `marketing`.`discountID` ASC 
                LIMIT ?, ? ";

        //設定繫結值
        $arrParam = [($page - 1) * $numPerPage, $numPerPage];

        //查詢分頁後的商品資料
        $stmt = $pdo->prepare($sql);
        $stmt->execute($arrParam);

        //若數量大於 0，則列出商品
        if($stmt->rowCount() > 0) {
            $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            for($i = 0; $i < count($arr); $i++) {
        ?>
            <tr>
                <td class="border">
                    <input type="checkbox" name="chk[]" value="<?php echo $arr[$i]['id']; ?>" />
                </td>
                <td class="border"><?php echo $arr[$i]['discountID']; ?></td>
                <td class="border"><?php echo $arr[$i]['discountName']; ?></td>
                <td class="border"><?php echo $arr[$i]['discountPeriod']; ?></td>
                <td class="border"><?php echo $arr[$i]['discountAllowed']; ?></td>
                <td class="border"><?php echo $arr[$i]['discountMethod']; ?></td>
                <td class="border"><?php echo $arr[$i]['updated_at']; ?></td>
                <td class="border">
                    <a class="btn mano_edit fas fa-edit" href="./editMarket.php?id=<?php echo $arr[$i]['id']; ?>">編輯</a> 
                </td>
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
        </tbody>
        <tfoot>
            <tr>
                <td class="border" colspan="9">
                <?php for($i = 1; $i <= $totalPages; $i++){ ?>
                    <a href="?text<?php echo $_GET['text']?>&page=<?=$i?>"><?= $i ?></a>
                <?php } ?>
                </td>
            </tr>
            
            <?php if($total > 0) { ?>
            <tr>
                <td class="border" colspan="9"><input class="btn mano_delete fas fa-trash-alt" type="submit" name="smb" value="刪除"></td>
            </tr>
            <?php } ?>
            
        </tfoot>
    </table>
</form>
<?php 
} else { 
    //引入尚未建立商品種類的文字描述
    require_once('./templates/noCategory.php');
}?>
</body>
</html>