<?php
    session_start();
    require_once 'connect.php';
    require_once 'functions.php';

    if (isset($_POST['action']) and $_POST['action'] == 'show_more') {
        global  $db;

        $stmt = mysqli_prepare($db, "SELECT * FROM `articles` ORDER BY date DESC LIMIT 5 OFFSET ?;");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }
        mysqli_stmt_bind_param($stmt, 's', $_POST['offset']);
        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $echo = '';

        foreach ($finding as $article) {
            $hashTags = getHashTags($article['id']);
            $likes = get_likes($article['id']);
            $commentsCount = getCommentsCount($article['id'])[0]['count'];
            $coOk = '';
            $sesOk = '';
            $commLike = '';
            if (isset($_COOKIE['coOk'])) {
                $coOk = $_COOKIE['coOk'];
                $commLike .= <<<EOT
                        <div class="comm_like">
                            <div class="row">
                                <div class="col-md-4">
                                    <p>
                                        <a class="comments" href="comments.php?id={$article['id']}">
                                            <span class="glyphicon glyphicon-comment" aria-hidden="true"></span> {$commentsCount}
                                        </a>
                                        <a href="#" class="set_like" data-article="{$article['id']}" data-user="{$coOk}">
                                            <span class="glyphicon glyphicon-thumbs-up likes" aria-hidden="true"></span> <span class="like">{$likes}</span>
                                        </a>
                                    </p>
                                </div>
                            </div><!-- /row -->
                        </div>
EOT;

            } elseif (isset($_SESSION['sesOk'])) {
                $sesOk = $_SESSION['sesOk'];
                $commLike .= <<<EOT
                        <div class="comm_like">
                            <div class="row">
                                <div class="col-md-4">
                                    <p>
                                        <a class="comments" href="comments.php?id={$article['id']}">
                                            <span class="glyphicon glyphicon-comment" aria-hidden="true"></span> {$commentsCount}
                                        </a>
                                        <a href="#" class="set_like" data-article="{$article['id']}" data-user="{$sesOk}">
                                            <span class="glyphicon glyphicon-thumbs-up likes" aria-hidden="true"></span> <span class="like">{$likes}</span>
                                        </a>
                                    </p>
                                </div>
                            </div><!-- /row -->
                        </div>
EOT;
            };
            $hash_tags = '';
            foreach($hashTags as $hashTag){
                $hash_tags .= '<a href="index.php?hash='.$hashTag['name'].'">#'.$hashTag['name'].'</a>';
            }
            $echo .=  <<<EOT
            <article>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="tittle-articles">
                                    <h3>{$article['tittle']}</h3>
                                </div>
                            </div>
                        </div><!-- /row -->
                        <div class="row">
                            <div class="col-md-4">
                                <img class="img-responsive" src="{$article['photo']}" alt="">
                            </div>
                            <div class="col-md-8">
                                <div class="described-article">
                                    <p>
                                        {$article['text']}
                                    </p>
                                </div>
                            </div>
                        </div><!-- /row -->
                        <div class="hashtags">
                            <div class="row">
                                <div class="col-md-3">
                                    $hash_tags
                                </div>
                            </div><!-- /row -->
                        </div>
                        $commLike
                    </article>
EOT;
        }
        echo $echo;
    } elseif (isset($_POST['action']) and $_POST['action'] == 'set_like') {
        $stmt = mysqli_prepare($db, "SELECT * FROM `likes` WHERE id_user=? AND id_article=?;");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }
        mysqli_stmt_bind_param($stmt, 'ss', $_POST['user'], $_POST['article']);

        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if (empty($finding)) {
            $stmt = mysqli_prepare($db, "INSERT INTO `likes` (id_user, id_article) VALUES (?,?);");

            if (!$stmt) {
                die('mysqli error: '.mysqli_error($db));
            }

            mysqli_stmt_bind_param($stmt, 'ss', $_POST['user'], $_POST['article']);

            if (!mysqli_stmt_execute($stmt)) {
                die ('stmt error: '.mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);

            echo 'pluse';
        } else {
            $stmt = mysqli_prepare($db, "DELETE FROM `likes` WHERE id_user = ? AND id_article = ?;");

            if (!$stmt) {
                die('mysqli error: '.mysqli_error($db));
            }

            mysqli_stmt_bind_param($stmt, 'ss', $_POST['user'], $_POST['article']);

            if (!mysqli_stmt_execute($stmt)) {
                die ('stmt error: '.mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
            echo 'mines';
        }

    } elseif (isset($_POST['action']) and $_POST['action'] == 'show_more_cabinet') {
        global  $db;
        if (getUserRole($_COOKIE['coOk']) == 1) {
            $stmt = mysqli_prepare($db, "SELECT * FROM `articles` ORDER BY date DESC LIMIT 5 OFFSET ?;");
            if (!$stmt) {
                die('mysqli error: '.mysqli_error($db));
            }
            mysqli_stmt_bind_param($stmt, 's', $_POST['offset']);
        } else {
            $stmt = mysqli_prepare($db, "SELECT * FROM `articles` WHERE id_user=? ORDER BY date DESC LIMIT 5 OFFSET ?;");
            if (!$stmt) {
                die('mysqli error: '.mysqli_error($db));
            }
            mysqli_stmt_bind_param($stmt, 'ss', $_COOKIE['coOk'], $_POST['offset']);
        }



        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $echo = '';

        foreach ($finding as $article) {
            $hashTags = getHashTags($article['id']);
            $likes = get_likes($article['id']);
            $commentsCount = getCommentsCount($article['id'])[0]['count'];
            $coOk = '';
            $sesOk = '';
            $commLike = '';
            if (isset($_COOKIE['coOk'])) {
                $coOk = $_COOKIE['coOk'];
                $commLike .= <<<EOT
                        <div class="comm_like">
                            <div class="row">
                                <div class="col-md-4">
                                    <p>
                                        <a class="comments" href="comments.php?id={$article['id']}">
                                            <span class="glyphicon glyphicon-comment" aria-hidden="true"></span> {$commentsCount}
                                        </a>
                                        <a href="#" class="set_like" data-article="{$article['id']}" data-user="{$coOk}">
                                            <span class="glyphicon glyphicon-thumbs-up likes" aria-hidden="true"></span> <span class="like">{$likes}</span>
                                        </a>
                                    </p>
                                </div>
                            </div><!-- /row -->
                        </div>
EOT;

            } elseif (isset($_SESSION['sesOk'])) {
                $sesOk = $_SESSION['sesOk'];
                $commLike .= <<<EOT
                        <div class="comm_like">
                            <div class="row">
                                <div class="col-md-4">
                                    <p>
                                        <a class="comments" href="comments.php?id={$article['id']}">
                                            <span class="glyphicon glyphicon-comment" aria-hidden="true"></span> {$commentsCount}
                                        </a>
                                        <a href="#" class="set_like" data-article="{$article['id']}" data-user="{$sesOk}">
                                            <span class="glyphicon glyphicon-thumbs-up likes" aria-hidden="true"></span> <span class="like">{$likes}</span>
                                        </a>
                                    </p>
                                </div>
                            </div><!-- /row -->
                        </div>
EOT;
            };
            $hash_tags = '';
            foreach($hashTags as $hashTag){
                $hash_tags .= '<a href="index.php?hash='.$hashTag['name'].'">#'.$hashTag['name'].'</a>';
            }
            $echo .=  <<<EOT
            <article>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="tittle-articles">
                                    <h3>{$article['tittle']}</h3>
                                </div>
                            </div>
                        </div><!-- /row -->
                        <div class="row">
                            <div class="col-md-4">
                                <img class="img-responsive" src="{$article['photo']}" alt="">
                            </div>
                            <div class="col-md-8">
                                <div class="described-article">
                                    <p>
                                        {$article['text']}
                                    </p>
                                </div>
                            </div>
                        </div><!-- /row -->
                        <div class="hashtags">
                            <div class="row">
                                <div class="col-md-3">
                                    $hash_tags
                                </div>
                            </div><!-- /row -->
                        </div>
                        $commLike
                        <div class="control-buttons">
                        <div class="row">
                            <div class="col-md-3">
                                <form action="addArticles.php" method="post">
                                    <input type="hidden" name="id" value="{$article['id']}">
                                    <button type="submit" name="action" value="edit" class="btn btn-info">Edit</button>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <form action="cabinet.php" method="post">
                                    <input type="hidden" name="id" value="{$article['id']}">
                                    <button type="submit" name="action" value="del" class="btn btn-danger">delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    </article>
EOT;
        }
        echo $echo;
    }