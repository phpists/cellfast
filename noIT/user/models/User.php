<?php

namespace noIT\user\models;

use Yii;
use yii\rbac\Role;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * User model
 *
 * @property integer $id
 * @property string $email
 * @property string $name
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $role_object
 * @property boolean $isAdmin
 *
 * @property Role[] $roles
 */

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    private $_roles;

    /**
     * @inheritdoc
     */
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
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['email'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['email', 'name', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['roles'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
            'name' => Yii::t('app', 'Username'),
            'status' => Yii::t('app', 'Status'),
            'roles' => Yii::t('app', 'Roles'),
        ];
    }

    public function getUserName() {
        return $this->name ? : $this->email;
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
     * Finds user by email
     *
     * @param string $name
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by name
     *
     * @param string $name
     * @param array|string $searchFields
     * @return static|null
     */
    public static function findByUsername($name, $searchFields = ['email', 'username'])
    {
        if (!is_array($searchFields)) {
            $searchFields = [$searchFields];
        }

        $condition = [];
        foreach ($searchFields as $field) {
            $condition[$field] = $name;
        }
        $condition['status'] = self::STATUS_ACTIVE;

        return static::findOne($condition);
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

    public static function isPasswordResetTokenValid($token)
    {

        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function setRoles($value) {
        $this->_roles = $value;
    }

    public function getRoles() {
        return Yii::$app->authManager->getRolesByUser($this->id);
    }

    public function hasRole($role) {
        foreach ($this->roles as $_role) {
            if ($_role->name == $role) {
                return true;
            }
        }
        return false;
    }

    public function getIsAdmin() {
        return $this->hasRole('admin');
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendResetPasswordEmail()
    {

        if (!User::isPasswordResetTokenValid($this->password_reset_token)) {
            $this->generatePasswordResetToken();
        }

        if (!$this->save()) {
            return false;
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $this]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject('Установление пароля | ' . Yii::$app->name)
            ->send();
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ( $this->_roles !== null && is_array($this->_roles) ) {
            $this->_updateRoles();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    protected function _updateRoles() {
        $_to_change = [];
        foreach ($this->_roles as $_role) {
            if ( ($userRole = Yii::$app->authManager->getRole($this->_role)) && !$this->hasRole($_role) ) {
                $_to_change[] = $userRole;
            }
        }
        if ($_to_change) {
            Yii::$app->authManager->revokeAll($this->id);
            foreach ($_to_change as $_userRole) {
                Yii::$app->authManager->assign($_userRole, $this->id);
            }
        }
        return true;
    }

    static public function findByRole($role) {
        return static::find()
            ->where(['id' => Yii::$app->authManager->getUserIdsByRole($role), 'status' => self::STATUS_ACTIVE])
            ->all();
    }
}
