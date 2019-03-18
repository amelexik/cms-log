<?php

namespace skeeks\modules\cms\logCms\components;

use skeeks\cms\base\Component;
use skeeks\cms\models\CmsContent;
use skeeks\cms\models\CmsContentElement;
use skeeks\cms\models\CmsUser;
use skeeks\cms\models\Tree;
use skeeks\modules\cms\logCms\models\Log;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class LogComponent
 * @package skeeks\modules\cms\logCms\components
 */
class LogComponent extends Component
{
    /**
     *
     */
    const EVENT_INSERT = 1;
    /**
     *
     */
    const EVENT_UPDATE = 2;
    /**
     *
     */
    const EVENT_DELETE = 3;

    /**
     * @var array
     */
    public $defaultModels = [
        Tree::class,
        CmsContentElement::class
    ];
    /**
     * @var
     */
    public $availableModels;

    /**
     * @var
     */
    private $_cmsContent;
    /**
     * @var
     */
    private $_users;

    /**
     * Можно задать название и описание компонента
     * @return array
     */
    static public function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name' => \Yii::t('skeeks/logCms', 'Cms changelog'),
        ]);
    }

    /**
     * @var array
     */
    public $relatedElementContentIds = [];

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['availableModels'], 'safe'],
        ]);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'availableModels' => \Yii::t('skeeks/logCms', 'Available Model list'),
        ]);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        return ArrayHelper::merge(parent::attributeHints(), [
        ]);
    }


    /**
     * @param ActiveForm $form
     * @return string|void
     */
    public function renderConfigForm(ActiveForm $form)
    {
        echo $form->fieldSet(\Yii::t('skeeks/logCms', 'Setting'));
        echo $form->field($this, 'availableModels')->textarea(['rows' => 20]);
        echo $form->fieldSetEnd();
    }


    /**
     * @return array
     */
    public function getLogModels()
    {
        $models = [];
        if (!empty($this->availableModels)) {
            if ($list = explode("\r\n", $this->availableModels)) {
                $models = array_filter($list);
            }
        }

        return array_merge($this->defaultModels, $models);
    }

    /**
     * @return array
     */
    public function getCmsContent()
    {
        if (!$this->_cmsContent) {
            $this->_cmsContent = ArrayHelper::map(CmsContent::find()->all(), 'id', 'name');
        }
        return $this->_cmsContent;
    }

    /**
     * @return array
     */
    public function getUsers()
    {
        if (!$this->_users) {
            $this->_users = ArrayHelper::map(Log::find()->distinct('user_name')->all(), 'user_name', 'user_name');
        }
        return $this->_users;
    }

    /**
     * @return array
     */
    public function getEvents()
    {
        return [
            self::EVENT_INSERT => \Yii::t('skeeks/logCms', 'Insert'),
            self::EVENT_UPDATE => \Yii::t('skeeks/logCms', 'Update'),
            self::EVENT_DELETE => \Yii::t('skeeks/logCms', 'Delete'),
        ];
    }

}