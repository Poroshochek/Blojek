<?php
    error_reporting(-1);
    session_start();
//if not reg
    if (!(isset($_COOKIE['coOk'])) or !(isset($_SESSION['sesOk']))) {
        header("Location: index.php");
    }

    require_once 'functions.php';
    require_once 'connect.php';

    if (isset($_GET['exit'])) {
        logout();
    }

    $article['tittle'] = '';
    $article['text'] = 'Текст';
    $hashStr = 'HashTags';

    if(isset($_POST['action']) and $_POST['action'] == 'add'){
        global $db;

        $stmt = mysqli_prepare($db, "INSERT INTO `articles` (tittle, text, photo, id_user) VALUES (?, ?, ?, ?);");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }
        $hashTags = explode(',', $_POST['hashtags']);

        $uploaddir = __DIR__.'/uploads/';
        $uploadfile = $uploaddir . basename($_FILES['upload']['name']);

        if (move_uploaded_file($_FILES['upload']['tmp_name'], $uploadfile)) {
            $link = 'uploads/'.basename($_FILES['upload']['name']);
            mysqli_stmt_bind_param($stmt, 'ssss', $_POST['tittleArt'], $_POST['textArt'], $link, $_COOKIE['coOk']);

            if (!mysqli_stmt_execute($stmt)) {
                die ('stmt error: '.mysqli_stmt_error($stmt));
            }

            $articleId = mysqli_stmt_insert_id($stmt); //get last ex id
            mysqli_stmt_close($stmt);
            if (!setHashTag($hashTags, $articleId)) {
                die('no hash tags');
            }
            //close query

            header("Location: cabinet.php");
        } else {
            die('can not upload file');
        }
    } elseif (isset($_POST['action']) and $_POST['action'] == 'edit') {
        $article = getArticleById($_POST['id'])[0];
        $hashTags = getHashTags($article['id']);
        $hashStr = '';
    //get hash for input
        foreach ($hashTags as $hashTag) {
            foreach ($hashTag as $hash) {
                $hashStr .= $hash.',';
            }
        }

    } elseif (isset($_POST['action']) and $_POST['action'] == 'upgrade') {
        global $db;

        $stmt = mysqli_prepare($db, "UPDATE `articles` SET tittle=?, text=?, photo=? WHERE id =?;");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }

        $hashTags = explode(',', $_POST['hashtags']);

        $uploaddir = __DIR__.'/uploads/';
        $uploadfile = $uploaddir . basename($_FILES['upload']['name']);

        if (move_uploaded_file($_FILES['upload']['tmp_name'], $uploadfile)) {
            $link = 'uploads/'.basename($_FILES['upload']['name']);
            mysqli_stmt_bind_param($stmt, 'ssss', $_POST['tittleArt'], $_POST['textArt'], $link, $_POST['id']);

            if (!mysqli_stmt_execute($stmt)) {
                die ('stmt error: '.mysqli_stmt_error($stmt));
            }

            $articleId = $_POST['id'];
            mysqli_stmt_close($stmt);
            if (!setHashTag($hashTags, $articleId, true)) {
                die('no hash tags');
            }
            //close query

            header("Location: cabinet.php");
        } else {
            die('can not upload file');
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
    <title>Document</title>

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

    <div id="content">
        <div class="container">
            <div class="add-article">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <form class="form-horizontal" enctype="multipart/form-data" action="addArticles.php" method="post" >
                            <?php echo (isset($_POST['action']) and $_POST['action'] == 'edit') ? '<input type="hidden" name="id" value="'.$_POST['id'].'">' : '' ?>
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <input type="text" class="form-control" name="tittleArt" value="<?= $article['tittle']; ?>" placeholder="tittle">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <textarea class="form-control" name="textArt" rows="3"><?= $article['text']; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <input type="text" class="form-control" name="hashtags" value="<?= $hashStr; ?>" placeholder="hashtags">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <input type="file" name="upload" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default" name="action" value="<?php echo (isset($_POST['action']) and $_POST['action'] == 'edit') ? 'upgrade' : 'add' ?>">Добавить</button>
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
