<?php

namespace console\controllers;

use console\models\News;
use console\models\Sender;

class MailerController extends \yii\console\Controller {

    public function actionSend() {

        $newsList = News::getNewsList();
        $newsList = News::prepareNews($newsList);

        Sender::send($newsList);
    }
}