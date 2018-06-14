<?php

namespace  frontend\modules\post\models\events;

use yii\base\Event;
use frontend\modules\user\models\User;
use frontend\models\Post;

class PostCreatedEvent extends Event {

    public $user;
    public $post;

    public function __construct(User $u, Post $p) {
        $this->user = $u;
        $this->post = $p;
    }

    public function getUser() {
        return $this->user;
    }

    public function getPost() {
        return $this->post;
    }
}