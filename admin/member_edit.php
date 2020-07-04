<?php
//引入判斷是否登入機制
// require_once('./checkSession.php');

//引用資料庫連線
require_once('../db.inc.php');
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
<form name="myForm" method="POST" action="member_updateEdit.php" enctype="multipart/form-data">
    <table class="border">
        <tbody>
        <?php
        //SQL 敘述
        $sql = "SELECT `id`, `memberId`, `memberName`, `class`, `paymentAddress`, `paymentCity`, `paymentZip`, `phone`, `email`, `pwd`, `paymentChoice`, `shipAddress`, `rollDate`, `memberImg`
                FROM `member`  
                WHERE `id` = ?";

        //設定繫結值
        $arrParam = [(int)$_GET['editId']];

        //查詢
        $stmt = $pdo->prepare($sql);
        $stmt->execute($arrParam);
        $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($arr) > 0) {
        ?>
            <tr>
                <td class="border">會員編號</td>
                <td class="border">
                    <input type="text" name="memberId" value="<?php echo $arr[0]['memberId']; ?>" maxlength="9" />
                </td>
            </tr>
            <tr>
                <td class="border">姓名</td>
                <td class="border">
                    <input type="text" name="memberName" value="<?php echo $arr[0]['memberName']; ?>" maxlength="10" />
                </td>
            </tr>
            <tr>
                <td class="border">會員等級</td>
                <td class="border">
                    <select name="class">
                        <option value="<?php echo $arr[0]['class']; ?>" selected><?php echo $arr[0]['class']; ?></option>
                        <option value="手摘">手摘</option>
                        <option value="一番茶">一番茶</option>
                        <option value="二番茶">二番茶</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="border">帳單地址</td>
                <td class="border">
                    <input type="text" name="paymentAddress" value="<?php echo $arr[0]['paymentAddress']; ?>" maxlength="10" />
                </td>
            </tr>
            <tr>
                <td class="border">帳單城市</td>
                <td class="border">
                    <input type="text" name="paymentCity" value="<?php echo $arr[0]['paymentCity']; ?>" maxlength="10" />
                </td>
            </tr>
            <tr>
                <td class="border">帳單郵遞區號</td>
                <td class="border">
                    <textarea name="paymentZip"><?php echo $arr[0]['paymentZip']; ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="border">連絡電話</td>
                <td class="border">
                    <textarea name="phone"><?php echo $arr[0]['phone']; ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="border">電子信箱</td>
                <td class="border">
                    <textarea name="email"><?php echo $arr[0]['email']; ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="border">密碼</td>
                <td class="border">
                    <textarea name="pwd"><?php echo $arr[0]['pwd']; ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="border">付款方式</td>
                <td class="border">
                    <select name="paymentChoice">
                        <option value="<?php echo $arr[0]['paymentChoice']; ?>" selected><?php echo $arr[0]['paymentChoice']; ?></option>
                        <option value="visa">visa</option>
                        <option value="master">master</option>
                        <option value="jbc">jbc</option>
                        <option value="貨到付款">貨到付款</option>
                        <option value="第三方支付">第三方支付</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="border">運送地址</td>
                <td class="border">
                    <textarea name="shipAddress"><?php echo $arr[0]['shipAddress']; ?></textarea>
                </td>
            </tr>
            
            <tr>
                <td class="border">大頭貼</td>
                <td class="border">
                <?php if($arr[0]['memberImg'] !== NULL) { ?>
                    <img class="w100px" src="./files/<?php echo $arr[0]['memberImg']; ?>" />
                <?php } ?>
                <input type="file" name="memberImg" />
                </td>
            </tr>
            <tr>
                <td class="border">功能</td>
                <td class="border">
                    <a href="./member_delete.php?deleteId=<?php echo $arr[0]['id']; ?>">刪除</a>
                </td>
            </tr>
        <?php
        } else {
        ?>
            <tr>
                <td class="border" colspan="6">沒有資料</td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
            <td class="border" colspan="6"><input type="submit" name="smb" value="修改"></td>
            </tr>
        </tfoo>
    </table>
    <input type="hidden" name="editId" value="<?php echo (int)$_GET['editId']; ?>">
</form>
</body>
</html>