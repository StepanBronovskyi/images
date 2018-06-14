<?php

namespace backend\controllers;

use Yii;
use common\models\Complaint;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Post;
use frontend\models\Feed;

/**
 * ComplaintController implements the CRUD actions for Complaint model.
 */
class ComplaintController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Complaint models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Complaint::find()->orderBy('count DESC'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Complaint model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {

        $post_id = Complaint::findOne($id)->post_id;

        $post = Post::getPostById($post_id);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'post' => $post,
        ]);
    }
    /**
     * Deletes an existing Complaint model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {

        $post_id = Complaint::findOne($id)->post_id;
        $this->findComplaint($id)->delete();
        $this->findPost($post_id)->delete();
        $feedList = $this->findFeed($post_id);

        foreach ($feedList as $feedItem) {
            $feedItem->delete();
        }
        return $this->redirect(['/complaint/index']);
    }

    public function actionApprove($id) {
        $this->findComplaint($id)->delete();
        return $this->redirect(['/complaint/index']);
    }

    /**
     * Finds the Complaint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Complaint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findPost($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    protected function findComplaint($id)
    {
        if (($model = Complaint::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    protected function findFeed($id) {

        return Feed::find()->where(['post_id' => $id])->all();
    }
}
