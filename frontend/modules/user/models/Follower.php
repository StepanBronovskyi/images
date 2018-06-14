<?php
/**
 * Created by PhpStorm.
 * User: Степан
 * Date: 18.03.2018
 * Time: 16:50
 */

namespace frontend\modules\user\models;


use yii\db\ActiveRecord;
use frontend\modules\user\models\User;

class Follower extends ActiveRecord {

    public static function tableName() {
        return 'followers';
    }

    public  function registerUser(User $user) {
        $this->user_id = $user->id;
        $this->save();
    }

    public function subscribeUser($user, $currentUser) {
        if($follower = $this::find()->where(['user_id' => $user->id])->one()) {
            $foll_str = $follower->followers;
            $foll_arr = explode(",", $foll_str);
            $foll_arr[] = $currentUser->id;
            $foll_arr = array_unique($foll_arr);
            $follower->followers = implode(",", $foll_arr);
            $follower->save();
        }
    }

    public function cancelSubscribe($user, $currentUser) {
        if($follower = $this::find()->where(['user_id' => $user->id])->one()) {
            $foll_str = $follower->followers;
            $foll_arr = explode(",", $foll_str);
            $key = array_search($currentUser->id, $foll_arr);
            unset($foll_arr[$key]);
            $follower->followers = implode(",", $foll_arr);
            $follower->save();
        }
    }
}