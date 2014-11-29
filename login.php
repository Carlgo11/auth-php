<?php
if (isset($_POST['login'])) {
    require 'lib/auth/API.php';
    $output = Login::doLogin($_POST['username'], $_POST['password']);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <link rel="icon" type="image/vnd.microsoft.icon"  href="./resources/favicon.ico"/>
        <link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="http://getbootstrap.com/examples/signin/signin.css" rel="stylesheet">
        <title>
            <?php include 'config.php'; echo $conf['title']; ?>
        </title>
    </head>
    <body>
        <form class="form-signin" role="form" action="" method="POST">
            <?php
            if (isset($output) && !$output) {
                echo '<div clasn"s="alert alert-danger" role="alert"><b>Wrong Username or Password!</b><br>Maybe you misspelled something?</div>';
            }
            ?>
            <input type="text" name="username" class="form-control" placeholder="Username" required="" autofocus="" autocomplete="off" style="margin-top: 20px">
            <input type="text" name="password" class="form-control" placeholder="Password" required="" autofocus="" autocomplete="off" style="margin-top: 10px">
            <button type="submit" name="login" style="margin-top: 5px" >Login</button>
        </form>
    </body>
</html>