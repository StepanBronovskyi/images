<?php

namespace frontend\modules\post\models;
/**
 * Created by PhpStorm.
 * User: Степан
 * Date: 06.04.2018
 * Time: 17:17
 */
use yii\db\ActiveRecord;
use Yii;

class Likes extends ActiveRecord {

    public static function tableName() {
        return 'likes';
    }

    public static function getCount($post_id) {
        return self::findOne(['post_id' => $post_id])->count;
    }

    public function like($currentUser, $post_id) {
        if(!self::findOne(['post_id' => $post_id])) {
            $likes = new Likes();
            $likes->post_id = $post_id;
            $likes->count = 1;
            $likes->users_id = $currentUser->id;
            $likes->save();
        }
        if($post = self::findOne(['post_id' => $post_id])) {
            $str_users_id = $post->users_id;
            $arr_users_id = explode(',', $str_users_id);
            if(in_array($currentUser->id, $arr_users_id)) {
                return $post->count;
            }
            array_push($arr_users_id, $currentUser->id);
            $post->users_id = implode(',', $arr_users_id);
            $post->count += 1;
            $count = $post->count;
            $post->save();
            return $count;
        }
    }

    public function unlike($currentUser, $post_id) {
        if(!self::findOne(['post_id' => $post_id])) {
            $likes = new Likes();
            $likes->post_id = $post_id;
            $likes->count = 0;
            $likes->save();
        }
        if($post = self::findOne(['post_id' => $post_id])) {
            $str_users_id = $post->users_id;
            $arr_users_id = explode(',', $str_users_id);
            if(in_array($currentUser->id, $arr_users_id)) {
                $index = array_search($currentUser->id, $arr_users_id);
                unset($arr_users_id[$index]);
                $post->users_id = implode(',', $arr_users_id);
                $post->count -= 1;
                $count = $post->count;
                $post->save();
                return $count;
            }
        }
    }
}