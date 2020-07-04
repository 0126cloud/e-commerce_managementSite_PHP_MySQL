<?php
session_start();
require_once('./db.inc.php');
require_once('./tpl/tpl-html-head.php'); 
//require_once('./tpl/header.php');
?>
<div class="login-bg">
<h1 class="mano">MANO</h1>
<form class="login" name="myForm" method="POST" action="./addUser.php">
        <div class="form-group">
            <label for="inputUsername">Email：</label>
            <input type="text" class="form-control" id="inputUsername" name="username" placeholder="請輸入Email" value="">
        </div>
        <div class="form-group">
            <label for="inputPassword">密碼：</label>
            <input type="password" class="form-control" id="inputPassword" name="pwd" placeholder="請輸入密碼" value="">
        </div>
        <div class="form-group">
            <label for="inputName">姓名：</label>
            <input type="text" class="form-control" id="inputName" name="name" placeholder="請輸入您的姓名" value="">
        </div>
        <input style="background-color: #7ba188; border: 1px solid #7ba188; color: white;" class="form-control margin-bottom" type="submit" value="確定註冊" />
        <a class="form-control register" href="./index.php">返回登入頁</a>
</form>

</div>
<?php
//require_once('./tpl/footer.php');
require_once('./tpl/tpl-html-foot.php'); 
?>