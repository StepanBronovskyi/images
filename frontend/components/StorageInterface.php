<?php
/**
 * Created by PhpStorm.
 * User: Степан
 * Date: 30.03.2018
 * Time: 16:29
 */

namespace frontend\components;
use yii\web\UploadedFile;

interface StorageInterface {

    public function saveUploadedFile(UploadedFile $file);

    public function getFile($filename);

}