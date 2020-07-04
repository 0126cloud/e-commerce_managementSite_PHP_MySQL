<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

//建立種類列表
// function buildTree($pdo, $parentId = 0){
    // function buildTree($pdo, $categoryType = 0){

    function buildTree($pdo){  
    $sql = "SELECT `categoryId`, `categoryName`, `categoryParentId`
            FROM `category` 
            WHERE `categoryParentId` = 1 OR `categoryParentId` = 3 OR `categoryParentId` = 8 OR `categoryParentId` = 9 OR `categoryParentId` = 5 OR `categoryParentId` = 16 OR `categoryParentId` = 18";

   
    // $arrParam = [$categoryType];
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        for($i = 0; $i < count($arr); $i++) {
            echo "<option value='".$arr[$i]['categoryId']."'>";
            echo $arr[$i]['categoryName'];
            echo "</option>";
            // buildTree($pdo, $arr[$i]['categoryId']); 
        }
    }
}
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
<h3>新增商品</h3>
<form name="myForm" enctype="multipart/form-data" method="POST" action="add.php">
<table class="border table table-striped table-hover">
    <thead>
        <tr>
            <th class="border">商品名稱</th>
            <th class="border">商品圖片路徑</th>
            <th class="border">MSRP</th>
            <th class="border">商品價格</th>
            <th class="border">商品數量</th>
            <th class="border">商品顏色</th>
            <th class="border">商品尺寸</th>
            <th class="border">物流編號</th>
            <th class="border">商品種類</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="border">
                <input type="text" name="itemName" value="" placeholder="抹茶相關商品 ex: 抹茶..." maxlength="100" />
            </td>
            <td class="border">
                <input type="file" name="itemImg" value="" />
            </td>
            <td class="border">
                <input type="text" name="MSRP" value="" placeholder="100" style="width: 50%;"/>
            </td>
            <td class="border">
                <input type="text" name="itemPrice" value="" placeholder="100" style="width: 50%;" maxlength="11" />
            </td>
            <td class="border">
                <input type="text" name="itemQty" value="" placeholder="20" style="width: 30%;" maxlength="3" />
            </td>
            <td class="border">
                <input type="text" name="itemColor" value="#" style="width: 60%;" maxlength="7" />
            </td>
            <td class="border">
                <input type="text" name="Size" value="" style="width: 30%;" maxlength="3" />
            </td>
            <td class="border">
                <input type="text" name="shippingId" value="S" style="width: 50%;" maxlength="5" />
            </td>
            <td class="border">
                <select name="itemCategoryId">
                <?php buildTree($pdo, 0); ?>
                </select>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td class="border" colspan="10"><input type="submit" name="smb" value="新增"></td>
        </tr>
    </tfoot>
</table>
</form>
</body>
</html>