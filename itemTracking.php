<?php 
session_start();
require_once('./db.inc.php');
require_once('./tpl/tpl-html-head.php'); 
require_once('./tpl/header.php');
require_once("./tpl/func-buildTree.php");
require_once("./tpl/func-getRecursiveCategoryIds.php"); 

$sqlTotal = "SELECT count(1) FROM `item_tracking` WHERE `userName`= ".$pdo->quote($_SESSION['username']); //SQL 敘述
// var_dump($sqlTotal);
$total = $pdo->query($sqlTotal)->fetch(PDO::FETCH_NUM)[0]; //取得總筆數
$numPerPage = 5; //每頁幾筆
$totalPages = ceil($total/$numPerPage); // 總頁數
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; //目前第幾頁
$page = $page < 1 ? 1 : $page; //若 page 小於 1，則回傳 1

?>

<form name="myForm" entype= "multipart/form-data" method="POST" action="deleteItemTracking.php">
    <div class="container-fluid">
        <div class="row">
            <!-- 樹狀商品種類連結 -->
            <div class="col-md-3"><?php buildTree($pdo, 0); ?></div>

            <!-- 商品追蹤清單 -->
            <div class="col-md-9">
                <div class="container-fluid">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col" class="border-0 bg-light">
                                            <div class="py-2 text-uppercase">勾選</div>
                                        </th>
                                        <th scope="col" class="border-0 bg-light">
                                            <div class="p-2 px-3 text-uppercase">加入追蹤日期</div>
                                        </th>
                                        <th scope="col" class="border-0 bg-light">
                                            <div class="py-2 text-uppercase">圖片</div>
                                        </th>
                                        <th scope="col" class="border-0 bg-light">
                                            <div class="py-2 text-uppercase">價格</div>
                                        </th>
                                        <th scope="col" class="border-0 bg-light">
                                            <div class="py-2 text-uppercase">狀態</div>
                                        </th>
                                        <th scope="col" class="border-0 bg-light">
                                            <div class="py-2 text-uppercase">最新資訊</div>
                                        </th>
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
                    WHERE `username` = ? 
                    LIMIT ?, ? ";

            $arrParam = [
                $_SESSION['username'],
                ($page - 1) * $numPerPage,
                $numPerPage
            ];

            //查詢 SQL
            $stmt = $pdo->prepare($sql);
            $stmt->execute($arrParam);

            //若商品項目個數大於 0，則列出商品
            if($stmt->rowCount() > 0) {
                $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
                for($i = 0; $i < count($arr); $i++){ 
            ?>
                <tr>
                    <td class="border-0 align-middle"><input type="checkbox" name="chk[]" value="<?php echo $arr[$i]['itemId']; ?>" /></td>
                    <td class="border-0 align-middle"><?= $arr[$i]['created_at'] ?></td>
                    <td class="border-0 align-middle">
                        <img src="./images/items/<?= $arr[$i]["itemImg"] ?>" alt="" class="item-tracking-preview img-fluid rounded shadow-sm">
                    </td>
                    <td class="border-0 align-middle"><?= $arr[$i]['itemPrice'] ?></td>
                    <td class="border-0 align-middle">
                    <?php if($arr[$i]['itemQty'] > 0){ ?>
                        <button type="button" class="btn btn-primary" id="btn_addCartForItemTracking" data-item-id="<?= $arr[$i]['itemId'] ?>"><a class="btn btn-primary" href="./deleteTracking.php?deleteItemTrackingId=<?= $arr[$i]['itemTrackingId'] ?>">加入購物車</a></button>
                    <?php } else { echo "完售"; } ?>
                    </td>
                    <td class="border-0 align-middle"><?= nl2br($arr[$i]['msg']) ?></td>
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
                                    <tr>
                                        <td class="border-0 align-middle"><input link type="submit" name="smb" value="刪除"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
require_once('./tpl/footer.php');
require_once('./tpl/tpl-html-foot.php'); 
?>