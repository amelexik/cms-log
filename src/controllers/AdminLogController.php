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
use yii\base\Exception;
use yii\filters\AccessControl;
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


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'         => true,
                        'matchCallback' => function ($rule, $action) {
                            if ($action->id == 'index') {
                                //Creating and Assigning Privileges for the Root User
                                return $this->isAllow;
                            } else {
                                echo json_encode(['success' => false, 'message' => \Yii::t('skeeks/logCms', 'In this module allow only action INDEX')]);
                                \Yii::$app->end();
                            }

                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $models = [];
        if ($m = \Yii::$app->logCms->getLogModels()) {
            foreach ($m as $value) {
                $models[$value] = $value;
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
                            'content_id'     => [
                                'field' => [
                                    'class' => SelectField::class,
                                    'items' => \Yii::$app->logCms->getCmsContent()
                                ],
                            ],
                            'user_name'      => [
                                'field' => [
                                    'class' => SelectField::class,
                                    'items' => \Yii::$app->logCms->getUsers()
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
                        'content_id',
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
                                'class'  => \yii\grid\DataColumn::class,
                                'label'  => \Yii::t('skeeks/logCms', 'Event'),
                                'value'  => function (Log $model) {
                                    return \Yii::$app->logCms->getEvents()[$model->operation_type];
                                },
                                'format' => 'raw',

                            ],
                        'content_id'     =>
                            [
                                'class'  => \yii\grid\DataColumn::class,
                                'label'  => \Yii::t('skeeks/logCms', 'Content'),
                                'value'  => function (Log $model) {
                                    return isset(\Yii::$app->logCms->getCmsContent()[$model->content_id])
                                        ? \Yii::$app->logCms->getCmsContent()[$model->content_id]
                                        : '—';
                                },
                                'format' => 'raw',

                            ]
                    ],
                ],
            ]
        ]);
    }

    public function beforeAction($action)
    {
        //if(!in_array($action->id,['index']))

        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }
}

