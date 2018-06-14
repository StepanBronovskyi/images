<?php

namespace console\models;

use Yii;

class Sender {

    public static function send($newsList) {

        $result = Yii::$app->mailer->compose('/mailer/index', [
            'newsList' => $newsList,
        ])
            ->setFrom("adminYii2@gmail.com")
            ->setTo('bronovskyi74@gmail.com')
            ->setSubject('Just message')
            ->send();
        var_dump($result);
    }
}