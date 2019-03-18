<?php

namespace skeeks\modules\cms\logCms\models;

use skeeks\cms\models\CmsUser;
use Yii;

/**
 * This is the model class for table "{{%cms_log}}".
 *
 * @property int $id
 * @property string $model_class
 * @property string $pk
 * @property int $operation_type 1-insert, 2-update, 3-delete
 * @property int $created_at
 * @property string $name
 * @property int $user_id
 * @property string $user_name
 * @property string $user_ip
 * @property string $user_agent
 * @property string $data
 *
 * @property CmsUser $user
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cms_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_class', 'pk', 'operation_type', 'created_at', 'name', 'user_name'], 'required'],
            [['operation_type', 'created_at', 'user_id'], 'integer'],
            [['data'], 'string'],
            [['model_class'], 'string', 'max' => 500],
            [['pk', 'user_name'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 150],
            [['user_ip'], 'string', 'max' => 50],
            [['user_agent'], 'string', 'max' => 300],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => CmsUser::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'             => 'ID',
            'model_class'    => \Yii::t('skeeks/logCms', 'Model Class'),
            'pk'             => \Yii::t('skeeks/logCms', 'Model Pk'),
            'operation_type' => \Yii::t('skeeks/logCms', 'Operation Type'),
            'created_at'     => \Yii::t('skeeks/logCms', 'Time'),
            'name'           => \Yii::t('skeeks/logCms', 'Name'),
            'user_id'        => \Yii::t('skeeks/logCms', 'User Id'),
            'user_name'      => \Yii::t('skeeks/logCms', 'Username'),
            'user_ip'        => \Yii::t('skeeks/logCms', 'Ip'),
            'user_agent'     => \Yii::t('skeeks/logCms', 'User Agent'),
            'data'           => \Yii::t('skeeks/logCms', 'Additional Data'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(CmsUser::className(), ['id' => 'user_id']);
    }
}
