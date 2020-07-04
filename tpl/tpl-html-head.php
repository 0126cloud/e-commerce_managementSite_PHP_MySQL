<!DOCTYPYE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="DarrenYang">
    <title>抹の</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="./css/carousel.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Sawarabi+Mincho&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kalam&display=swap" rel="stylesheet">
    <style>
        /* Miz create */

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* 顏色專區 勿動 */
        :root {
            --mainColor: #016D1B;
            --secondColor: #A1CD2E;
            --thirdColor: #996145;
            --bgColor: rgb(255, 243, 199);
            --checkColor:#78962E;
            --editColor:#D29454;
            --deleteColor:#CE5145;
        }

        /* 網頁呈現相關 */
        .container {
            max-width: 960px;
            margin: 0 auto;
        }

        .d-flex {
            display: flex;
        }

        /* 網頁呈現相關end */

        /* 樣式相關開始 */

        .manoBorder {
            border: 2px solid var(--mainColor);
            border-radius: 15px;
        }

        .bgcolor1 {
            background-color: var(--bgColor);
        }
        .bgcolor2 {
            background-color: var(--secondColor);
        }
        .bgcolor3 {
            background-color: var(--thirdColor);
        }

        .mano_dark {
            background-color: var(--mainColor);
            color: #fff;

        }

        .mano_light {
            color: var(--mainColor);
            border: 2px solid var(--mainColor);
        }

        .mano_check {
            background-color: var(--checkColor);
            color: #fff;

        }
        .mano_edit {
            background-color: var(--editColor);
            color: #fff;

        }
        .mano_delete {
            background-color: var(--deleteColor);
            color: #fff;

        }
        /* 樣式結束 */

        /* 導覽列開始 */
        .navbar{
            
            background-color: #fff;
            color: var(--mainColor);
        }            
        nav .btn,nav .btn:hover{
            background-color: #fff;
            color: var(--mainColor);
            
        }

        .logo {
            font-size: 36px;
            font-family: 'Sawarabi Mincho', sans-serif;
        }

        .logo h1{
            display: inline-block;
            color: transparent;
            background: linear-gradient(#A1CD2E, green);
            -webkit-background-clip: text;
            font-size:64px;
        }
        
        .nav2{
            justify-content: space-between;
        }

        .dropdown{
            background-color: var(--mainColor);
            /* border-right:1px solid yellow; */
        }
        .dropdown-menu a,.dropdown-menu a:hover{
            color: var(--mainColor);
        }
        /* 導覽列結束 */
        /* 清單相關開始 */
        /* 參考教學 */
        .treeview 
        .list-group-item{cursor:pointer}
        .treeview .node-disabled{color:silver;cursor:not-allowed}
        /* .node-treeview1:not(.node-disabled):hover{background-color:#F5F5F5;}  */
        /* 參考教學結束 */
        .treeview a,.treeview a:hover,.treeview a:visited{
            color: var(--mainColor);
        }
        .list-group-item{
            color: #000;
            border-style:none;
        }
        nav  a,nav a:hover,nav span{
            /* display: block; */
            color:  #000;
            padding: 0 12px;
        } 

        /* 清單相關結束 */
        .table {
            width:100%;

        }
        thead{
            background-color: var(--secondColor);
            /* color: #fff; */
        }
        body{
            font-family:微軟正黑體;
        }
        table td.shrink { 
        white-space:nowrap ;
        } 
        table td.expand { 
            width: 99% ;
        } 
/* Miz create END */
        .login-bg {
            width: 100%;
            height: 100%;
            background-color: #7ba188;
            text-align: center;
        }

        .mano {
            color: white;
            margin: 50px auto 0;
            font-family: 'Kalam', cursive;
            text-shadow: 2px 2px 5px black;
        }

        .login {
            text-align: left;
            max-width: 300px;
            margin: 20px auto;
            box-shadow: 3px 3px 5px gray;
            padding: 30px;
            border-radius: 3px;
            background-color: white;
        }

        .margin-bottom {
            margin-bottom: 10px;
        }

        .identify {
            width: 100px;
            margin: 10px;
        }

        .login-radio {
            width: 15px;
            margin-left:5px; 
        }

        .register:link,
        .register:hover,
        .register:active {
            text-align: center;
            text-decoration: none;
            color: black;
        }

    </style>
</head>
<body class="d-flex flex-column h-100">