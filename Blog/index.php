<?php

    error_reporting(-1);
    session_start();

    require_once 'connect.php';
    require_once 'functions.php';
    getUserId('user1');
    if (isset($_GET['exit'])) {
        logout();
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
            <?php
            if (isset($_GET['show']) and $_GET['show'] == 'popular'){
                $articles = getPopularArticles();
            }elseif(isset($_GET['author'])){
                $articles = getArticles(getUserId($_GET['author']));
            }elseif (isset($_GET['hash'])){
               $articles = getArticlesByHash($_GET['hash']);
            }else{
                $articles = getArticles();
            }

            foreach ($articles as $article){
                ?>
                <article>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tittle-articles">
                                <h3><?php echo $article['tittle'] ?></h3>
                            </div>
                        </div>
                    </div><!-- /row -->
                    <div class="row">
                        <div class="col-md-4">
                            <img class="img-responsive" src="<?php echo $article['photo'] ?>" alt="">
                        </div>
                        <div class="col-md-8">
                            <div class="described-article">
                                <p>
                                    <?php echo $article['text'] ?>
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
                    <?php
                        if (isset($_COOKIE['coOk']) or isset($_SESSION['sesOk'])) { ?>
                            <div class="comm_like">
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
                            <?php
                        } ?>

                </article>
                <?php
            }
            ?>

            <div class="row qwe">
                <div class="col-md-2 col-md-offset-5">

                    <a href="#" class="show_more" data-offset="5">Показать еще</a>
                </div>
            </div><!-- /row -->
        </div> <!-- /container -->
    </div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
