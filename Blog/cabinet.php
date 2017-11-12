<?php

    require_once 'connect.php';
    require_once 'functions.php';
    session_start();
    //exit
    if (isset($_GET['exit'])) {
        logout();
    }

    if (!(isset($_COOKIE['coOk'])) or !(isset($_SESSION['sesOk']))) {
        header("Location: index.php");
    }



    if(isset($_POST['action']) and $_POST['action'] == 'del'){
        $stmt = mysqli_prepare($db, "DELETE FROM `articles` WHERE id = ?;");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }

        mysqli_stmt_bind_param($stmt, 's', $_POST['id']);

        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
        header("Location: cabinet.php");
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
            <div class="to-article-add">
                <div class="row">
                    <div class="col-sm-2 col-sm-offset-5">
                        <a href="addArticles.php">Добавить еще статей!</a>
                    </div>
                </div>
                <hr>
            </div>
            <div class="row">
                <div class="col-sm-2 col-sm-offset-5">
                    <span>Мои Статьи:</span>
                </div>
            </div>
            <?php



            if (getUserRole($_COOKIE['coOk']) == 1) {
                $articles = getArticles();
            } else {
                $articles = getArticles($_COOKIE['coOk']);
            }


            foreach ($articles as $article) {
              ?>
                <article>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tittle-articles">
                                <h3><?= $article['tittle'];?></h3>
                            </div>
                        </div>
                    </div><!-- /row -->
                    <div class="row">
                        <div class="col-md-4">
                            <img class="img-responsive" src="<?= $article['photo'];?>" alt="">
                        </div>
                        <div class="col-md-8">
                            <div class="described-article">
                                <p>
                                    <?= $article['text'];?>
                                </p>
                            </div>
                        </div>
                    </div><!-- /row -->
                    <div class="hashtags">
                        <div class="row">
                            <div class="col-md-3">
                                <?php
                                $hashTags = getHashTags($article['id']);
                                foreach($hashTags as $hashTag){
                                    echo '<a href="index.php?hash='.$hashTag['name'].'">#'.$hashTag['name'].'</a>';
                                }
                                ?>
                            </div>
                        </div><!-- /row -->
                    </div>
                    <div class="comm-like">
                        <div class="row">
                            <div class="col-md-4">
                                <p>
                                    <a class="comments" href="comments.php?id=<?php echo $article['id']; ?>">
                                        <span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <?php echo getCommentsCount($article['id'])[0]['count']; ?>
                                    </a>
                                    <a href="#" class="set_like" data-article="<?php echo $article['id']; ?>" data-user="<?php echo $_COOKIE['coOk']; ?>">
                                        <span class="glyphicon glyphicon-thumbs-up likes" aria-hidden="true"></span> <span class="like"><?php
                                            echo get_likes($article['id']);
                                            ?></span>
                                    </a>
                                </p>
                            </div>
                        </div><!-- /row -->
                    </div>
                    <div class="control-buttons">
                        <div class="row">
                            <div class="col-md-3">
                                <form action="addArticles.php" method="post">
                                    <input type="hidden" name="id" value="<?= $article['id']; ?>">
                                    <button type="submit" name="action" value="edit" class="btn btn-info">Edit</button>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <form action="cabinet.php" method="post">
                                    <input type="hidden" name="id" value="<?= $article['id']; ?>">
                                    <button type="submit" name="action" value="del" class="btn btn-danger">delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </article>
                <?php
            }
            ?>
            <div class="row qwe">
                <div class="col-md-2 col-md-offset-5">
                    <a class="show_more_cabinet" href="#" data-offset="5">Показать еще</a>
                </div>
            </div><!-- /row -->
        </div> <!-- /container -->
    </div> <!-- /content -->


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
