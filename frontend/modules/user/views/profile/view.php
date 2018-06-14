<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use dosamigos\fileupload\FileUpload;
?>
<?php echo $user->nickname;?>
<br>
<?php echo $user->email;?>
<br>
<img src="<?php echo $user->getPicture();?>" id="profile-picture">
<br>
<button type="button" class="btn btn-default btn-lg" data-toggle="modal" data-target="#myModal1">
    <?php echo $user->getSubscriptionsCount();?> subscriptions
</button>
<button type="button" class="btn btn-default btn-lg" data-toggle="modal" data-target="#myModal2">
    <?php echo $user->getFollowersCount();?> followers
</button>

<?php if($currentUser && $currentUser->equals($user)):?>
<?= FileUpload::widget([
    'model' => $modelPicture,
    'attribute' => 'picture',
    'url' => ['/user/profile/upload-picture'], // your url, this is just for demo purposes,
    'options' => ['accept' => 'image/*'],
    'clientOptions' => [
        'maxFileSize' => 2000000
    ],
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                                $("#profile-picture").attr("src", data.result);
                            }'
    ],
]); ?>
<?php endif;?>
<br>
<br>
<?php if(!$currentUser->equals($user)):?>
<?php if($user->isSubscribe()):?>
    <a href="<?php echo Url::to(['/user/profile/unsubscribe', 'id' => $user->id])?>" class="btn btn-primary">
        Unsubscribe
    </a>
    <br>
<?php else: ?>
    <a href="<?php echo Url::to(['/user/profile/subscribe', 'id' => $user->id])?>" class="btn btn-primary">
        Subscribe
    </a>
<?php endif;?>
<?php endif;?>
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <?php foreach ($user->getSubscriptions() as $subscriber):?>
                    <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => $subscriber->nickname])?>">
                        <?php echo $subscriber->nickname;?>
                    </a>
                    <hr>
                <?php endforeach;?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <?php foreach ($user->getFollowers() as $follower):?>
                    <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => $follower->nickname])?>">
                        <?php echo $follower->nickname;?>
                    </a>
                    <hr>
                <?php endforeach;?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
