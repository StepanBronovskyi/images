<?php
/**
 * Created by PhpStorm.
 * User: Степан
 * Date: 26.03.2018
 * Time: 17:38
 */

namespace frontend\modules\user\models;


use yii\base\Model;

class PictureForm extends Model {

    public $picture;

    public function rules() {
        return [
           [['picture'], 'file', 'extensions' => ['jpg', 'png'], 'checkExtensionByMimeType' => true
           ],
        ];
    }

    public function save() {
        return 1;
    }
}