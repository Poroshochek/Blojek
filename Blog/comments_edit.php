<?php
    require_once 'connect.php';
    require_once 'functions.php';


    if(isset($_POST['id']) and $_POST['id']!=''){

    updateComment($_POST['comment'], $_POST['id']);

    header("Location: comments.php?id=".$_POST['article_id']);
    }


    if(isset($_GET['id']) and $_GET['id']!=''){
        ?>
        <div class="row">
            <div class="col-sm-6 col-md-offset-3">
                <form class="form-horizontal" action="comments_edit.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                    <input type="hidden" name="article_id" value="<?php echo $_GET['article_id']?>">
                    <div class="form-group">
                        <div class="col-sm-8">
                            <textarea class="form-control" name="comment" rows="3"><?php
                                echo getCommentsByID($_GET['id']);
                                ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <button class="btn btn-primary" type="submit">UPDATE</button>
                        </div>
                    </div>
                </form><br>
            </div>
        </div>
        <?php
    }