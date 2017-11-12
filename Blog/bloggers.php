<?php
require_once 'connect.php';
require_once 'functions.php';

$bloggers = getBestBloggers();
foreach ($bloggers as $blogger) {
    ?>
    <a href="index.php?author=<?php echo $blogger['login']; ?>"><?php echo $blogger['login']; ?></a>:<span><?php echo $blogger['likes']; ?></span><br>
    <?php
}