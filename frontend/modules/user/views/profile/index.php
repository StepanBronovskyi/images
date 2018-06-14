<?php use yii\helpers\Url; ?>
<?php foreach ($userList as $user):?>
    <h4><a href="<?php echo Url::to(['/user/profile/view', 'nickname' => $user->nickname])?>">
            <?php echo $user->nickname;?>
        </a>
    </h4>
    <hr>
<?php endforeach;?>
