<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

//建立種類列表
function buildTree($pdo, $parentId = 0){
    $sql = "SELECT `categoryId`, `categoryName`, `categoryParentId`
            FROM `category` 
            WHERE `categoryParentId` = ?";
    $stmt = $pdo->prepare($sql);
    $arrParam = [26];
    $stmt->execute($arrParam);
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
    input.coursePrice{
        width:100px;
    }
    input.coursedate {
        width:150px;
    }
    input.courseQty{
        width:80px;
    }
    input{
        width:200px;
    }
    text{
        font-size:smaller;
    }
  
    </style>
</head>
<body>


<?php require_once('./templates/title.php'); ?>
<hr />
<h3>新增課程</h3>
<form name="myForm" enctype="multipart/form-data" method="POST" action="add.course.php">

<table class="border table table-striped table-hover text">

    <thead>
        <tr>
            <th class="border">課程名稱</th>
            <th class="border">內容描述</th>
            <th class="border">課程照片路徑</th>
            <th class="border">課程費用</th>
            <th class="border">上課日期</th>
            <th class="border">地點</th>
            <th class="border">人數</th>
            <th class="border">課程類別</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="shrink">
                <input required type="text" name="courseName" value="" maxlength="100" />
            </td>
            <td class="shrink">
                <input type="text" name="courseDesc" value="" maxlength="200" />
            </td>
            <td class="shrink">
                <input type="file" name="courseImg" value="" />
            </td>
            <td class="shrink">
                <input class="coursePrice" type="text" name="coursePrice" value="" maxlength="11" />
            </td>
            <td class="shrink">
                <input class="coursedate" type="date" name="coursePeriod" value="" maxlength="11" />
            </td>

            <td class="shrink">
            <select name="courseLocation">
            <option value="TPE">台北</option>
            　<option value="NTP">新北</option>
            　<option value="ILA">宜蘭</option>
            　<option value="ZMI">苗栗</option>
                </select>
            </td>
            <td class="shrink">
                <input class="courseQty" type="number" name="courseQty" min="0" max="50" value="1" />
            </td>
            <td class="shrink">
                <select name="courseCategoryId">
                <?php buildTree($pdo, 0); ?>
                </select>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
        <!-- <td><a class="btn mano_edit fas fa-edit" href="./edit.course.php?courseId=<?php echo $arr[$i]['courseId']; ?>">新增</a> </td> -->
            <td class="border" colspan="8"><button class="btn mano_check fas fa-edit" type="submit" name="smb" value="" >新增</button></td>
        </tr>
    </tfoot>
</table>
</form>
</body>
</html>