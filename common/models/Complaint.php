<?php
/**
 * Created by PhpStorm.
 * User: Степан
 * Date: 26.04.2018
 * Time: 16:34
 */

namespace common\models;


use yii\db\ActiveRecord;
use Yii;

class Complaint extends ActiveRecord {

    public static function tableName() {
        return 'complaints';
    }

    public function report($id, \frontend\modules\user\models\User $currentUser) {
        if(!$complaint = $this->findOne(['post_id' => $id])) {
            $this->post_id = $id;
            $this->users_id = $currentUser->getId();
            $this->count = 1;
            $this->save();
        }
        else {
            $usersArr = explode(',',$complaint->users_id);
            if(!in_array($currentUser->id, $usersArr)) {
                array_push($usersArr, $currentUser->id);
                $complaint->users_id = implode(',', $usersArr);
                $complaint->count += 1;
                $complaint->save();
            }
            return;
        }
    }

    public static function isReported($id) {
        if($complaint = self::findOne(['post_id' => $id])) {
            $usersArr = explode(',', $complaint->users_id);
            $currentUser = Yii::$app->user->identity;
            if(in_array($currentUser->id, $usersArr)) {
                return true;
            }
            return false;
        }
    }
}