<?php
/**
 * Created by PhpStorm.
 * User: Степан
 * Date: 18.03.2018
 * Time: 16:50
 */

namespace frontend\modules\user\models;
use frontend\modules\user\models\User;


use yii\db\ActiveRecord;

class Subscription extends ActiveRecord {

    public static function tableName() {
        return 'subscriptions';
    }

    public  function registerUser(User $user) {
        $this->user_id = $user->id;
        $this->save();
    }

    public function followUser($currentUser, $user) {
        if($subscription = $this->find()->where(['user_id' => $currentUser->id])->one()) {
            if($subscription->subscriptions) {
                $subscr = explode(",", $subscription->subscriptions);
                $subscr[] = $user->id;
                $subscr = array_unique($subscr);
                $subscription->subscriptions = implode(",", $subscr);
                $subscription->save();
            }
            else {
                $subscr_array = array();
                array_push($subscr_array, $user->id);
                $subscr_str = implode(",", $subscr_array);
                $subscription->subscriptions = $subscr_str;
                $subscription->save();
            }
        }
    }

    public function cancelFollow($currentUser, $user) {
        if($subscription = $this::find()->where(['user_id' => $currentUser->id])->one()) {
            $subscr_str = $subscription->subscriptions;
            $subscr_arr = explode(",", $subscr_str);
            $key = array_search($user->id, $subscr_arr);
            unset($subscr_arr[$key]);
            $subscr_str = implode(",", $subscr_arr);
            $subscription->subscriptions = $subscr_str;
            $subscription->save();
        }
    }
}