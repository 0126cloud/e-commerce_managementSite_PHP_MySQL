<!DOCTYPYE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>我的 PHP 程式</title>
    <style>
    .border {
        border: 1px solid;
    
    }
    img.courseImg {
        width: 200px;
        
    }
    .desc{
        color:gray;
    }

    </style>
<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線


// $sqlTotal = "SELECT count(1) FROM `item_tracking`"; //SQL 敘述
// $total = $pdo->query($sqlTotal)->fetch(PDO::FETCH_NUM)[0]; //取得總筆數
// $numPerPage = 5; //每頁幾筆
// $totalPages = ceil($total/$numPerPage); // 總頁數
// $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; //目前第幾頁
// $page = $page < 1 ? 1 : $page; //若 page 小於 1，則回傳 1



//商品種類 SQL 敘述
$sqlTotalCatogories = "SELECT count(1) FROM `item_tracking`";

//取得商品種類總筆數
$totalCatogories = $pdo->query($sqlTotalCatogories)->fetch(PDO::FETCH_NUM)[0];
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
    .desc{
        color:gray;
    }
    .num{
        margin-top:15px;
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
<h3>搜尋結果</h3>
<br>
<form action="admin.itemTracking.search.php" method="POST" entype= "multipart/form-data">
<input name="search" type="text" placeholder="找商品追蹤" autocomplete="off">
<input class="btn mano_edit fas fa-edit" type="submit" value="Search">
</form>

<br>
<a href="./itemTracking.php">返回商品追蹤列表</a>
<br>
</div>
<br>


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

$sqlTotal = "SELECT count(1) FROM `item_tracking`"; //SQL 敘述
$total = $pdo->query($sqlTotal)->fetch(PDO::FETCH_NUM)[0]; //取得總筆數
$numPerPage = 5; //每頁幾筆
$totalPages = ceil($total/$numPerPage); // 總頁數
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; //目前第幾頁
$page = $page < 1 ? 1 : $page; //若 page 小於 1，則回傳 1



//商品種類 SQL 敘述
$sqlTotalCatogories = "SELECT count(1) FROM `item_tracking`";

//取得商品種類總筆數
$totalCatogories = $pdo->query($sqlTotalCatogories)->fetch(PDO::FETCH_NUM)[0];


$sql2 = "SELECT `item_tracking`.`created_at`,`item_tracking`.`id`,`item_tracking`.`userName`,`items`.`itemImg`,`items`.`itemPrice`,`items`.`itemQty`,`items`.`itemName`,`item_tracking`.`msg` FROM `item_tracking` INNER JOIN `items` ON `item_tracking`.`itemId`=`items`.`itemId` WHERE `items`.`itemName`
LIKE ?";

$arr2 = ['%'.$_POST['search'].'%']; // ?代值，%需加.

$stmt2 = $pdo->prepare($sql2);
$resule = $stmt2->execute($arr2);

if($stmt2->rowCount() > 0) {
    $arr2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    for($i = 0; $i < count($arr2); $i++){
        $strPk = "";
        if($strPk === '') 
            $strPk = $arr2[$i]['id']; 
        else 
            $strPk.= ",".$arr2[$i]['id'];
?>
    <tr>
        <td class="border"><?= $arr2[$i]['created_at'] ?></td>
        <td class="border"><?= $arr2[$i]['userName'] ?></td>
        <td class="border">
            <img class="itemImg" src="../images/items/<?= $arr2[$i]["itemImg"] ?>" 
                title="<?= $arr2[$i]["itemName"] ?>" 
                alt="<?= $arr2[$i]["itemName"] ?>">
        </td>
        <td class="border"><?= $arr2[$i]['itemPrice'] ?></td>
        <td class="border"><?= $arr2[$i]['itemQty'] ?></td>
        <td class="border">
            <textarea name="msg_<?= $arr2[$i]['id'] ?>" 
                cols="50" 
                rows="2" 
                data-item-tracking-id="<?= $arr2[$i]['id'] ?>"><?= $arr2[$i]['msg'] ?></textarea>
        </td>
    </tr>             
<?php
    }
}
?>
</tbody>
<!-- <tfoot>
    <tr>
        <td class="border" colspan="11">
        <?php for($i = 1; $i <= $totalPages; $i++){ ?>
            <a href="?page=<?=$i?>"><?= $i ?></a>
        <?php } ?>
        </td>
    </tr>
</tfoot> -->
</table>
<input type="hidden" name="pk" value="<?php echo $strPk; ?>">
<button  class="btn mano_check"type="submit" name="smb" value="更新"><i class="fa far fa-file"></i> 更新</button>
</form>
</body>
</html>