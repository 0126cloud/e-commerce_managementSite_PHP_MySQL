<?php
//引入判斷是否登入機制
// require_once('./checkSession.php');

//引用資料庫連線
require_once('../db.inc.php');

//SQL 敘述: 取得 students 資料表總筆數
$sqlTotal = "SELECT count(1) FROM `member`";

//取得總筆數
$total = $pdo->query($sqlTotal)->fetch(PDO::FETCH_NUM)[0];

//每頁幾筆
$numPerPage = 5;

// 總頁數
$totalPages = ceil($total/$numPerPage); 

//目前第幾頁
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

//若 page 小於 1，則回傳 1
$page = $page < 1 ? 1 : $page;
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
    .w100px {
        width: 100px;
    }
    </style>
</head>
<body>
<?php require_once('./templates/title.php'); ?>
<hr />

<form name="myForm" method="POST" action="member_deleteIds.php">
    <table class="border search table table-stripe table-hover">
        <thead>
            <tr>
                <th class="border">選擇</th>
                <th class="border">會員編號</th>
                <th class="border">姓名</th>
                <th class="border">會員等級</th>
                <th class="border">帳單地址</th>
                <th class="border">帳單城市</th>
                <th class="border">帳單郵遞區號</th>
                <th class="border">連絡電話</th>
                <th class="border">電子信箱</th>
                <th class="border">付款方式</th>
                <th class="border">運送地址</th>
                <th class="border">會員註冊時間</th>
                <th class="border">會員大頭貼</th>
                <th class="border">功能</th>
            </tr>
        </thead>
        <tbody>
        <?php
        //SQL 敘述
        $sql = "SELECT `id`, `memberId`, `memberName`, `class`, `paymentAddress`, `paymentCity`, `paymentZip`, `phone`, `email`, `paymentChoice`, `shipAddress`, `rollDate`, `memberImg`
                FROM `member` 
                ORDER BY `id` ASC 
                LIMIT ?, ? ";

        //設定繫結值
        $arrParam = [($page - 1) * $numPerPage, $numPerPage];

        //查詢分頁後的學生資料
        $stmt = $pdo->prepare($sql);
        $stmt->execute($arrParam);

        //資料數量大於 0，則列出所有資料
        if($stmt->rowCount() > 0) {
            $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            for($i = 0; $i < count($arr); $i++) {
        ?>
            <tr>
                <td class="border">
                    <input type="checkbox" name="chk[]" value="<?php echo $arr[$i]['id']; ?>" />
                </td>
                <td class="border"><?php echo $arr[$i]['memberId']; ?></td>
                <td class="border"><?php echo $arr[$i]['memberName']; ?></td>
                <td class="border"><?php echo $arr[$i]['class']; ?></td>
                <td class="border"><?php echo $arr[$i]['paymentAddress']; ?></td>
                <td class="border"><?php echo $arr[$i]['paymentCity']; ?></td>
                <td class="border"><?php echo nl2br($arr[$i]['paymentZip']); ?></td>
                <td class="border"><?php echo $arr[$i]['phone']; ?></td>
                <td class="border"><?php echo $arr[$i]['email']; ?></td>
                <td class="border"><?php echo $arr[$i]['paymentChoice']; ?></td>
                <td class="border"><?php echo $arr[$i]['shipAddress']; ?></td>
                <td class="border"><?php echo $arr[$i]['rollDate']; ?></td>
                <td class="border">
                <?php if($arr[$i]['memberImg'] !== NULL) { ?>
                    <img class="w100px" src="./files/<?php echo $arr[$i]['memberImg']; ?>">
                <?php } ?>
                </td>
                <td class="border">
                    <a class="btn mano_edit fas fa-edit" href="./member_edit.php?editId=<?php echo $arr[$i]['id']; ?>">編輯</a>
                    <a class="btn mano_delete fas fa-trash-alt" href="./member_delete.php?deleteId=<?php echo $arr[$i]['id']; ?>">刪除</a>
                </td>
            </tr>
        <?php
            }
        } else {
        ?>
            <tr>
                <td class="border" colspan="15">沒有資料</td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="border" colspan="15">
                <?php for($i = 1; $i <= $totalPages; $i++){ ?>
                    <a href="?page=<?= $i ?>"><?= $i ?></a>
                <?php } ?>
                </td>
            </tr>
        </tfoot>
    </table>
    <input class="btn mano_delete fas fa-trash-alt" type="submit" name="smb" value="刪除">
</form>

</body>
</html>