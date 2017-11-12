<?php
    error_reporting(-1);
    session_start();

    require_once 'connect.php';
    require_once 'functions.php';
    //if already registered
//    require_once 'islogining.php';


    //exit
    if (isset($_GET['exit'])) {
        logout();
    }

    $errors = array();

    $login = '';
    $password = '';

    if (isset($_POST['reg'])) {
        header("Location: registration.php");
    }

    if (isset($_POST['sign'])) {
        $_SESSION['login'] = $_POST['login'];
        $login = $_SESSION['login'];
        $_SESSION['password'] = $_POST['password'];
        $password = $_SESSION['password'];


        if (!checkLog($login)) {
            $errors[] = 'Неправельный логин, просьба повторить!';
        }

        if (!checkPaswd($login, $password)) {
            $errors[] = 'Неправельный пароль, попробуйте еще раз!';
        }

        if (empty($errors)) {
//            unset($_SESSION['login']);
//            unset($_SESSION['password']);

            if (isset($_POST['checkbox']) == 1) {
                $_SESSION['sesOk'] = getUserId($login);

                header("Location: index.php");

            } elseif (isset($_POST['checkbox']) == 0) {
                $_SESSION['sesOk'] = getUserId($login);
                setcookie('coOk', getUserId($login), time()+3600*10);

                header("Location: index.php");
            }

//            print_r($_SESSION)and die;
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
    <title>Authorization</title>

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
                   <form class="form-horizontal" action="" method="post">
                       <div class="form-group">
                           <label for="login" class="col-sm-2 control-label">Логин</label>
                           <div class="col-sm-10">
                               <input type="text" name="login" class="form-control" id="login" placeholder="Login"
                                      value="<?= (isset($login))? htmlspecialchars("$login") : '' ?>">
                           </div>
                       </div>
                       <div class="form-group">
                           <label for="inputPassword3" class="col-sm-2 control-label">Пароль</label>
                           <div class="col-sm-10">
                               <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Password">
                           </div>
                       </div>
                       <div class="form-group">
                           <div class="col-sm-offset-2 col-sm-10">
                               <div class="checkbox">
                                   <label>
                                       <input type="checkbox" name="checkbox" value="1"> Чужой компьютер
                                   </label>
                               </div>
                           </div>
                       </div>
                       <div class="form-group">
                           <div class="col-sm-offset-2 col-sm-10">
                               <button type="submit" class="btn btn-default" name="sign">Войти </button>
                               <button type="submit" class="btn btn-default" name="reg">Зарегистрироваться</button>
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
