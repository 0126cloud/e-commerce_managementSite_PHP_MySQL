<nav class="navbar d-flex nav2">  
        <div>
         <a class="logo" href="./home.php"><img url=""class="logo"><h1>抹の</h1></a>
        </div>
         <div>
            <a class="" href="./itemList.php">商品一覽</a>
            <a class="" href="./itemList.php?shippingId=S001">低溫配送專區</a>
            <a class="" href="./myCart.php">
                <span>我的購物車</span>
                (<span id="cartItemNum">
                <?php 
                if(isset($_SESSION["cart"])) {
                    echo count($_SESSION["cart"]);
                } else {
                    echo 0;
                }
                ?>
                </span>)
            </a>

            <?php if(isset($_SESSION["username"])) { ?>
            <a class="" href="./order.php">我的訂單</a>
            <a class="" href="./itemTracking.php">商品追蹤清單</a>
            <?php } ?>

        
            <?php if(!isset($_SESSION["username"])){ ?>
                <a class="" href="./register.php">註冊</a>
            <?php } else { ?>
                <span><?php echo $_SESSION["name"] ?> 您好</span>
            <?php } ?>

            <?php require_once("./tpl/login.php") ?>
        
        <a class="" href="http://www.facebook.com/">
        <i class="fab fa-facebook-square"></i></a>
        </div>
</nav>