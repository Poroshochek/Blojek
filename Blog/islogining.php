<?php

    if (isset($_COOKIE['coOk']) || isset($_SESSION['sesOk'])) {
        header("Location: index.php");
    }
