<?php
require_once('./checkAdmin.php'); //引入登入判斷
require_once('../db.inc.php'); //引用資料庫連線
?>
<!DOCTYPYE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Marketing</title>
    <style>
    .border {
        border: 1px solid;
    }
    </style>
</head>
<body>
<?php require_once('./templates/title.php'); 
$sql_rs="";
?>
<hr />
<h3>行銷活動搜尋結果</h3>
<br>
<form action="marketSearch.php" method="GET" enctype= "multipart/form-data">
<input name="search" type="text" placeholder="找活動">
<input class="btn mano_check far fa-file" type="submit" value="搜尋">
<a class="btn mano_check far fa-file" href="./market.php">返回</a>
</form>
<br>

<form name="myForm" entype= "multipart/form-data" method="GET" action="./deleteMarket.php">
    <table class="border table table-striped table-hover">
        <thead>
            <tr>
                <th class="border">勾選</th>
                <th class="border">行銷ID</th>
                <th class="border">行銷名稱</th>
                <th class="border">行銷時段</th>
                <th class="border">行銷允許</th>
                <th class="border">行銷折扣</th>
                <th class="border">更新時間</th>
                <th class="border">功能</th>
            </tr>
        </thead>
        <tbody>
        <?php 
            /*if($totalDiscounts > 0) {
            } else { 
                //引入尚未建立商品種類的文字描述
                require_once('./templates/noCategory.php');   
            }*/
            $sqlTotal = "SELECT count(1)
                        FROM `marketing`
                        WHERE `discountName`   
                        OR `discountID`             
                        LIKE". $pdo->quote('%'.$_GET['search'].'%');
            
            //取得總筆數，沒有用到問號值，是用query，純sql文字語法
            $total = $pdo->query($sqlTotal)->fetch(PDO::FETCH_NUM)[0]; 
            //var_dump($total);
            $numPerPage = 5; //每頁幾筆

            //$total2 = $stmt2->rowCount();
            $totalPages = ceil($total/$numPerPage); // 總頁數
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; //目前第幾頁
            $page = $page < 1 ? 1 : $page; //若 page 小於 1，則回傳 1

        //SQL 敘述
        $sql_rs = "SELECT `marketing`.`id`,`marketing`.`discountID`, `marketing`.`discountName`, 
                          `marketing`.`discountPeriod`, `marketing`.`discountAllowed`, 
                          `marketing`.`discountMethod`, `marketing`.`updated_at`
                FROM `marketing` 
                WHERE `discountName`                
                LIKE ?
                OR `discountID` LIKE ?
                LIMIT ?, ? ";
//concat(IFNULL`discountID`,''),(IFNULL`discountName`,'')
        //設定繫結值
        $arr2 = ['%'.$_GET['search'].'%','%'.$_GET['search'].'%',($page -1 ) * $numPerPage,$numPerPage];

        //查詢分頁後的商品資料
        $stmt2 = $pdo->prepare($sql_rs);//有用問號，是用prepare, execute執行
        $result = $stmt2->execute($arr2);
        
       
        //若數量大於 0，則列出商品
        if($stmt2->rowCount() > 0) {
            $arr2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            for($i = 0; $i < count($arr2); $i++) {
        ?>
            <tr>
                <td class="border">
                    <input type="checkbox" name="chk[]" value="<?php echo $arr2[$i]['id']; ?>" />
                </td>
                <td class="border"><?php echo $arr2[$i]['discountID']; ?></td>
                <td class="border"><?php echo $arr2[$i]['discountName']; ?></td>
                <td class="border"><?php echo $arr2[$i]['discountPeriod']; ?></td>
                <td class="border"><?php echo $arr2[$i]['discountAllowed']; ?></td>
                <td class="border"><?php echo $arr2[$i]['discountMethod']; ?></td>
                <td class="border"><?php echo $arr2[$i]['updated_at']; ?></td>
                <td class="border">
                    <a class="btn mano_edit fas fa-edit" href="./editMarket.php?id=<?php echo $arr2[$i]['id']; ?>">編輯</a> 
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
                <td class="border" colspan="9">


                <?php
                for($i = 1; $i <= $totalPages; $i++){ ?>
                    <a href="?search=<?php echo $_GET['search']?>&page=<?=$i?>"><?= $i ?></a>
                <?php } ?>
                </td>
            </tr>
            <tr>
                <td class="border" colspan="9"><input class="btn mano_delete fas fa-trash-alt" type="submit" name="smb" value="刪除"></td>
            </tr>
             
            
        </tfoot>
    </table>
</form>
</body>
</html>