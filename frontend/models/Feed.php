<?php
/**
 * Created by PhpStorm.
 * User: Степан
 * Date: 15.04.2018
 * Time: 12:21
 */

namespace frontend\models;


use frontend\modules\post\models\Likes;
use yii\db\ActiveRecord;

class Feed extends ActiveRecord {

    public static function tableName() {
        return 'feeds';
    }

    public function getLikesCount() {
        return Likes::getCount($this->post_id);
    }
}