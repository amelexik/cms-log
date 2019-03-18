<?php
/**
 * Module
 **/

namespace skeeks\modules\cms\logCms;


/**
 * Class Module
 * @package skeeks\offer
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'skeeks\modules\cms\logCms\controllers';

    /**
     * @return array
     */
    protected function _descriptor()
    {
        return array_merge(parent::_descriptor(), [
            "name"        => \Yii::t('skeeks/logCms', 'Cms changelog'),
            "description" => \Yii::t('skeeks/logCms', 'Cms changelog protocol'),
        ]);
    }

}