<?php
    error_reporting(-1);
    session_start();

    require_once 'connect.php';
    require_once 'functions.php';


    if (isset($_GET['exit'])) {
        logout();
    }

    $errors = array();

    $login = '';
    $email = '';

    if (isset($_POST['registration'])) {
        $_SESSION['login'] = $_POST['login'];
        $login = $_SESSION['login'];
        $_SESSION['email'] = $_POST['email'];
        $email = $_SESSION['email'];

        if (!islogin($_POST['login'])) {
            $errors[] = 'Логин не должен пустовать, но должен сосстоять из латинских букв и быть не более 32 символов';
        }

        if (!isEmail($_POST['email'])) {
            $errors[] = "Неверно введен email, просьба проверить!<br>
                         Все буквы должны быть латинскими!<br>
                         В начале строки недопускаються: точка или цифра!";
        }

        if (!isPassword($_POST['password'])) {
            $errors[] = 'Пароль должен быть больше 3-х символов и меньше, а так же содержать только лат. буквы и цифры';
        }

        if (empty($errors)) {
            //add to data
            if (addUser($login, $email, $_POST['password'])) {
                echo 'User added';
                header("location: authorization.php");
            } else {
                $errors[] = 'Пользователь с данным именем уже существует!';
            }
        }
    }

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" type="text/css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
    <?php require_once 'elements/header.php'?>

    <?php require_once  'elements/navbar.php'?>

    <?php if (!empty($errors)) : ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 text-center errors">
                    <br>
                    <p><?=array_shift($errors)?></p>
                </div>
            </div>
        </div>
    <?php endif ?>
    <div id="content">
        <div class="container">
            <div class="authorization">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <form class="form-horizontal" method="post">
                            <div class="form-group">
                                <label for="login" class="col-sm-2 control-label">Логин</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="login" name="login" placeholder="Login" maxlength="32"
                                           value="<?= (isset($login))? htmlspecialchars("$login") : '' ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Е-мейл</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="inputEmail3" name="email" placeholder="example@server.name"
                                           value="<?= (isset($email))? htmlspecialchars("$email") : '' ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">Пароль</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="inputPassword3" name="password" placeholder="12345">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
<!--                                    <button type="submit" class="btn btn-default" name="sign">Войти </button>-->
                                    <button type="submit" class="btn btn-default" name="registration">Зарегистрироватсья</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- /container -->
    </div>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
