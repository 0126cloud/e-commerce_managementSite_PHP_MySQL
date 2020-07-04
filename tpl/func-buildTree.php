<div class="treeview1">
<?php
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
            echo "<a style='color:#000;' href='./itemList.php?categoryId={$arr[$i]['categoryId']}'>{$arr[$i]['categoryName']}</a>";
            buildTree($pdo, $arr[$i]['categoryId']);
            echo "</li>";
        }
        echo "</ul>";
    }
}
?>
</div>