<?php
/**
 * Created by PhpStorm.
 * User: Степан
 * Date: 15.03.2018
 * Time: 17:14
 */

namespace frontend\modules\user\models;
use yii\base\Model;
use Yii;
use frontend\modules\user\models\User;

class SignupForm extends Model {

    public $nickname;
    public $email;
    public $password;

    public function rules() {
        return [
            [['email', 'password', 'nickname'], 'required'],
            [['email'], 'email'],
            [['password'], 'string', 'min' => 4, 'max' => 25],
            [['email'], 'validateEmail'],
            [['nickname'], 'validateNickname'],
        ];
    }

    public function signup() {
        if($this->validate() && $this->validateEmail() && $this->validateNickname()) {
            $newUser = new User();
            $newUser->nickname = $this->nickname;
            $newUser->email = $this->email;
            $newUser->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            $newUser->auth_key = Yii::$app->security->generateRandomString();
            $newUser->created_at = $time = time();
            $newUser->updated_at = $time;
            if($newUser->save()) {
                return $newUser;
            }
        }
        return false;
    }

    public function validateEmail() {
        return User::checkEmailExist($this->email);
    }

    public function validateNickname() {
        return User::checkNicknameExist($this->nickname);
    }
}