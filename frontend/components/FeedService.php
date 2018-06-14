<?php
/**
 * Created by PhpStorm.
 * User: Степан
 * Date: 15.04.2018
 * Time: 12:20
 */

namespace frontend\components;


use frontend\modules\post\models\events\PostCreatedEvent;
use yii\base\Component;
use frontend\models\Feed;
use yii\base\Event;

class FeedService extends Component {

    public function addToFeed(PostCreatedEvent $e) {

        $user = $e->getUser();
        $post = $e->getPost();

        $followers = $user->getFollowers();

        foreach ($followers as $followerItem) {
            $feed = new Feed();
            $feed->user_id = $followerItem->id;
            $feed->author_id = $user->getId();
            $feed->author_nickname = $user->nickname;
            $feed->post_id = $post->id;
            $feed->post_filename = $post->filename;
            $feed->post_description = $post->description;
            $feed->post_created_at = $post->date;

            $feed->save();
        }
    }
}