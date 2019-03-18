<?php

namespace skeeks\modules\cms\logCms\components;

use skeeks\cms\base\Component;
use skeeks\cms\models\CmsContent;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

class LogComponent extends Component
{
    const EVENT_INSERT = 1;
    const EVENT_UPDATE = 2;
    const EVENT_DELETE = 3;
    public $availableModels;

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

    public $relatedElementContentIds = [];

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['availableModels'], 'safe'],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'availableModels' => \Yii::t('skeeks/logCms', 'Available Model list'),
        ]);
    }

    public function attributeHints()
    {
        return ArrayHelper::merge(parent::attributeHints(), [
        ]);
    }


    public function renderConfigForm(ActiveForm $form)
    {
        echo $form->fieldSet(\Yii::t('skeeks/logCms', 'Setting'));
        echo $form->field($this, 'availableModels')->textarea(['rows' => 20]);
        echo $form->fieldSetEnd();
    }


    public function getLogModels()
    {
        if (!empty($this->availableModels)) {
            if ($list = explode("\r\n", $this->availableModels)) {
                $list = array_filter($list);
                return $list;
            }
        }
        return [];
    }

    public function getEvents()
    {
        return [
            self::EVENT_INSERT => \Yii::t('skeeks/logCms', 'Insert'),
            self::EVENT_UPDATE => \Yii::t('skeeks/logCms', 'Update'),
            self::EVENT_DELETE => \Yii::t('skeeks/logCms', 'Delete'),
        ];
    }

}