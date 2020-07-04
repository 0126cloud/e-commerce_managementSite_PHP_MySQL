<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

$sqlTotal = "SELECT count(1) FROM `item_tracking`"; //SQL 敘述
$total = $pdo->query($sqlTotal)->fetch(PDO::FETCH_NUM)[0]; //取得總筆數
$numPerPage = 5; //每頁幾筆
$totalPages = ceil($total/$numPerPage); // 總頁數
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; //目前第幾頁
$page = $page < 1 ? 1 : $page; //若 page 小於 1，則回傳 1

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
        width: 100px;
        height:100px;
    }
    .search-bar{
        margin-left:20px;
    }
    </style>
</head>
<body>
<?php require_once('./templates/title.php'); ?>
<hr />

<div class="search-bar">
<h3>商品追蹤管理</h3>
<form action="admin.itemTracking.search.php" method="POST" entype= "multipart/form-data">
<input name="search" type="text" placeholder="找商品追蹤" autocomplete="off">
<input class="btn mano_edit fas fa-edit" type="submit" value="Search">
</form>
</div>

<form name="myForm" method="POST" action="updateItemTracking.php">
    <table class="border table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col" class="border">設定日期</th>
                <th scope="col" class="border">買家帳號</th>
                <th scope="col" class="border">商品圖片</th>
                <th scope="col" class="border">單價</th>
                <th scope="col" class="border">數量</th>
                <th scope="col" class="border">最新訊息</th>
            </tr>
        </thead>
        <tbody>
        <?php
        //SQL 敘述
        $sql = "SELECT `items`.`itemId`, `items`.`itemName`, `items`.`itemImg`, `items`.`itemPrice`, 
                    `items`.`itemQty`, `items`.`itemCategoryId`, `items`.`created_at`, `items`.`updated_at`,
                    `category`.`categoryId`, `category`.`categoryName`,
                    `item_tracking`.`id` AS `itemTrackingId`,
                    `item_tracking`.`username`, `item_tracking`.`msg`,
                    `item_tracking`.`created_at`, `item_tracking`.`updated_at`
                FROM `items` INNER JOIN `category`
                ON `items`.`itemCategoryId` = `category`.`categoryId`
                INNER JOIN `item_tracking`
                ON `items`.`itemId` = `item_tracking`.`itemId`
                ORDER BY `item_tracking`.`created_at` DESC 
                LIMIT ?, ? ";

        $arrParam = [
            ($page - 1) * $numPerPage,
            $numPerPage
        ];

        //查詢 SQL
        $stmt = $pdo->prepare($sql);
        $stmt->execute($arrParam);

        //整理 primary key
        $strPk = '';

        //若商品項目個數大於 0，則列出商品
        if($stmt->rowCount() > 0) {
            $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            for($i = 0; $i < count($arr); $i++){ 
                if($strPk === '') 
                    $strPk = $arr[$i]['itemTrackingId']; 
                else 
                    $strPk.= ",".$arr[$i]['itemTrackingId'];
        ?>
            <tr>
                <td class="border"><?= $arr[$i]['created_at'] ?></td>
                <td class="border"><?= $arr[$i]['username'] ?></td>
                <td class="border">
                    <img class="itemImg" src="../images/items/<?= $arr[$i]["itemImg"] ?>" 
                        title="<?= $arr[$i]["itemName"] ?>" 
                        alt="<?= $arr[$i]["itemName"] ?>">
                </td>
                <td class="border"><?= $arr[$i]['itemPrice'] ?></td>
                <td class="border"><?= $arr[$i]['itemQty'] ?></td>
                <td class="border">
                    <textarea name="msg_<?= $arr[$i]['itemTrackingId'] ?>" 
                        cols="50" 
                        rows="2" 
                        data-item-tracking-id="<?= $arr[$i]['itemTrackingId'] ?>"><?= $arr[$i]['msg'] ?></textarea>
                </td>
            </tr>             
        <?php
            }
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="border" colspan="11">
                <?php for($i = 1; $i <= $totalPages; $i++){ ?>
                    <a href="?page=<?=$i?>"><?= $i ?></a>
                <?php } ?>
                </td>
            </tr>
        </tfoot>
    </table>
    <input type="hidden" name="pk" value="<?php echo $strPk; ?>">
    <button  class="btn mano_check"type="submit" name="smb" value="更新"><i class="fa far fa-file"></i> 更新</button>
</form>
</body>
</html>