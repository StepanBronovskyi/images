<?php

namespace frontend\modules\user\controllers;

use frontend\modules\user\models\LoginForm;
use yii\web\Controller;
use frontend\modules\user\models\SignupForm;
use Yii;
use frontend\modules\user\models\Subscription;
use frontend\modules\user\models\Follower;

/**
 * Default controller for the `user` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin() {

        $model = new LoginForm();

        if($model->load(Yii::$app->request->post()) && $user = $model->login()) {
            Yii::$app->user->login($user);
            return $this->redirect(['/site/index']);
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSignup() {

        $model = new SignupForm();

        if($model->load(Yii::$app->request->post()) && $user = $model->signup()) {
            $subscription = new Subscription();
            $follower = new Follower();
            $subscription->registerUser($user);
            $follower->registerUser($user);
            Yii::$app->user->login($user);
            $this->redirect(['/site/index']);
        }

        return $this->render('signup',[
            'model' => $model,
        ]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->redirect(['/site/index']);
    }
}
