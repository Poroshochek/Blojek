<?php


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
            <article>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="tittle-articles">
                            <h3>Tittle</h3>
                        </div>
                    </div>
                </div><!-- /row -->
                <div class="row">
                    <div class="col-sm-4">
                        <img class="img-responsive" src="images/1.jpg" alt="">
                    </div>
                    <div class="col-sm-8">
                        <div class="described-article">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus, vero,
                                obcaecati, aut, error quam sapiente nemo saepe quibusdam sit excepturi nam quia
                                corporis eligendi eos magni recusandae laborum minus inventore?
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus, vero,
                                obcaecati, aut, error quam sapiente nemo saepe quibusdam sit excepturi nam quia
                                corporis eligendi eos magni recusandae laborum minus inventore?
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus, vero,
                                obcaecati, aut, error quam sapiente nemo saepe quibusdam sit excepturi nam quia
                                corporis eligendi eos magni recusandae laborum minus inventore?
                            </p>
                        </div>
                    </div>
                </div><!-- /row -->
                <div class="hashtags">
                    <div class="row">
                        <div class="col-sm-3">
                            <a href="#">#lorem</a>
                            <a href="#">#ipsum</a>
                        </div>
                    </div><!-- /row -->
                </div>
                <div class="comm-like">
                    <div class="row">
                        <div class="col-sm-4">
                            <p>
                                <a class="comments" href="#">
                                    <span class="glyphicon glyphicon-comment" aria-hidden="true"></span> 40
                                </a>
                                <span class="glyphicon glyphicon-thumbs-up likes" aria-hidden="true"></span> 5
                            </p>
                        </div>
                    </div><!-- /row -->
            </article>
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" action="" method="post">
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                <textarea class="form-control" rows="3">Добавить комментарий</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                <button type="submit" class="btn btn-default">Sign in</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /row -->
            <hr>
            <div class="messages">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <p><a href="">Vasya (2017-02-11 12:12:12)</a></p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus, vero,
                            obcaecati, aut, error quam sapiente nemo saepe quibusdam sit excepturi nam quia
                            corporis eligendi eos magni recusandae laborum minus inventore?
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus, vero,
                            obcaecati, aut, error quam sapiente nemo saepe quibusdam sit excepturi nam quia
                            corporis eligendi eos magni recusandae laborum minus inventore?
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus, vero,
                            obcaecati, aut, error quam sapiente nemo saepe quibusdam sit excepturi nam quia
                            corporis eligendi eos magni recusandae laborum minus inventore?
                        </p>
                    </div>
                </div> <!--/row-->
            </div> <!--/messages-->
        </div> <!-- /container -->
    </div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
