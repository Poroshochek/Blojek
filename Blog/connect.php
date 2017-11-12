<?php

    $db = mysqli_connect('localhost','root','','bloh') or die('Cannot connect to database');
    mysqli_set_charset($db, "utf8") or die('Cannot set Charset utf-8!');

