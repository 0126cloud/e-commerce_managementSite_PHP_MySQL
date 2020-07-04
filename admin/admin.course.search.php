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
        width: 200px;
        
    }
    .desc{
        color:gray;
    }

    </style>
<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線
error_reporting(0);



$search = $_GET["search"];
$searchbutton = $_GET["button"];

?>

</head>
<body>
<?php require_once('./templates/title.php'); ?>
<hr />

<div class="search-bar">
<h3>課程列表</h3>
<br>
<form action="admin.course.search.php" method="GET" entype= "multipart/form-data">
<div class="option">
    <div class="margin">關鍵字搜尋:</div>
    <input class="margin" name="search" type="text" placeholder="抹の找課程">
    <!-- <div class="margin">系列推薦:</div> 
    <select class="margin" name="search">
    <option value="">請選擇</option>
    　<option value="抹茶">達人推薦-抹の全系列</option>
    　<option value="防疫">病毒OUT! 防疫限定</option>
    　<option value="VIP">VIP手摘會員專屬</option>
    　<option value="手作">療癒系の手作課程</option>
    </select> -->
    
<input class="btn mano_edit fas fa-edit margin" type="submit" value="Search">
</div>
</form>

<br>
<a href="./admin.course.search.php">返回全部課程</a>
<br>
</div>
<br>


<form name="myForm" entype= "multipart/form-data" method="POST" action="./delete.course.php">
<table class="border table table-striped table-hover">
        <thead>
            <tr>
                <th class="border">代碼</th>
                <th class="border">課程名稱</th>
                <th class="border">課程照片路徑</th>
                <th class="border">課程價格</th>
                <th class="border">上課時間</th>
                <th class="border">地點</th>
                <th class="border">人數上限</th>
                
                <th class="border">功能</th>
            </tr>
        </thead>
        <tbody>

<?php
//先算頁數 WHERE條件  QUOTE加引號的方法
$searchname ='%'.$_GET['search'].'%';

$sqlTotal = "SELECT count(1) FROM `course` WHERE `courseName` OR `courseDesc`
LIKE ". $pdo->quote($searchname); //SQL 敘述
// var_dump($sqlTotal);

$total = $pdo->query($sqlTotal)->fetch(PDO::FETCH_NUM)[0]; //取得總筆數
$numPerPage = 5; //每頁幾筆
$totalPages = ceil($total/$numPerPage); // 總頁數
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; //目前第幾頁
$page = $page < 1 ? 1 : $page; //若 page 小於 1，則回傳 1

//LIMIT ?,?  從第幾筆 開始取幾筆
$sql2 = "SELECT * FROM `course` WHERE `courseName` 
LIKE ? 
OR `courseDesc` LIKE ?
LIMIT ?, ?";

//設定繫結值


$arr2 = 
['%'.$_GET['search'].'%','%'.$_GET['search'].'%',($page - 1) * $numPerPage, $numPerPage]; // ?代值，%需加.  //依序帶入3個值 以 ,分隔

$stmt2 = $pdo->prepare($sql2);
$resule = $stmt2->execute($arr2);



//商品種類 SQL 敘述
$sqlTotalCatogories = "SELECT count(1) FROM `category`";

//取得商品種類總筆數
$totalCatogories = $pdo->query($sqlTotalCatogories)->fetch(PDO::FETCH_NUM)[0];



if($stmt2->rowCount() > 0) {
    
$arr2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
for($i = 0; $i < count($arr2); $i++) {
    ?>
        <tr>
                <td class="">
                    <input type="checkbox" name="chk[]" value="<?php echo $arr2[$i]['courseId']; ?>" />
                    <br><br>
                    <a href="./edit.course.php?courseId=<?php echo $arr2[$i]['courseId']; ?>"><div class="num">
                    <?php echo 'CS'.str_pad($arr2[$i]['courseId'],3,"0",STR_PAD_LEFT);?></div></a>
                </td>
                <td class="shrink"><?php echo $arr2[$i]['courseName']; ?>
                <br><br>
                <div style="width: 200px;" class="desc text-wrap"><?php echo $arr2[$i]['courseDesc']; ?></div>
                </td>
                <td class="shrink"><img class="courseImg" src="../images/course/<?php echo $arr2[$i]['courseImg']; ?>" /></td>
                <td class="shrink"><?php echo '$'.$arr2[$i]['coursePrice']; ?></td>
                <td class="shrink"><?php echo $arr2[$i]['coursePeriod']; ?></td>
                <td class="shrink"><?php echo $arr2[$i]['courseLocation']; ?></td>
                <td class="shrink"><?php echo $arr2[$i]['courseQty'].'人'; ?></td>
               
            <td class="shrink" style="width:130px;" >
                <a class="btn mano_edit fas fa-edit" href="./edit.course.php?courseId=<?php echo $arr2[$i]['courseId']; ?>">編輯</a> 
                    <!-- <a href="./multipleImages.php?itemId=<?php echo $arr[$i]['courseId']; ?>">多圖設定</a> |  -->
                    <!-- <a href="./comments.php?courseId=<?php echo $arr[$i]['courseId']; ?>">回覆評論</a> -->
             </td>
        </tr>
    <?php
        }
    } else {
    ?>
        <tr>
            <td class="border" colspan="9">沒有資料</td>
        </tr>
    <?php
    }
    ?>
    </tbody>
    <tfoot>
            <tr>
                <td class="border" colspan="11">
                <?php for($i = 1; $i <= $totalPages; $i++){ ?>
                    <a href="?search=<?php echo $search ?>&button=<?php echo $searchbutton?>&page=<?=$i?>"><?= $i ?></a>
                <?php } ?>
            
                </td>
            </tr>
            
            <tr>
            
              <td class="border" colspan="9"><button class=" btn mano_delete fas fa-trash-alt" style="width:100px;height:40px;" type="submit" name="smb" value="刪除">刪除</button></td>
            </tr>
          
            
        </tfoot>
</table>

<?php 
//若有建立商品種類，則顯示商品清單
if($totalCatogories > 0) {
?>

<?php 
} else { 
    //引入尚未建立商品種類的文字描述
    require_once('./templates/noCategory.php');
}?>
</body>
</html>