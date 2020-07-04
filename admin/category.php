<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

//建立種類列表
function buildTree($pdo, $parentId = 0){
    $sql = "SELECT `categoryId`, `categoryName`, `categoryParentId`
            FROM `category` 
            WHERE `categoryParentId` = ?";
    $stmt = $pdo->prepare($sql);
    $arrParam = [$parentId];
    $stmt->execute($arrParam);
    if($stmt->rowCount() > 0) {
        echo "<ul class='list-group'>";
        $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        for($i = 0; $i < count($arr); $i++) {
            echo "<li class='list-group-item node-treeview1'>";
            echo "<input type='radio' name='categoryId' value='".$arr[$i]['categoryId']."' />";
            echo $arr[$i]['categoryName'];
            echo " | <a class='btn mano_edit fas fa-edit' href='./editCategory.php?editCategoryId=".$arr[$i]['categoryId']."'>編輯</a>";
            echo " | <a class='btn mano_delete fas fa-trash-alt' href='./deleteCategory.php?deleteCategoryId=".$arr[$i]['categoryId']."'>刪除</a>";
            buildTree($pdo, $arr[$i]['categoryId']);
            echo "</li>";
        }
        echo "</ul>";
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
    </style>
</head>
<body>
<?php require_once('./templates/title.php'); ?>
<hr />
<h3>編輯類別</h3>
<form name="myForm" method="POST" enctype= "multipart/form-data" action="./insertCategory.php">

<?php buildTree($pdo, 0); ?>

<!-- <table class="border table table-striped table-hover"> -->
<table class="border table-striped table-hover">

    <thead>
        <tr>
            <th class="border">類別名稱</th>
            <th class="border">類別開關</th>
            <th class="border">類別類型</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="border">
                <input type="text" name="categoryName" value="" maxlength="100" />
            </td>
            <td class="border">
            <select class="form-control" name="active">
                <option value="open">open</option>
                <option value="closed">closed</option>
            </select>
            </td>
            <td class="border">
            <select class="form-control" name="categoryType">
                <option value="社群">社群</option>
                <option value="商城">商城</option>
            </select>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td class="border" colspan="6"><input type="submit" name="smb" value="新增"></td>
        </tr>
    </tfoot>
</table>

</form>
</body>
</html>