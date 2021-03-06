<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

$sqlTotal = "SELECT count(1) FROM `orders`"; //SQL 敘述
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
    img.payment_type_icon{
        width: 50px;
    }
    </style>
</head>
<body>
<?php require_once('./templates/title.php'); ?>
<hr />

<div class="search-bar">
<h3>訂單列表</h3>
<form action="searchOrders.php" method="POST" entype= "multipart/form-data">
<table class="search table table-stripe table-hover">
    <tr>
    <td>
    <label for="searchOrders">訂單資料</label>
    <select name="searchOrders" id="searchOrders">
        <option value="orderNumber">訂單編號</option>
        <option value="ship_name">用戶名稱</option>
        <option value="itemName">商品名稱</option>
        <option value="courseName">課程名稱</option>
        <option value="paymentMethod">付款方式</option>
        <option value="paymentStatus">付款狀態</option>
    </select>
    <input name="search" type="text" placeholder="訂單關鍵字搜尋" autocomplete="off">
<input type="submit" value="搜尋">
    </td>
    </tr>
</table>
</form>
</div>
<br>
<form name="myForm" method="POST" action="./deleteOrder.php">
    <table class="border table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col" class="border">
                    <div class="p-2 px-3 text-uppercase">訂單編號</div>
                </th>
                <th scope="col" class="border">
                    <div class="p-2 px-3 text-uppercase">用戶名稱</div>
                </th>
                <th scope="col" class="border">
                    <div class="py-2 text-uppercase">付款方式</div>
                </th>
                <th scope="col" class="border">
                    <div class="py-2 text-uppercase">商品名稱</div>
                </th>
                <th scope="col" class="border">
                    <div class="py-2 text-uppercase">單價</div>
                </th>
                <th scope="col" class="border">
                    <div class="py-2 text-uppercase">數量</div>
                </th>
                <th scope="col" class="border">
                    <div class="py-2 text-uppercase">總金額</div>
                </th>
                <th scope="col" class="border">
                    <div class="py-2 text-uppercase">付款狀態</div>
                </th>


                <th scope="col" class="border">
                    <div class="py-2 text-uppercase">功能</div>
                </th>


            </tr>
        </thead>
        <tbody>
        <?php
        $sqlOrder = "SELECT `orders`.`orderId`,`orders`.`orderNumber`,`orders`.`ship_name`,`orders`.`totalPrice`,`orders`.`paymentStatus`,`orders`.`created_at`,`orders`.`updated_at`, `orders`.`paymentMethod`
                    FROM `orders`
                    ORDER BY `orders`.`orderNumber` DESC
                    LIMIT ?, ?";

        //設定繫結值
        $arrParam = [($page - 1) * $numPerPage, $numPerPage];
        
        $stmtOrder = $pdo->prepare($sqlOrder);
        $stmtOrder->execute($arrParam);


        if($stmtOrder->rowCount() > 0){
            $arrOrders = $stmtOrder->fetchAll(PDO::FETCH_ASSOC);
            for($i = 0; $i < count($arrOrders); $i++) {
        ?>
            <tr>
                <th scope="row" class="border"><?php echo $arrOrders[$i]["orderNumber"] ?></th>
                <td class="border"><?php echo $arrOrders[$i]["ship_name"] ?></td>
                <td class="border"><?php echo $arrOrders[$i]["paymentMethod"] ?></td>
                <td class="border">
                <?php
                $sqlItemList = "SELECT `order_lists`.`checkPrice`,`order_lists`.`checkQuantity`,`order_lists`.`checkSubtotal`,
                                        `order_lists`.`itemName`,`order_lists`.`courseName`
                                FROM `order_lists` 
                                WHERE `order_lists`.`orderId` = ? 
                                ORDER BY `order_lists`.`orderListId` ASC";
                $stmtItemList = $pdo->prepare($sqlItemList);
                $arrParamItemList = [
                    $arrOrders[$i]["orderId"]
                ];
                $stmtItemList->execute($arrParamItemList);

                if($stmtItemList->rowCount() > 0) {
                    $arrItemList = $stmtItemList->fetchAll(PDO::FETCH_ASSOC);
                    for($j = 0; $j < count($arrItemList); $j++) {
                        if($arrItemList[$j]["itemName"]) {
                ?>
                    <p><?php echo $arrItemList[$j]["itemName"] ?></p>

                    <?php
                    }  ?>

                    <p><?php echo $arrItemList[$j]["courseName"] ?></p>
                <?php
                    }
                }
                ?>
                </td>

                <td class="border">
                    <?php
                        for($j = 0; $j < count($arrItemList); $j++) {
                    ?>
                        <p><?php echo $arrItemList[$j]["checkPrice"] ?> $NTD</p>
                    <?php
                    }
                    ?>
                </td>

                <td class="border">
                    <?php
                        for($j = 0; $j < count($arrItemList); $j++) {
                    ?>
                        <p><?php echo $arrItemList[$j]["checkQuantity"] ?> 件</p>
                    <?php
                    }
                    ?>
                </td>

                <!-- <td class="border">
                    <?php
                        for($j = 0; $j < count($arrItemList); $j++) {
                    ?>
                        <p><?php echo $arrItemList[$j]["checkSubtotal"] ?> $NTD</p>
                    <?php
                    }
                    ?>
                </td> -->
                <td class="border"><?php echo $arrOrders[$i]["totalPrice"] ?> $NTD</td>
                <td class="border"><?php echo $arrOrders[$i]["paymentStatus"] ?> </td>
                <td class="border">
                    <a class='btn mano_edit fas fa-edit' href="./editOrder.php?orderId=<?php echo $arrOrders[$i]["orderId"] ?>" class="text-dark">編輯</a>
                    <a class='btn mano_delete fas fa-trash-alt' href="./deleteOrder.php?orderId=<?php echo $arrOrders[$i]["orderId"] ?>" class="text-dark">刪除</a>
                </td>
            </tr>
        <?php
            }
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="border" colspan="9">
                <?php for($i = 1; $i <= $totalPages; $i++){ ?>
                    <a href="?page=<?=$i?>"><?= $i ?></a>
                <?php } ?>
                </td>
            </tr>            
        </tfoot>

    </table>
</div>

</form>

</body>
</html>