<?php
/**
 * Created by PhpStorm.
 * User: Степан
 * Date: 04.04.2018
 * Time: 19:46
 */

namespace frontend\models;


use yii\db\ActiveRecord;
use Yii;
use frontend\modules\post\models\Likes;
use frontend\modules\user\models\User;

class Post extends ActiveRecord {

    public static function tableName() {
        return 'post';
    }

    public static function getPostById($id) {
        return self::findOne(['id' => $id]);
    }

    public function getImage() {
        return Yii::$app->storage->getFile($this->filename);
    }

    public function getAuthor() {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->one()->nickname;
    }

    public function getLikesCount() {
        return Likes::getCount($this->id);
    }
}