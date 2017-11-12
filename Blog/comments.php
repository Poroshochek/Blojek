<?php
    session_start();
    require_once 'connect.php';
    require_once  'functions.php';

    if (!(isset($_COOKIE['coOk'])) and !(isset($_SESSION['sesOk']))) {
        header("Location: index.php");
    }

    if (isset($_GET['exit'])) {
        logout();
    }

    if(isset($_POST['comment']) and $_POST['comment'] != ''){
        if(isset($_POST['id_user'])){
            if(setComment($_POST['comment'], $_POST['id_user'], $_POST['id_article'])){
                header("Location: comments.php?id=".$_POST['id_article']);
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


    <div class="container">
        <?php
            if (isset($_GET['id']) and $_GET['id'] != '') {
                $article = getArticleById($_GET['id'])[0];
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
            <div class="row">
                <div class="col-sm-6 col-md-offset-3">
                    <form class="form-horizontal" action="comments.php" method="post">
                        <input type="hidden" name="id_user" value="<?php echo $_COOKIE['coOk']; ?>">
                        <input type="hidden" name="id_article" value="<?php echo $article['id']; ?>">
                        <div class="form-group">
                            <div class="col-sm-8">
                                <textarea class="form-control" name="comment" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8">
                                <button class="btn btn-primary" type="submit">SEND</button>
                            </div>
                        </div>
                    </form><br>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?php
                    $comments = getCommentsByArticle($article['id']);
                    foreach ($comments as $comment){
                        ?>
                        <div class="comment">
                            <a href="#"><?php echo $comment['author'].'('.$comment['date'].')'; ?></a>
                            <p><?php echo $comment['comment']; ?></p>
                            <?php
                            if(($_COOKIE['coOk'] == $comment['id_user']) or (getUserRole($_COOKIE['coOk']) == 1)){
                                ?>

                                <div class="row">
                                    <div class="col-sm-3">
                                        <a href="comments_edit.php?id=<?php echo $comment['id']; ?>&article_id=<?php echo $article['id']; ?>">EDIT</a>
                                    </div>
                                    <div class="col-sm-3">
                                        <a href="comments_delete.php?id=<?php echo $comment['id']; ?>&article_id=<?php echo $article['id']; ?>">DELETE</a>
                                    </div>
                                </div><br>

                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        <?php
            }
            ?>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
