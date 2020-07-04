<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線


$sqlTotal = "SELECT count(1) FROM `items`"; //SQL 敘述
$total = $pdo->query($sqlTotal)->fetch(PDO::FETCH_NUM)[0]; //取得總筆數
$numPerPage = 6; //每頁幾筆
$totalPages = ceil($total/$numPerPage); // 總頁數
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; //目前第幾頁
$page = $page < 1 ? 1 : $page; //若 page 小於 1，則回傳 1



//商品種類 SQL 敘述
$sqlTotalCatogories = "SELECT count(1) FROM `category`";

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
        width: 100px;
        height: 100px;
    }
    .searchBtn {
        border: 2px solid green;
        margin: 0 0 10px 10px;
    }
    </style>
</head>
<body>
<?php require_once('./templates/title.php'); ?>
<hr />
<h3>商品列表</h3>

<a href="itemSearch.php" class="btn mano_edit far fa-file"><i class=""></i> 找茶</a>

<?php 
//若有建立商品種類，則顯示商品清單
if($totalCatogories > 0) {
?>
<form name="myForm" entype= "multipart/form-data" method="POST" action="delete.php">
    <table class="border table table-striped table-hover">
        <thead>
            <tr>
                <th class="border">勾選</th>
                <th class="border">商品名稱</th>
                <th class="border">商品圖片路徑</th>
                <th class="border">MSRP</th>
                <th class="border">商品價格</th>
                <th class="border">商品數量</th>
                <th class="border">商品種類</th>
                <th class="border">商品顏色</th>
                <th class="border">商品尺寸</th>
                <th class="border">物流代號</th>
                <th class="border">新增時間</th>
                <th class="border">更新時間</th>
                <th class="border">功能</th>
            </tr>
        </thead>
        <tbody>
        <?php
        //SQL 敘述
        $sql = "SELECT `items`.`itemId`, `items`.`itemName`, `items`.`itemImg`, `items`.`MSRP`, `items`.`itemPrice`, 
                        `items`.`itemQty`, `items`.`itemCategoryId`, `items`.`itemColor`,`items`.`Size`,`items`.`shippingId`, `items`.`created_at`, `items`.`updated_at`,
                        `category`.`categoryName`
                FROM `items` INNER JOIN `category`
                ON `items`.`itemCategoryId` = `category`.`categoryId`
                ORDER BY `items`.`itemId` ASC 
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
                    <input type="checkbox" name="chk[]" value="<?php echo $arr[$i]['itemId']; ?>" />
                </td>
                <td class="border"><?php echo $arr[$i]['itemName']; ?></td>
                <td class="border"><img class="itemImg" src="../images/items/<?php echo $arr[$i]['itemImg']; ?>" /></td>
                <td class="border"><?php echo $arr[$i]['MSRP']; ?></td>
                <td class="border"><?php echo $arr[$i]['itemPrice']; ?></td>
                <td class="border"><?php echo $arr[$i]['itemQty']; ?></td>
                <td class="border"><?php echo $arr[$i]['categoryName']; ?></td>
                <td class="border"><?php echo $arr[$i]['itemColor']; ?></td>
                <td class="border"><?php echo $arr[$i]['Size']; ?></td>
                <td class="border"><?php echo $arr[$i]['shippingId']; ?></td>
                <td class="border"><?php echo $arr[$i]['created_at']; ?></td>
                <td class="border"><?php echo $arr[$i]['updated_at']; ?></td>
                <td class="border">
                    <a class="btn mano_check far fa-file" href="./edit.php?itemId=<?php echo $arr[$i]['itemId']; ?>">商品編輯</a>
                    <!-- <a  class="btn mano_edit far fa-images" style="margin: 12px 2px;" href="./multipleImages.php?itemId=<?php //echo $arr[$i]['itemId']; ?>">多圖設定</a><br> -->
                    <a  class="btn mano_edit far fa-folder-open"href="#" onclick="window.open('./multipleCat.php?itemId=<?php echo $arr[$i]['itemId']; ?>', '多分類設定', config='height=900,width=450,left=1000');">多分類設定</a>
                    <!-- <a  class="btn mano_edit fas fa-edit"href="./comments.php?itemId=<?php //echo $arr[$i]['itemId']; ?>">回覆評論</a> -->
                </td>
            </tr>
        <?php
            }
        } else {
        ?>
            <tr>
                <td class="border" colspan="13">沒有資料</td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="border" colspan="13">
                <?php for($i = 1; $i <= $totalPages; $i++){ ?>
                    <a href="?page=<?=$i?>"><?= $i ?></a>
                <?php } ?>
                </td>
            </tr>
            
            <?php if($total > 0) { ?>
            <tr>
                <td class="border" colspan="13"><button class='btn mano_delete ' type="submit" name="smb" value="刪除"><i class="fas fa-trash-alt"></i>刪除</button></td>
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