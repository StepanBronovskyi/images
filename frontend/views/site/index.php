<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
$this->title = 'My Yii Application';
use frontend\widgets\newslist\NewsList;
use frontend\models\Post;
use frontend\modules\user\models\User;
use common\models\Complaint;
?>

<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="<?php echo Url::to(['newsletter/subscribe']);?>">Subscribe</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <?php foreach ($feedList as $feedItem):?>

                <img src="
                    <?php
                        $author_id = $feedItem->author_id;
                        $user = User::getUserById($author_id);
                        echo $user->getPicture();
                    ?>
                " width="40" height="40" style="border-radius: 50%">
                <a href="/<?php echo $feedItem->author_nickname;?>"><?php echo $feedItem->author_nickname;?></a>
                <br>
                <img src="
                    <?php
                        $post_id = $feedItem->post_id;
                        $post = Post::getPostById($post_id);
                        echo $post->getImage();
                    ?>
                " width="600px" height="600px">
                <p><?php echo $feedItem->post_description;?></p>
                <p><?php echo Yii::$app->formatter->asDatetime($feedItem->post_created_at);?></p>
                Likes: <span id="like-count"><?php echo $feedItem->getLikesCount(); ?></span>
                <br>
                <br>
                <a href="#" class="btn btn-primary button-like" id="<?php echo $feedItem->post_id;?>">
                    Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
                </a>
                <a href="#" class="btn btn-primary button-unlike" id="<?php echo $feedItem->post_id;?>">
                    Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
                </a>
                <?php if(!Complaint::isReported($feedItem->post_id)):?>
                    <a href="/complaint/add/<?php echo $feedItem->post_id;?>" class="btn btn-primary button-complaint" id="<?php echo $feedItem->post_id;?>">
                        Complaint <span class="glyphicon glyphicon-bell"></span>
                    </a>
                <?php endif;?>
                <hr>
            <?php endforeach;?>
        </div>

    </div>
</div>
<?= yii\authclient\widgets\AuthChoice::widget([
    'baseAuthUrl' => ['site/auth'],
    'popupMode' => false,
]) ?>

<?php $this->registerJsFile('@web/js/like.js', [
    'depends' => \yii\web\JqueryAsset::className(),
])?>
<?php $this->registerJsFile('@web/js/complaint.js', [
    'depends' => \yii\web\JqueryAsset::className(),
])?>