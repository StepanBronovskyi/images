<?php

namespace frontend\modules\post\controllers;

use frontend\modules\post\models\form\PostForm;
use frontend\modules\post\models\Likes;
use yii\web\Controller;
use Yii;
use yii\web\UploadedFile;
use frontend\models\Post;
/**
 * Default controller for the `post` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionCreate() {

        $model = new PostForm(Yii::$app->user->identity);

        if($model->load(Yii::$app->request->post())) {

            $model->picture = UploadedFile::getInstance($model, 'picture');

            if($model->save()) {
                return $this->redirect(['/user/profile/view', 'nickname' => Yii::$app->user->identity->nickname]);
            }
        }

        return $this->render('create',[
            'model' => $model
        ]);
    }

    public function actionView($id) {

        $post = Post::getPostById($id);

        return $this->render('view', [
            'post' => $post,
        ]);
    }

    public function actionLike() {
        $currentUser = Yii::$app->user->identity;
        $post_id = Yii::$app->request->post('id');
        $like = new Likes();
        return $like->like($currentUser, $post_id);
    }

    public function actionUnlike() {
        $currentUser = Yii::$app->user->identity;
        $post_id = Yii::$app->request->post('id');
        $like = new Likes();
        return $like->unlike($currentUser, $post_id);
    }
}
