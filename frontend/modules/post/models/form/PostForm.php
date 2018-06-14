<?php

namespace frontend\modules\post\models\form;

use frontend\models\Post;
use yii\base\Model;
use Yii;
use frontend\modules\user\models\User;
use Intervention\Image\ImageManager;
use frontend\modules\post\models\events\PostCreatedEvent;

class PostForm extends Model {

    const EVENT_POST_CREATED = 'post_created';

    public $picture;
    public $description;
    private $user;

    public function rules() {
        return [
            [['picture'], 'file', 'extensions' => ['jpg', 'png'], 'checkExtensionByMimeType' => true],
            [['description'], 'string'],
            [['description'], 'required'],
        ];
    }

    public function __construct(User $user) {
        $this->user = $user;
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'resizePicture']);
        $this->on(self::EVENT_POST_CREATED, [Yii::$app->feedService, 'addToFeed']);
    }


    public function resizePicture() {

    }

    public function save() {
        if($this->validate()) {

            $post = new Post();
            $post->filename = Yii::$app->storage->saveUploadedFile($this->picture);
            $post->date = time();
            $post->user_id = $this->user->getId();
            $post->description = $this->description;

            if($result = $post->save()) {
                $event = new PostCreatedEvent(Yii::$app->user->identity, $post);
                $this->trigger(self::EVENT_POST_CREATED, $event);
                return $result;
            }
        }
    }
}