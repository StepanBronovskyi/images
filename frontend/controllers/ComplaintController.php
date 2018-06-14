<?php

namespace frontend\controllers;
use yii\web\Controller;
use common\models\Complaint;
use Yii;


class ComplaintController extends Controller {

    public function actionAdd($id) {
        $model = new Complaint();
        $currentUser = Yii::$app->user->identity;
        $model->report($id, $currentUser);
        return $this->redirect(['/site/index/']);
    }

}