<?php

namespace frontend\widgets\newslist;

use yii\base\Widget;
use Yii;
use frontend\models\News;

class NewsList extends Widget{

    public $limit = null;

    public function run() {

        $list = News::getNewsList($this->limit);
        return $this->render('news', [
            'list' => $list,
        ]);
    }

}