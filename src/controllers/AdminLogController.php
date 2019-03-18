<?php
/**
 * Created by PhpStorm.
 * User: amelexik
 * Date: 23.11.18
 * Time: 16:24
 */

namespace skeeks\modules\cms\logCms\controllers;

use skeeks\cms\backend\controllers\BackendModelStandartController;
use skeeks\cms\grid\DateTimeColumnData;
use skeeks\cms\models\CmsContent;
use skeeks\modules\cms\logCms\models\Log;
use skeeks\yii2\form\fields\SelectField;
use yii\helpers\ArrayHelper;

class AdminLogController extends BackendModelStandartController
{

    public function init()
    {
        $this->name = \Yii::t('skeeks/logCms', "Cms changelog");
        $this->modelShowAttribute = "id";
        $this->modelClassName = Log::className();
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $models = [];
        if($m = \Yii::$app->logCms->getLogModels()){
            foreach ($m as $value){
                $models[$value]=$value;
            }
        }
        return ArrayHelper::merge(parent::actions(), [
            'index' => [
                "filters" => [
                    'visibleFilters' => [
                        'model_class',
                    ],

                    'filtersModel' => [
                        'fields' => [
                            'model_class'    => [
                                'field' => [
                                    'class' => SelectField::class,
                                    'items' => $models
                                ],
                            ],
                            'operation_type' => [
                                'field' => [
                                    'class' => SelectField::class,
                                    'items' => \Yii::$app->logCms->getEvents()
                                ],
                            ],
                        ],
                    ],

                ],
                'grid'    => [
                    'defaultOrder'   => [
                        'created_at' => SORT_DESC,
                    ],
                    'visibleColumns' => [
                        'pk',
                        'name',
                        'model_class',
                        'operation_type',
                        'created_at',
                        'user_name',
                        'user_ip',
                    ],
                    'columns'        => [
                        'created_at'     =>
                            [
                                'class' => DateTimeColumnData::class,
                            ],
                        'operation_type' =>
                            [
                                'class'         => \yii\grid\DataColumn::class,
                                'label'         => \Yii::t('skeeks/logCms', 'Event'),
                                'headerOptions' => ['style' => 'width:30%'],
                                'value'         => function (Log $model) {
                                    return \Yii::$app->logCms->getEvents()[$model->operation_type];
                                },
                                'format'        => 'raw',

                            ]
                    ],
                ],
            ]
        ]);
    }
}

