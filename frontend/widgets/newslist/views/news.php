<?php foreach ($list as $news):?>

    <h1>
        <a href="<?php echo Yii::$app->urlManager->createUrl(['news/view', 'id' => $news['id']])?>">
            <?php echo $news['title']?>
        </a>
    </h1>
    <p><?php echo $news['author']?></p>
    <p><?php echo $news['content']?></p>
    <br>

<?php endforeach;?>