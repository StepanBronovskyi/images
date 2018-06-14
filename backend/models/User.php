<?php
namespace backend\models;

use frontend\models\Feed;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const ROLE_ADMIN = 'admin';
    const ROLE_MODERATOR = 'moderator';

    public $roles;
    /**
     * @inheritdoc
     */

    public function __construct() {
        $this->on(self::EVENT_AFTER_UPDATE, [$this, 'updateRoles']);
    }

    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['roles'], 'safe'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    public static function getUserList() {
        return self::find()->all();
    }

    public static function getUserByNickname($nickname) {
        return self::find()->where(['nickname' => $nickname])->one();
    }

    public static function getUserById($id) {
        return self::find()->where(['id' => $id])->one();
    }

    public static function getUserByEmail($email) {
        return self::find()->where(['email' => $email])->one();
    }

    public static final function checkPassword($password) {
        if(self::find()->where(['password_hash' => Yii::$app->security->generatePasswordHash($password)])->one()) {
            return true;
        }
        return false;
    }


    public static final function checkEmailExist($email) {
        if(self::find()->where(['email' => $email])->one()) {
            return false;
        }
        return true;
    }

    public static function checkNicknameExist($nickname) {
        if(self::find()->where(['nickname' => $nickname])->one()) {
            return false;
        }
        return true;
    }

    public function getSubscriptions() {
        $subscr_str = $this->hasOne(Subscription::className(), ['user_id' => 'id'])->one()->subscriptions;
        $subscr_arr = explode(",", $subscr_str);
        return $this::find()->where(['id' => $subscr_arr])->all();
    }

    public function getFollowers() {
        $foll_str = $this::hasOne(Follower::className(), ['user_id' => 'id'])->one()->followers;
        $foll_arr = explode(",", $foll_str);
        return $this::find()->where(['id' => $foll_arr])->all();
    }

    public function getSubscriptionsCount() {
        return count(self::getSubscriptions());
    }

    public function getFollowersCount() {
        return count(self::getFollowers());
    }

    public function isSubscribe() {
        $subscr_str = $this->hasOne(Follower::className(), ['user_id' => 'id'])->one()->followers;
        $subscr_arr = explode(",", $subscr_str);
        $current_user_id = Yii::$app->user->identity->id;
        return in_array($current_user_id, $subscr_arr);
    }

    public function getPicture() {
        if($this->picture) {
            return Yii::$app->storage->getFile($this->picture);
        }
        else {
            return Yii::$app->storage->getFile("no-picture.jpg");
        }
    }

    public function getFeed() {
        return $this::hasMany(Feed::className(), ['user_id' => 'id'])->orderBy(['post_created_at' => SORT_DESC])->limit(10)->all();
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }


    public function getRolesList() {
        return [
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_MODERATOR => 'Moderator',
        ];
    }

    public function updateRoles() {
        Yii::$app->authManager->revokeAll($this->id);

        if(is_array($this->roles)) {
            foreach ($this->roles as $rolesName) {
                if($role = Yii::$app->authManager->getRole($rolesName)) {
                    Yii::$app->authManager->assign($role, $this->id);
                }
            }
        }
    }

    public function afterFind() {
        $this->roles = $this->findRoles();
    }

    public function findRoles() {
        $roles = Yii::$app->authManager->getRolesByUser($this->id);
        return ArrayHelper::getColumn($roles, 'name', false);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
