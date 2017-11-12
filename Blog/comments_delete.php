<?php
    require_once 'connect.php';
    require_once 'functions.php';

    if (isset($_GET['id']) and $_GET['id']!='') {
        global  $db;

        $stmt = mysqli_prepare($db, "DELETE FROM `comments` WHERE id = ?;");

        if (!$stmt) {
            die('mysqli error: '.mysqli_error($db));
        }

        mysqli_stmt_bind_param($stmt, 's', $_GET['id']);

        if (!mysqli_stmt_execute($stmt)) {
            die ('stmt error: '.mysqli_stmt_error($stmt));
        }
        header("Location: comments.php?id=".$_GET['article_id']);
    }

