<?php
/**
 * Created by PhpStorm.
 * User: Степан
 * Date: 30.03.2018
 * Time: 16:29
 */

namespace frontend\components;
use frontend\components\StorageInterface;
use yii\web\UploadedFile;
use yii\base\Component;
use yii\helpers\FileHelper;
use Yii;

class Storage extends Component implements StorageInterface {

    private $fileName;


    public function saveUploadedFile(UploadedFile $file) {

        $path = $this->preparePath($file);

        if($path && $file->saveAs($path)) {
            return $this->fileName;
        }
    }

    public function preparePath(UploadedFile $file) {

        $this->fileName = $this->getFileName($file);

        $path = $this->getStoragePath(). $this->fileName;

        $path = FileHelper::normalizePath($path);
        if(FileHelper::createDirectory(dirname($path))) {
            return $path;
        }
    }

    public function getFileName(UploadedFile $file) {

        $hash = sha1_file($file->tempName);
        $name = substr_replace($hash, "/", 2, 0);
        $name = substr_replace($name, "/", 5, 0);

        return $name .".".$file->extension;
    }

    public function getStoragePath() {
        return Yii::getAlias(Yii::$app->params['storagePath']);
    }

    public function getFile($filename) {
        return Yii::getAlias(Yii::$app->params['storageUri']) . $filename;
    }
}