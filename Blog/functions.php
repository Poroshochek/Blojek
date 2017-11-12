<?php
    //-----------------------------------------------------registration----------------------------------------
    function isLogin ($login)
    {
        if (!preg_match('/[a-z0-9]{1,32}$/', $login)) {
            return false;
        } else {
            return true;
        }
    }

    function isEmail ($email)
    {
        if (!preg_match("/^[^\.0-9]+[a-zA-Z0-9_\.\-]+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/", $email)) {
            return false;
        } else {
            return true;
        }
    }

    function isPassword ($passwd)
    {
        if (!preg_match('/[a-zA-Z0-9]{3,32}$/', $passwd)) {
            return false;
        } else {
            return true;
        }
    }

    function isDuplicate ($login) {
        global  $db;

        $stmt = mysqli_prepare($db, "SELECT login FROM `sers` WHERE login=?;");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }

        mysqli_stmt_bind_param($stmt, 's', $login);

        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);
        //выводим масив всех данных и проверяем нашли или нет
//        print_r($finding);

        mysqli_stmt_close($stmt);

        return $finding;
    }

    function getUserRole ($id)
    {
        global  $db;

        $stmt = mysqli_prepare($db, "SELECT access FROM `sers` WHERE id=?;");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }

        mysqli_stmt_bind_param($stmt, 's', $id);

        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $finding[0]['access'];
    }

    function getUserId($login)
    {
        global  $db;

        $stmt = mysqli_prepare($db, "SELECT id FROM `sers` WHERE login=?;");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }

        mysqli_stmt_bind_param($stmt, 's', $login);

        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $finding[0]['id'];
    }


    function addUser ($login, $email, $password) {
        global $db;

        $login = trim($login);
        $email = trim($email);
        $password = password_hash($password, PASSWORD_BCRYPT);

        if (isDuplicate($login)) {
            return false; //сообщаем что уже есть пользователь с таким логином
        }

        $stmt = mysqli_prepare($db, "INSERT INTO `sers` (login, email, password) VALUES (?, ?, ?);");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }
        mysqli_stmt_bind_param($stmt, 'sss', $login, $email, $password);

        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }

        //close query
        mysqli_stmt_close($stmt);

        return true;
    }
//-------------------------------------------------------addHash-tags-------------------------------------

    function setHashTag ($hashTags, $article_id, $upd=null)
    {
        if (!is_array($hashTags)) {
            die();
        }

        global  $db;

        $ids = array();//for ids
        foreach ($hashTags as $name) {
            global  $db;

            $name = trim($name);
            $stmt = mysqli_prepare($db, "SELECT id FROM `hash_tags` WHERE `name` LIKE ?;");

            if (!$stmt) {
                die('mysqli error: '.mysqli_error($db));
            }

            mysqli_stmt_bind_param($stmt, 's', $name);

            if (!mysqli_stmt_execute($stmt)) {
                die ('stmt error: '.mysqli_stmt_error($stmt));
            }

            $result = mysqli_stmt_get_result($stmt);
            $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);

            if($finding){
                $ids[] = $finding[0]['id'];
            } else {
                $stmt = mysqli_prepare($db, "INSERT INTO `hash_tags` (name) VALUES (?);");

                if (!$stmt) {
                    die('mysqli error: '.mysqli_error($db));
                }
                mysqli_stmt_bind_param($stmt, 's', $name);

                if (!mysqli_stmt_execute($stmt)) {
                    die ('stmt error: '.mysqli_stmt_error($stmt));
                }
                $ids[] = mysqli_stmt_insert_id($stmt);
                mysqli_stmt_close($stmt);
            }

        }

        if ($upd!=null) {
            $stmt = mysqli_prepare($db, "DELETE FROM `hash_article` WHERE id_article = ?;");

            if (!$stmt) {
                die('mysqli error: '.mysqli_error($db));
            }

            mysqli_stmt_bind_param($stmt, 's', $article_id);

            if (!mysqli_stmt_execute($stmt)) {
                die ('stmt error: '.mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
        }
        if (!empty($ids)) {
            $req_str = '';
            foreach ($ids as $key => $id){
                if($key+1 != count($ids)){
                    $req_str .= '('.$article_id.', '.$id.'),';
                }else{
                    $req_str .= '('.$article_id.', '.$id.')';
                }
            }
            $stmt = mysqli_prepare($db, "INSERT INTO `hash_article` (id_article, id_hash) VALUES $req_str;");

            if (!$stmt) {
                die('mysqli error: '.mysqli_error($db));
            }

            if (!mysqli_stmt_execute($stmt)) {
                die ('stmt error: '.mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
        }

        return true;
    }

    function get_likes($id){
        global  $db;
        $stmt = mysqli_prepare($db, "SELECT COUNT(id_article) as count FROM `likes` WHERE `id_article`= ?;");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }

        mysqli_stmt_bind_param($stmt, 's', $id);

        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $finding[0]['count'];
    }

    function getHashTags($id)
    {
        global  $db;

        $stmt = mysqli_prepare($db, "SELECT `hash_tags`.name FROM `hash_tags` LEFT JOIN `hash_article` ON `hash_tags`.id = `hash_article`.id_hash WHERE  `hash_article`.id_article = ?;");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }

        mysqli_stmt_bind_param($stmt, 's', $id);

        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $finding;
    }

    function getArticles($user_id=null)
    {
        if($user_id!=null){
            global  $db;

            $stmt = mysqli_prepare($db, "SELECT * FROM `articles` WHERE articles.id_user=? ORDER BY date DESC LIMIT 5;");

            if (!$stmt) {
                die('mysqli error: '.mysqli_error($db));
            }

            mysqli_stmt_bind_param($stmt, 's', $user_id);

            if (!mysqli_stmt_execute($stmt)) {
                die ('stmt error: '.mysqli_stmt_error($stmt));
            }

            $result = mysqli_stmt_get_result($stmt);
            $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);

            return $finding;
        } else {
            global  $db;

            $stmt = mysqli_prepare($db, "SELECT * FROM `articles` ORDER BY date DESC LIMIT 5;");

            if (!$stmt) {
                die('mysqli error: '.mysqli_error($db));
            }

            if (!mysqli_stmt_execute($stmt)) {
                die ('stmt error: '.mysqli_stmt_error($stmt));
            }

            $result = mysqli_stmt_get_result($stmt);
            $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);

            return $finding;
        }

    }

    function getArticleById ($article_id=null)
    {
        if($article_id!=null){
            global  $db;

            $stmt = mysqli_prepare($db, "SELECT * FROM `articles` WHERE id=?;");

            if (!$stmt) {
                die('mysqli error: '.mysqli_error($db));
            }

            mysqli_stmt_bind_param($stmt, 's', $article_id);

            if (!mysqli_stmt_execute($stmt)) {
                die ('stmt error: '.mysqli_stmt_error($stmt));
            }

            $result = mysqli_stmt_get_result($stmt);
            $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $finding;
        }
    }

    function setComment ($comment, $id_user, $id_article)
    {
        global $db;

        $stmt = mysqli_prepare($db, "INSERT INTO `comments` (comment, id_user, id_article) VALUES (?, ?, ?);");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }
        mysqli_stmt_bind_param($stmt, 'sss', $comment, $id_user, $id_article);

        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }

        //close query
        mysqli_stmt_close($stmt);

        return true;
    }

    function getCommentsByArticle ($id)
    {
        global  $db;

        $stmt = mysqli_prepare($db, "SELECT `sers`.login as author, `comments`.date, `comments`.comment, `comments`.id_user, `comments`.id  FROM `comments` LEFT JOIN `sers` ON `sers`.id = `comments`.id_user WHERE  `comments`.id_article = ? ORDER BY comments.id DESC;");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }

        mysqli_stmt_bind_param($stmt, 's', $id);

        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $finding;
    }

    function getCommentsByID ($id)
    {
        global  $db;

        $stmt = mysqli_prepare($db, "SELECT comment FROM `comments` WHERE id = ?;");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }

        mysqli_stmt_bind_param($stmt, 's', $id);

        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $finding[0]['comment'];
    }

    function updateComment ($comment, $id)
    {
        global  $db;

        $stmt = mysqli_prepare($db, "UPDATE `comments` SET comment=? WHERE id=?;");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }

        mysqli_stmt_bind_param($stmt, 'ss', $comment, $id);

        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);

    }

    function getCommentsCount ($id)
    {
        global  $db;

        $stmt = mysqli_prepare($db, "SELECT COUNT(id_article) as count FROM `comments` WHERE id_article = ?;");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }

        mysqli_stmt_bind_param($stmt, 's', $id);

        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $finding;
    }

    function getBestBloggers ()
    {
        global  $db;

        $stmt = mysqli_prepare($db, "SELECT COUNT(`sers`.id) as likes, `sers`.login FROM `likes` LEFT JOIN `articles` ON (`articles`.`id` = `likes`.`id_article`) LEFT JOIN `sers` ON (`articles`.`id_user` = `sers`.id) GROUP BY `sers`.id ORDER  BY likes DESC;");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }



        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $finding;
    }

    function getPopularArticles(){
        global  $db;

        $stmt = mysqli_prepare($db, "SELECT `articles`.* FROM `articles` LEFT JOIN `likes` ON `likes`.`id_article` = `articles`.`id` GROUP BY `articles`.`id` ORDER BY COUNT(`articles`.id) DESC LIMIT 5;");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }

        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $finding;
    }

    function getArticlesByHash($hash){
        global  $db;

        $stmt = mysqli_prepare($db, "SELECT `articles`.* FROM `articles`, `hash_article`, `hash_tags` WHERE `articles`.id = `hash_article`.`id_article` and `hash_tags`.id = `hash_article`.`id_hash` AND `hash_tags`.`name` = ? LIMIT 5;");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }
        mysqli_stmt_bind_param($stmt, 's', $hash);

        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $finding;
    }


//-------------------------------------------------------check-log-pass-------------------------------------
    function checkLog ($login)
    {
        global $db;

        $login = trim($login);

        $stmt = mysqli_prepare($db, "SELECT login FROM `sers` WHERE login=?");

        mysqli_stmt_bind_param($stmt, 's', $login);

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }

        if (!mysqli_stmt_execute($stmt)) {
            die(mysqli_stmt_error($stmt));
        }
        $result = mysqli_stmt_get_result($stmt);
        $finding = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);

        if ($finding) {
            return true;
        } else {
            return false;
        }
    }

    function checkPaswd ($log, $password)
    {
        global $db;

        $log = trim($log);

        $stmt = mysqli_prepare($db, "SELECT login, password FROM `sers` WHERE login=?");

        mysqli_stmt_bind_param($stmt, 's', $log);

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }

        if (!mysqli_stmt_execute($stmt)) {
            die(mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $findingPass = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);

        if (!empty($findingPass)) {
            $hash = $findingPass[0]['password'];
        } else {
            $hash ='';
        }

        if (password_verify($password, $hash)) {
            return true;
        } else {
            return false;
        }
    }

//-------------------------------------------------exit----------------------------------------------

    function logout ()
    {
        unset($_SESSION['sesOk']);
        setcookie('coOk', '');

        header("Location: index.php");
    }
