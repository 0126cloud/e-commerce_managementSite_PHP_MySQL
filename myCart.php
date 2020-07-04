<?php
session_start();
require_once('./db.inc.php');
require_once('./tpl/tpl-html-head.php');
require_once('./tpl/header.php');
require_once("./tpl/func-buildTree.php");
require_once("./tpl/func-getRecursiveCategoryIds.php");
?>
<form name="myForm" method="POST" action="./addOrder.php">

    <div class="container-fluid">
        <div class="row">
            <!-- 樹狀商品種類連結 -->
            <div class="col-md-2 col-sm-3"><?php buildTree($pdo, 0); ?></div>

            <!-- 商品項目清單 -->
            <div class="col-md-10 col-sm-9">
                <div class="row pl-3 pr-3">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col" class="border-0 bg-light">
                                        <div class="p-2 px-3 text-uppercase">商品名稱</div>
                                    </th>
                                    <th scope="col" class="border-0 bg-light">
                                        <div class="py-2 text-uppercase">價格</div>
                                    </th>
                                    <th scope="col" class="border-0 bg-light">
                                        <div class="py-2 text-uppercase">數量</div>
                                    </th>
                                    <th scope="col" class="border-0 bg-light">
                                        <div class="py-2 text-uppercase">小計</div>
                                    </th>
                                    <th scope="col" class="border-0 bg-light">
                                        <div class="py-2 text-uppercase">功能</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //放置結合當前資料庫資料的購物車資訊
                                $arr = [];

                                $total = 0;

                                if (isset($_SESSION["cart"]) && count($_SESSION["cart"]) > 0) {
                                    //重新排序索引
                                    $_SESSION["cart"] = array_values($_SESSION["cart"]);

                                    //SQL 敘述
                                    $sql = "SELECT `items`.`itemId`, `items`.`itemName`, `items`.`itemImg`, `items`.`itemPrice`, 
                                            `items`.`itemQty`, `items`.`itemCategoryId`, `items`.`created_at`, `items`.`updated_at`,
                                            `category`.`categoryId`, `category`.`categoryName`,`items`.`shippingId`
                                    FROM `items` INNER JOIN `category`
                                    ON `items`.`itemCategoryId` = `category`.`categoryId`
                                    WHERE `itemId` = ? ";

                                    // $arrParam2 = [
                                    //     (int)$_GET['itemId']
                                    // ];


                                    for ($i = 0; $i < count($_SESSION["cart"]); $i++) {
                                        $arrParam = [
                                            $_SESSION["cart"][$i]["itemId"]
                                        ];

                                        //查詢
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->execute($arrParam);


                                        //若商品項目個數大於 0，則列出商品
                                        if ($stmt->rowCount() > 0) {
                                            $arrTmp = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
                                            $arrTmp['cartQty'] = $_SESSION["cart"][$i]["cartQty"];
                                            $arr[] = $arrTmp;
                                            // $stmt->execute($arrParam2);
                                        }
                                    }

                                    //物流計算開始
                                    $shipColdMoney = 0;
                                    $coldItemTotal = 0;
                                    $shipRoomMoney = 0;
                                    $roomItemTotal = 0;
                                    $shipMoney = 0;
                                    $shiptotalMoney = 0;

                                    //計算個別單獨運費
                                    for ($i = 0; $i < count($arr); $i++) {
                                        if ($arr[$i]["shippingId"] == 'S001') {
                                            $shipColdMoney += 150;
                                            $coldItemTotal += $arr[$i]["itemPrice"] * $arr[$i]["cartQty"];
                                        } elseif ($arr[$i]["shippingId"] == 'S002') {
                                            $shipRoomMoney += 100;
                                            $roomItemTotal += $arr[$i]["itemPrice"] * $arr[$i]["cartQty"];
                                        } else {
                                            $shipMoney += 0;
                                        };
                                    };
                                    //避免運費重複計算
                                    if ($shipColdMoney > 150) {
                                        $shipColdMoney = 150;
                                    };
                                    if ($shipRoomMoney > 100) {
                                        $shipRoomMoney = 100;
                                    };
                                    //設定滿額免運
                                    if ($coldItemTotal > 1199) {
                                        $shipColdMoney -= 150;
                                    };
                                    if ($roomItemTotal > 799) {
                                        $shipRoomMoney -= 100;
                                    };
                                    //計算運費總額
                                    $shiptotalMoney += $shipColdMoney;
                                    $shiptotalMoney += $shipRoomMoney;
                                    $shiptotalMoney += $shipMoney;
                                    //先把訂單總金額加上運費=(0+運費)
                                    $total +=  $shiptotalMoney;

                                    //物流計算結束
                                    //開始算訂單商品金額
                                    for ($i = 0; $i < count($arr); $i++) {
                                        //計算總額
                                        $total += $arr[$i]["itemPrice"] * $arr[$i]["cartQty"];
                                ?>
                                        <tr>
                                            <th scope="row" class="border-0">
                                                <div class="p-2">
                                                    <img src="./images/items/<?php echo $arr[$i]["itemImg"] ?>" alt="" width="70" class="img-fluid rounded shadow-sm">
                                                    <div class="ml-3 d-inline-block align-middle">
                                                        <h5 class="mb-0"><a href="./itemDetail.php?itemId=<?php echo $arr[$i]['itemId']; ?>" class="text-dark d-inline-block align-middle"><?php echo $arr[$i]["itemName"] ?></a></h5>
                                                        <span class="text-muted font-weight-normal font-italic d-block">Category: <?php echo $arr[$i]["categoryName"] ?></span>
                                                    </div>
                                                </div>
                                            </th>
                                            <td class="border-0 align-middle">
                                                <p>$<?php echo $arr[$i]["itemPrice"] ?></p>
                                            </td>
                                            <td class="border-0 align-middle">
                                                <div class="d-flex">
                                                    <p style="cursor: pointer" id="decrease" data-item-id="<?php echo $arr[$i]['itemId'] ?>">&#10094;</p>
                                                    <p style="padding: 0 10px" id="cartQty<?= $arr[$i]["itemId"] ?>"><?php echo $arr[$i]["cartQty"] ?></p>
                                                    <p style="cursor: pointer" id="increase" data-item-id="<?php echo $arr[$i]['itemId'] ?>">&#10095;</p>
                                                </div>
                                                <!-- <input type="text" class="form-control" name="cartQty[]" value="<?php echo $arr[$i]["cartQty"] ?>" maxlength="3"> -->
                                            </td>
                                            <td class="border-0 align-middle">
                                                <p id="subtotal<?= $arr[$i]["itemId"] ?>"><?php echo ($arr[$i]["itemPrice"] * $arr[$i]["cartQty"]) ?></p>

                                                <!-- <input type="text" class="form-control" name="subtotal[]" value="<?php echo ($arr[$i]["itemPrice"] * $arr[$i]["cartQty"]) ?>" maxlength="10"> -->
                                            </td>
                                            <td class="border-0 align-middle"><a href="./deleteCart.php?idx=<?php echo $i ?>" class="text-dark">刪除</a></td>
                                        </tr>
                                        <input type="hidden" name="itemId[]" value="<?php echo $arr[$i]["itemId"] ?>">
                                        <input type="hidden" name="courseId[]" value="">
                                        <input type="hidden" name="itemPrice[]" value="<?php echo $arr[$i]["itemPrice"] ?>">
                                        
                                        <!-- 傳輸資料新增訂單 -->
                                        <input type="hidden" name="cartQty[]" value="<?php echo $arr[$i]["cartQty"] ?>">
                                        <input type="hidden" name="subtotal[]" value="<?php echo ($arr[$i]["itemPrice"] * $arr[$i]["cartQty"]) ?>">                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if (isset($_SESSION["cart"]) && count($_SESSION["cart"]) > 0) { ?>
                    <div class="row d-flex justify-content-start pl-3 pr-3 pb-3">
                        <?php
                        $sqlPaymentType = "SELECT `paymentId`, `paymentMethod`, `paymentTypeImg`
                                    FROM `payment`
                                    ORDER BY `paymentId` ASC";
                        $stmtPaymentType = $pdo->prepare($sqlPaymentType);
                        $stmtPaymentType->execute();
                        if ($stmtPaymentType->rowCount() > 0) {
                            $arrPaymentType = $stmtPaymentType->fetchAll(PDO::FETCH_ASSOC);
                            for ($j = 0; $j < count($arrPaymentType); $j++) {
                        ?>
                                <div class="col-md-2">
                                    <input type="radio" name="paymentId" id="paymentId" value="<?php echo $arrPaymentType[$j]['paymentId'] ?>">
                                    <?php echo $arrPaymentType[$j]['paymentMethod'] ?>
                                    <img class="payment_type_icon" src="./images/payment_types/<?php echo $arrPaymentType[$j]['paymentTypeImg'] ?>">
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>


                    <div class="row d-flex justify-content-end pl-3 pr-3 pb-3">
                        <h5>運費加總 <mark id="shipTotal"><?php echo $shiptotalMoney; ?></mark>
                        <input type="hidden" name="shiptotalMoney" value="<?php echo $shiptotalMoney ?>">
                        <input id="total" type="hidden" name="total" value="<?php echo $total ?>">
                         </h5>
                    </div>

                    <div class="row d-flex justify-content-end pl-3 pr-3 pb-3">
                        <h3>總消費金額: <mark id="total"><?php echo $total ?></mark></h3>
                    </div>
                    <div class="row d-flex justify-content-end pl-3 pr-3 pb-3">
                        <p>運費說明：常溫商品未滿800，運費100。低溫商品未滿1200，運費150。</p>
                    </div>
                    <div class="row d-flex justify-content-end pl-3 pr-3 pb-3">
                        <input class="btn btn-primary btn-lg" type="submit" name="smb" value="送出">
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>

</form>

<?php
require_once('./tpl/footer.php');
require_once('./tpl/tpl-html-foot.php');
?>