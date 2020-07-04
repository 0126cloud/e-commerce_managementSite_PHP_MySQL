<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線

//建立種類列表
function buildTree($pdo, $parentId = 0){
    $sql = "SELECT `categoryId`, `categoryName`, `categoryParentId`, `categoryType`
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
            //buildTree($pdo, $arr[0]['categoryId']); 
        
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
        padding:5px;
    }
    img.courseImg {
        width: 250px;
    }
    input{
        width:200px;
    }
    </style>
</head>
<body>
<?php require_once('./templates/title.php'); ?>
<hr />
<h3>課程列表</h3>
<form name="myForm" enctype="multipart/form-data" method="POST" action="update.course.php">
    <table class="border">
        <thead>
            <tr>
                <th class="border">課程名稱</th>
                <th class="border">內容描述</th>
                <th class="border">課程圖片</th>
                <th class="border">課程費用</th>
                <th class="border">人數上限</th>
                <th class="border">課程類別</th>
                <th class="border">新增時間</th>
                
            </tr>
        </thead>
        <tbody>
        <?php
        //SQL 敘述
        $sql = "SELECT `course`.`courseId`, `course`.`courseName`, `course`.`courseDesc`, `course`.`courseImg`, `course`.`coursePrice`, 
                        `course`.`courseQty`, `course`.`courseCategoryId`, `course`.`created_at`, `course`.`updated_at`,
                        `category`.`categoryId`, `category`.`categoryName`
                FROM `course` INNER JOIN `category`
                ON `course`.`courseCategoryId` = `category`.`categoryId`
                WHERE `courseId` = ? ";

        $arrParam = [
            (int)$_GET['courseId']
        ];

        //查詢
        $stmt = $pdo->prepare($sql);
        $stmt->execute($arrParam);

        //資料數量大於 0，則列出相關資料
        if($stmt->rowCount() > 0) {
            $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
            <tr>
                <td class="border">
                    <input type="text" name="courseName" value="<?php echo $arr[0]['courseName']; ?>" maxlength="100" />
                </td>
                <td class="border">
                    <input type="text" name="courseDesc" value="<?php echo $arr[0]['courseDesc']; ?>" maxlength="100" />
                </td>
                <td class="border">
                    <img class="courseImg" src="../images/course/<?php echo $arr[0]['courseImg']; ?>" /><br />
                    <input type="file" name="courseImg" value="" />
                </td>
                <td class="border">
                    <input type="text" name="coursePrice" value="<?php echo $arr[0]['coursePrice']; ?>" maxlength="11" />
                </td>
                <td class="border">
                <input class="courseQty" type="number" name="courseQty" min="0" max="50" value="<?php echo $arr[0]['courseQty']; ?>" />

                </td>
                <td class="border">
                    <select name="courseCategoryId">
                    <option value="<?php echo $arr[0]['categoryId']; ?>"><?php echo $arr[0]['categoryName']; ?></option>
                    <?php buildTree($pdo, 0); ?>
                    </select>
                </td>
                <td class="border"><?php echo $arr[0]['created_at']; ?></td>

            </tr>
        <?php
        } else {
        ?>
            <tr>
                <td colspan="7">沒有資料</td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="border" colspan="7"><button class="btn mano_check far fa-file" type="submit" name="smb" value="">更新</td>
            </tr>
        </tfoo>
    </table>
    <input type="hidden" name="courseId" value="<?php echo (int)$_GET['courseId']; ?>">
</form>
</body>
</html>