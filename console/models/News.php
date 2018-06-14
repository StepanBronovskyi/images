<?php

namespace console\models;

use Yii;

class News {

    public static function getNewsList() {

        $query = "SELECT * FROM new_news";
        return Yii::$app->db->createCommand($query)->queryAll();

    }

    public static function prepareNews($news) {
        foreach ($news as &$newsItem) {
            $newsItem['content'] = Yii::$app->stringHelper->getShortNews($newsItem['content']);
        }
        return $news;
    }

}