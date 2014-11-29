<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <link rel="icon" type="image/vnd.microsoft.icon"  href="./res/favicon.ico"/>
        <link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="http://getbootstrap.com/examples/signin/signin.css" rel="stylesheet">
        <title></title>
    </head>
    <body>
        <?php
        if (isset($_POST['register'])) {
            if ($_POST['repassword'] == $_POST['password']) {
                require 'lib/auth/login.php';
                if (Login::register($_POST['username'], $_POST['password']) == 1) {
                    echo "true";
                } elseif(Login::register($_POST['username'], $_POST['password']) == 0) {
                    $error = "<b>User already exists!</b>";
                }else{
                    echo "false";
                }
            } else {
                $error = "<b>Passwords doesn't match!</b>";
            }
        }
        ?>
        <form class="form-signin" role="form" action="" method="POST">
            <?php
            if (isset($error)) {
                echo '<div clasn"s="alert alert-danger" role="alert">' . $error . '</div>';
            }
            ?>
            <input type="text" name="username" class="form-control" placeholder="Username" required="" autofocus="" autocomplete="off" style="margin-top: 20px">
            <input type="text" name="password" class="form-control" placeholder="Password" required="" autofocus="" autocomplete="off" style="margin-top: 10px">
            <input type="text" name="repassword" class="form-control" placeholder="Renter Password" required="" autofocus="" autocomplete="off" style="margin-top: 10px">
            <button type="submit" name="register" style="margin-top: 5px">Register</button>
        </form>
    </body>
</html>