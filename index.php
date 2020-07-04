<?php 
session_start();
require_once('./db.inc.php');
require_once('./tpl/tpl-html-head.php');
// require_once('./tpl/header.php'); 
// require_once('./tpl/tpl-carousel.php'); 
// require_once('./tpl/main.php');
// require_once('./tpl/footer.php'); 
require_once('./tpl/tpl-html-foot.php'); 
?>
<div class="login-bg">
    <h1 class="mano">MANO</h1>
    <form class="login" name="myForm" method="post" action="./login.php">
        <label class="text-dark">帳號：</label>
        <input class="form-control margin-bottom"type="text" name="username" value="" placeholder="請輸入Email" maxlength="50" />
        <label class="text-dark">密碼：</label>
        <input class="form-control" type="password" name="pwd" value="" placeholder="請輸入密碼" maxlength="50" />
        <div class="d-flex id-box">
            <div class="identify d-flex align-items-center">
                <label class="text-dark">買家</label>
                <input class="form-control login-radio" type="radio" name="identity" value="users" checked />
            </div>
            <div class="identify d-flex align-items-center">
                <label class="text-dark">賣家</label>
                <input class="form-control login-radio" type="radio" name="identity" value="admin" />
            </div>
        </div>
        <input style="background-color: #7ba188; border: 1px solid #7ba188; color: white;" class="form-control margin-bottom" type="submit" value="登入" />
        <a class="form-control register" href="./register.php">註冊</a>
</form>

</div>
