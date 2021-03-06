<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'nickname',
            [
                'attribute' => 'picture',
                'format' => 'raw',
                'value' => function($user) {
                    return Html::img($user->getPicture(), ['width' => '70px']);
                },
            ],
            [
                'attribute' => 'nickname',
                /*'value' => function($url, $user) {
                    return Html::a(['/view/', 'id' => $user->id]);
                },*/
            ],
            'email:email',
            //'status',
            'created_at:date',
            //'updated_at',
            //'about:ntext',
            //'type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
