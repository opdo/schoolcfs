<?php
    require_once 'cfs.php';
    require_once 'myfunc.php';
    $result = '';
    if (isset($_SESSION["error_msg"])) {
         $result = $_SESSION["error_msg"];
         $result = '
         <div class="panel alert">
            <div class="heading">
                <span class="title">'.$result.'</span>
            </div>
        </div>
        ';
         unset($_SESSION["error_msg"]);
    }
    $helper = $fb->getRedirectLoginHelper();
    $permissions = ['email','manage_pages','publish_pages']; // optional
    if ($SETTING_DOMAIN == "http://localhost/") $SETTING_DOMAIN = $SETTING_DOMAIN.'/cfs/';
    $loginUrl = $helper->getLoginUrl($SETTING_DOMAIN.'login-callback.php', $permissions);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel='shortcut icon' type='image/x-icon' href='favicon.ico' />

    <title>Login Confession</title>

    <link href="css/metro.min.css" rel="stylesheet">
    <link href="css/metro-icons.min.css" rel="stylesheet">
    <link href="css/metro-responsive.min.css" rel="stylesheet">

    <script src="js/jquery.min.js"></script>
    <script src="js/metro.min.js"></script>
 
    <style>
        .login-form {
            width: 25rem;
            height: 13.25rem;
            position: fixed;
            top: 50%;
            margin-top: -9.375rem;
            left: 50%;
            margin-left: -12.5rem;
            background-color: #ffffff;
            opacity: 0;
            -webkit-transform: scale(.8);
            transform: scale(.8);
        }
    </style>
    <script>
        $(function(){
            var form = $(".login-form");

            form.css({
                opacity: 1,
                "-webkit-transform": "scale(1)",
                "transform": "scale(1)",
                "-webkit-transition": ".5s",
                "transition": ".5s"
            });
        });
    </script>
</head>
<body class="bg-darkTeal">
    <div class="login-form padding20 block-shadow">
        <div>
            <?php echo $result; ?>
        </div>
        <form role="form" method="post" action="login.php">
            <h1 class="text-light">Hutech Confession</h1>
            <hr class="thin"/>
            <br />
            <div class="form-actions align-center">
                <a href=<?php echo '"'.$loginUrl.'"'; ?> type="submit" class="button primary">Login with Facebook</a>
            </div>
        </form>
    </div>
</body>
</html>