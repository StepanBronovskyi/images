<?php
/**
 * Created by PhpStorm.
 * User: Степан
 * Date: 17.03.2018
 * Time: 11:50
 */

namespace frontend\modules\user\controllers;
use yii\web\Controller;
use frontend\modules\user\models\User;
use Yii;
use frontend\modules\user\models\Subscription;
use frontend\modules\user\models\Follower;
use frontend\modules\user\models\PictureForm;
use yii\web\Response;
use yii\web\UploadedFile;

class ProfileController extends Controller{

    public function actionIndex() {
        $userList = User::getUserList();

        return $this->render('index', [
            'userList' => $userList,
        ]);
    }

    public function actionView($nickname) {
        $user = User::getUserByNickname($nickname);
        $currentUser = Yii::$app->user->identity;

        $modelPicture = new PictureForm();

        return $this->render('view', [
            'user' => $user,
            'currentUser' => $currentUser,
            'modelPicture' => $modelPicture,
        ]);
    }

    public function actionUploadPicture() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new PictureForm();
        $model->picture =  UploadedFile::getInstance($model, 'picture');

        if($model->validate()) {
            $user = Yii::$app->user->identity;
            $user->picture = Yii::$app->storage->saveUploadedFile($model->picture);
            if($user->save()) {
                return  Yii::$app->storage->getFile($user->picture);
            }

        }
    }

    public function actionSubscribe($id) {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        $currentUser = Yii::$app->user->identity;
        $user = User::getUserById($id);

        $subscribe = new Subscription();
        $follower = new Follower();

        $subscribe->followUser($currentUser, $user);
        $follower->subscribeUser($user, $currentUser);

        $this->redirect(['/user/profile/view', 'nickname' => $user->nickname]);
    }

    public function actionUnsubscribe($id) {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/logout']);
        }

        $currentUser = Yii::$app->user->identity;
        $user = User::getUserById($id);

        $subscribe = new Subscription();
        $follower = new Follower();

        $subscribe->cancelFollow($currentUser, $user);
        $follower->cancelSubscribe($user, $currentUser);
        return $this->redirect(['/user/profile/view/', 'nickname' => $user->nickname]);
    }
}