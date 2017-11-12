
<header>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="logo">
                    <h1><a href="index.php">Блог=)</a></h1>
                </div>
            </div>
            <div class="col-sm-3 col-sm-offset-3">
                <div class="logon">
                    <?php if (!isset($_SESSION['sesOk']) && !isset($_COOKIE['coOk'])): ?>
                        <a href="authorization.php">Авторизация</a>
                    <?php else: ?>
                        <a href="cabinet.php">Личный кабинет</a>
                        <a href="?exit">Выход</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>