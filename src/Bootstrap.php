<?php
/**
 * Created by PhpStorm.
 * User: amelexik
 * Date: 15.03.19
 * Time: 11:57
 */

namespace skeeks\modules\cms\logCms;

use skeeks\cms\models\CmsTree;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\ActiveRecord;

class Bootstrap implements BootstrapInterface
{
    const INSERT = 1;
    const UPDATE = 2;
    const DELETE = 3;

    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_INSERT, function ($event) {
            $this->insertHistory($event->sender, self::INSERT, $event);
        });
        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_UPDATE, function ($event) {
            $this->insertHistory($event->sender, self::UPDATE, $event);
        });
        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_DELETE, function ($event) {
            $this->insertHistory($event->sender, self::DELETE, $event);
        });
    }

    /**
     * @param $model ActiveRecord
     * @param $type
     * @throws \yii\db\Exception
     */
    private function insertHistory($model, $type, $event = null)
    {
        if (!in_array($model::className(), Yii::$app->logCms->getLogModels()))
            return;

        $pk = is_array($model->getPrimaryKey())
            ? json_encode($model->getPrimaryKey()) // if pk is composite
            : $model->getPrimaryKey();

        if (!$pk) return;


        $name = isset($model->name) ? $model->name : $pk;
        $content_id = isset($model->content_id) ? $model->content_id : null;


        Yii::$app->db->createCommand()
            ->insert('{{%cms_log}}', [
                'model_class'    => $model::className(),
                'pk'             => $pk,
                'content_id'     => $content_id,
                'operation_type' => $type,
                'created_at'     => time(),
                'name'           => $name,
                'user_id'        => Yii::$app->has('user') ? Yii::$app->get('user')->id : null,
                'user_name'      => Yii::$app->has('user') ? Yii::$app->get('user')->identity->username : '',
                'user_ip'        => Yii::$app->has('request') ? Yii::$app->request->userIP : null,
                'user_agent'     => Yii::$app->has('request') ? Yii::$app->request->userAgent : null,
            ])
            ->execute();
    }
}