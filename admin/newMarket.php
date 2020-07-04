<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

//建立種類列表
/*function buildTree($pdo, $parentId = 0){
    $sql = "SELECT `categoryId`, `categoryName`, `categoryParentId`
            FROM `categories` 
            WHERE `categoryParentId` = ?";
    $stmt = $pdo->prepare($sql);
    $arrParam = [$parentId];
    $stmt->execute($arrParam);
    if($stmt->rowCount() > 0) {
        $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        for($i = 0; $i < count($arr); $i++) {
            echo "<option value='".$arr[$i]['categoryId']."'>";
            echo $arr[$i]['categoryName'];
            echo "</option>";
            buildTree($pdo, $arr[$i]['categoryId']); 
        }
    }
}*/
?>
<!DOCTYPYE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>新增活動</title>
    <style>
    .border {
        border: 1px solid;
    }
    </style>
</head>
<body>
<?php require_once('./templates/title.php'); ?>
<hr />
<h3>新增行銷活動</h3>
<form name="myForm" enctype="multipart/form-data" method="POST" action="addMarket.php">
<table class="border table table-striped table-hover">
    <thead>
        <tr>
            <th class="border">行銷ID</th>
            <th class="border">行銷名稱</th>
            <th class="border">行銷時段</th>
            <th class="border">行銷允許</th>
            <th class="border">行銷折扣</th>
            <th class="border">會員編號</th>

        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="border">
                <input type="text" name="discountID" value="" maxlength="10" />
            </td>
            <td class="border">
                <input type="text" name="discountName" value="" maxlength="100" />
            </td>
            <td class="border">
                <input type="month" name="discountPeriod" value="2020-05" />
            </td>
            <td class="border">
                
                    <select name="discountAllowed" class="form-control">
                        <option value="open" selected>open</option>
                        <option value="close">close</option>
                     </select>
                
            </td>
            <td class="border">
            <textarea name="discountMethod"></textarea>
            </td>
            
            <td class="border">
                <input type="text" name="memberID" value="" maxlength="20" />
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td class="border" colspan="6"><input class="btn mano_check far fa-file" type="submit" name="smb" value="新增"></td>
        </tr>
    </tfoot>
</table>
</form>
</body>
</html>