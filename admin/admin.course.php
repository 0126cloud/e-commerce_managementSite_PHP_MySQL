<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線



$sqlTotal = "SELECT count(1) FROM `course`"; //SQL 敘述
$total = $pdo->query($sqlTotal)->fetch(PDO::FETCH_NUM)[0]; //取得總筆數
$numPerPage = 5; //每頁幾筆
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
    img.courseImg {
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
<body">
<?php require_once('./templates/title.php'); ?>
<hr />

<div class="search-bar">
<h3>課程列表</h3>
<form action="admin.course.search.php" method="POST" enctype= "multipart/form-data">
<input name="search" type="text" placeholder="抹の找課程">
<input class="btn mano_edit fas fa-edit" type="submit" value="Search">
</form>
</div>

<br>
<?php 
//若有建立商品種類，則顯示商品清單
if($totalCatogories > 0) {
?>
<form name="myForm" entype= "multipart/form-data" method="POST" action="./delete.course.php">
    <table class="border table table-striped table-hover">
        <thead>
            <tr>
                <th class="border">代碼</th>
                <th class="border">課程名稱</th>
                <th class="border">課程圖片</th>
                <th class="border" style="white-space:nowrap;">課程費用</th>
                <th class="border">時間</th>
                <th class="border" style="white-space:nowrap;">地點</th>
                <th class="border" style="white-space:nowrap;">人數上限</th>
                <th class="border">課程類別</th>
                
                <th class="border">功能</th>
            </tr>
        </thead>
        <tbody>
        <?php
        //SQL 敘述
        $sql = "SELECT `course`.`courseId`, `course`.`courseName`, `course`.`courseDesc`, `course`.`courseImg`, `course`.`coursePrice`, `course`.`coursePeriod`,`course`.`courseLocation`, 
                        `course`.`courseQty`, `course`.`courseCategoryId`, `course`.`created_at`, `course`.`updated_at`,
                        `category`.`categoryName`
                FROM `course` INNER JOIN `category`
                ON `course`.`courseCategoryId` = `category`.`categoryId`
                ORDER BY `course`.`courseId` ASC 
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
                <td class="">
                    <input type="checkbox" name="chk[]" value="<?php echo $arr[$i]['courseId']; ?>" />
                    <br><br>
                    <a href="./edit.course.php?courseId=<?php echo $arr[$i]['courseId']; ?>"><div class="num"><?php echo 'CS0'.$arr[$i]['courseId']; ?></div></a>
                </td>
                <td class="shrink"><?php echo $arr[$i]['courseName']; ?>
                <br><br>
                <div style="width: 200px;" class="desc text-wrap"><?php echo $arr[$i]['courseDesc']; ?></div>
                </td>
                <td class="shrink"><img class="courseImg" src="../images/course/<?php echo $arr[$i]['courseImg']; ?>" /></td>
                <td class="shrink"><?php echo '$'.$arr[$i]['coursePrice']; ?></td>
                <td class="shrink"><?php echo $arr[$i]['coursePeriod']; ?></td>
                <td class="shrink"><?php echo $arr[$i]['courseLocation']; ?></td>
                <td class="shrink"><?php echo $arr[$i]['courseQty'].'人'; ?></td>
                <td class="shrink"><?php echo $arr[$i]['categoryName']; ?></td>
                
                <td class="shrink" style="width:130px;" >
                <a class="btn mano_edit fas fa-edit" href="./edit.course.php?courseId=<?php echo $arr[$i]['courseId']; ?>">編輯</a> 
                    <!-- <a href="./multipleImages.php?itemId=<?php echo $arr[$i]['courseId']; ?>">多圖設定</a> |  -->
                    <!-- <a href="./comments.php?courseId=<?php echo $arr[$i]['courseId']; ?>">回覆評論</a> -->
                </td>
            </tr>
        <?php
            }
        } else {
        ?>
            <tr>
                <td class="border" colspan="11">沒有資料</td>
            </tr>
        <?php
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
            
            <?php if($total > 0) { ?>
            <tr>
            
              <td class="border" colspan="9"><button class=" btn mano_delete fas fa-trash-alt" style="width:100px;height:40px;" type="submit" name="smb" value="刪除">刪除</button></td>
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