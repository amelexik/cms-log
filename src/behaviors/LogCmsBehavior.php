<?php

namespace skeeks\modules\cms\logCms\behaviors;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * Created by PhpStorm.
 * User: amelexik
 * Date: 14.03.19
 * Time: 17:01
 */
Class LogCmsBehavior extends \yii\base\Behavior
{
    public function attach($owner)
    {
        parent::attach($owner); // TODO: Change the autogenerated stub
        //dump('attach');
        echo 1;
    }

    public function events() {
        return [
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate'
        ];
    }

    public function afterUpdate($event) {
        \Yii::info('TEEEEEEEST');
        \Yii::info($event->changedAttributes);
    }
}