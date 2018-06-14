<?php
/**
 * Created by PhpStorm.
 * User: Степан
 * Date: 04.03.2018
 * Time: 10:08
 */

namespace common\components;


use yii\base\Component;
use common\components\UserNotificationInterface;

class EmailService extends Component {

    public function notifyUser($event) {
        mail($event->getEmail(), 'Hello', $event->getUserName()." ".$event->getSubject());
        if($event->callAdmin) {
            self::notifyAdmin($event);
        }
    }

    public function notifyAdmin($event) {
        mail('bronovskyi74@gmail.com', 'New subscriber', $event->getUserName().' had been register');
    }
}