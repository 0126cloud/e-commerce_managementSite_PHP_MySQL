<?php
session_start();
require_once('./db.inc.php');

//預設訊息 (錯誤先行)
$objResponse['success'] = false;
$objResponse['code'] = 400;
$objResponse['info'] = "加入購物車失敗";
$objResponse['cartItemNum'] = 0;

if( !isset($_POST['cartQty']) || !isset($_POST['itemId']) ){
    header("Refresh: 3; url=./itemList.php");
    $objResponse['info'] = "資料傳遞有誤";
    echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    exit();
}

//先前沒有建立購物車，就直接初始化 (建立)
if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$shipColdMoney = 0;
$coldItemTotal = 0;
$shipRoomMoney = 0;
$roomItemTotal = 0;
$shipMoney = 0;
$shiptotalMoney = 0;

$total = 0;

//SQL 敘述
$sql = "SELECT `items`.`itemId`, `items`.`itemName`, `items`.`itemImg`, `items`.`itemPrice`, 
            `items`.`itemQty`, `items`.`itemCategoryId`, `items`.`created_at`, `items`.`updated_at`,
            `category`.`categoryId`, `category`.`categoryName`,`items`.`shippingId`
        FROM `items` INNER JOIN `category`
        ON `items`.`itemCategoryId` = `category`.`categoryId`
        WHERE `itemId` = ? ";

$arrParam = [
    $_POST['itemId']
];

//查詢
$stmt = $pdo->prepare($sql);
$stmt->execute($arrParam);

//若商品項目個數大於 0，則列出商品
if($stmt->rowCount() > 0) {
    $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //將主要資料放到購物車中
    //搜尋購物車，如果該商品已存在，減少商品數量
    $idx = array_search($arr[0]["itemId"], array_column($_SESSION["cart"], 'itemId'));

    if ($idx !== False) {

        if ($_SESSION['cart'][$idx]["cartQty"] <= 1) {

            $_SESSION['cart'][$idx] = [
                "itemId"  => $arr[0]["itemId"],
                "cartQty" => $_SESSION['cart'][$idx]["cartQty"]
            ];

            header("Refresh: 3; url=./myCart.php");
            $objResponse['info'] = "商品數量無法再減少了哦";
            $objResponse['cartItemNum'] = count($_SESSION['cart']);
            $objResponse["cartQty"] = $_SESSION['cart'][$idx]["cartQty"];
            $objResponse["cartQtyid"] = $arr[0]["itemId"];
            $objResponse["subtotal"] = ($arr[0]["itemPrice"] * $_SESSION['cart'][$idx]["cartQty"]);


        
        } else {
            $_SESSION['cart'][$idx] = [
                "itemId"  => $arr[0]["itemId"],
                "cartQty" => $_SESSION['cart'][$idx]["cartQty"] -= $_POST["cartQty"]
            ];

            // header("Refresh: 3; url=./myCart.php");
            $objResponse['success'] = true;
            $objResponse['code'] = 200;
            $objResponse['info'] = "已減少商品";
            $objResponse['cartItemNum'] = count($_SESSION['cart']);
            $objResponse["cartQty"] = $_SESSION['cart'][$idx]["cartQty"];
            $objResponse["cartQtyid"] = $arr[0]["itemId"];
            $objResponse["subtotal"] = ($arr[0]["itemPrice"] * $_SESSION['cart'][$idx]["cartQty"]);
        
        }
    } else {
        $_SESSION['cart'][] = [
            "itemId"    => $arr[0]["itemId"],
            "cartQty"   => $_POST["cartQty"]
        ];

        //header("Refresh: 3; url=./myCart.php");
        $objResponse['success'] = true;
        $objResponse['code'] = 200;
        $objResponse['info'] = "已減少商品";
        $objResponse['cartItemNum'] = count($_SESSION['cart']);
        $objResponse["cartQty"] = $_SESSION['cart'][$idx]["cartQty"];
        $objResponse["cartQtyid"] = $arr[0]["itemId"];
        $objResponse["subtotal"] = ($arr[0]["itemPrice"] * $_SESSION['cart'][$idx]["cartQty"]);

    
    }


} 
else {
    header("Refresh: 3; url=./myCart.php");
    $objResponse['info'] = "查無商品項目";
    $objResponse['cartItemNum'] = count($_SESSION['cart']);
    echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
}

for ($i = 0; $i < count($_SESSION['cart']); $i++) {

    $arrParam2 = [
        $_SESSION["cart"][$i]["itemId"]
    ];

    //查詢
    $stmt = $pdo->prepare($sql);
    $stmt->execute($arrParam2);


    //若商品項目個數大於 0，則列出商品
    if ($stmt->rowCount() > 0) {
        $arrTmp = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
        $arr2[] = $arrTmp;
    }

    //計算總額
    $total += $arr2[$i]["itemPrice"] * $_SESSION["cart"][$i]["cartQty"];

}

for ($i = 0; $i < count($arr2); $i++) {
    if ($arr2[$i]["shippingId"] == 'S001') {
        $shipColdMoney += 150;
        $coldItemTotal += $arr2[$i]["itemPrice"] * $_SESSION["cart"][$i]["cartQty"];
    } elseif ($arr2[$i]["shippingId"] == 'S002') {
        $shipRoomMoney += 100;
        $roomItemTotal += $arr2[$i]["itemPrice"] * $_SESSION["cart"][$i]["cartQty"];
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



$objResponse["shipTotal"] = $shiptotalMoney;
$objResponse["total"] = $total;


echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);