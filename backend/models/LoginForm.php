<?php
/**
 * Created by PhpStorm.
 * User: Степан
 * Date: 15.03.2018
 * Time: 17:14
 */

namespace backend\models;
use yii\base\Model;

class LoginForm extends Model {

    public $email;
    public $password;

    public function rules() {
        return [
            [['email', 'password'], 'required'],
            [['email'], 'email'],
            [['password'], 'string', 'min' => 4, 'max' => 25],
            [['password'], 'validatePassword'],
        ];
    }

    public function login() {
        if($this->validate()) {
            return User::getUserByEmail($this->email);
        }
    }

    public function validatePassword() {
        return User::checkPassword($this->password);
    }

}