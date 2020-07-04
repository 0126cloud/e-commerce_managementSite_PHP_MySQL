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
<form name="myForm" method="POST" action="./member_insert.php" enctype="multipart/form-data">
<table class="border search table table-stripe table-hover">
    <thead>
    <tr>
                <th class="border">會員編號</th>
                <th class="border">姓名</th>
                <th class="border">會員等級</th>
                <th class="border">帳單地址</th>
                <th class="border">帳單城市</th>
                <th class="border">帳單郵遞區號</th>
                <th class="border">連絡電話</th>
                <th class="border">電子信箱</th>
                <th class="border">密碼</th>
                <th class="border">付款方式</th>
                <th class="border">運送地址</th>
                <th class="border">會員大頭貼</th>
            </tr>
    </thead>
    <tbody>
        <tr>
            <td class="border">
                <input type="text" name="memberId" id="memberId" value="M" maxlength="9" />
            </td>
            <td class="border">
                <input type="text" name="memberName" id="memberName" value="" maxlength="10" placeholder="陳小帥" />
            </td>
            <td class="border">
                <select name="class" id="class">
                    <option value="手摘" selected>手摘</option>
                    <option value="一番茶">一番茶</option>
                    <option value="二番茶">二番茶</option>
                </select>
            </td>
            <td class="border">
                <input type="text" name="paymentAddress" id="paymentAddress" value="" maxlength="30" placeholder="台北市帥哥區美女路87號2樓" />
            </td>
            <td class="border">
                <input type="text" name="paymentCity" id="paymentCity" value="" maxlength="10" placeholder="台北市" />
            </td>
            <td class="border">
                <input type="text" name="paymentZip" id="paymentZip" value="" maxlength="10" placeholder="123" />
            </td>
            <td class="border">
                <input type="text" name="phone" id="phone" value="" maxlength="10" placeholder="0912345678" />
            </td>
            <td class="border">
                <input type="text" name="email" id="email" value="" maxlength="20" placeholder="handsome@gmail.com" />
            </td>
            <td class="border">
                <input type="text" name="pwd" id="pwd" value="" maxlength="30" placeholder="請輸入密碼" />
            </td>
            <td class="border">
                <select name="paymentChoice" id="payChoice">
                    <option value="visa" selected>visa</option>
                    <option value="master">master</option>
                    <option value="jbc">jbc</option>
                    <option value="貨到付款">貨到付款</option>
                    <option value="第三方支付">第三方支付</option>
                </select>
            </td>
            <td class="border">
                <input type="text" name="shipAddress" id="shipAddress" value="" maxlength="30" placeholder="台北市帥哥區美女路87號2樓" />
            </td>
            <td class="border">
                <input type="file" name="memberImg" />
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td class="border" colspan="12"><input type="submit" name="smb" value="新增"></td>
        </tr>
    </tfoot>
</table>
</form>

</body>
</html>