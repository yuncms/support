<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\support\notifications;

use Yii;
use yuncms\notifications\messages\CloudPushMessage;
use yuncms\notifications\Notification;

/**
 * 点赞通知
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class SupportNotification extends Notification
{
    /** @var string */
    public $verb = 'support';

    /**
     * 获取标题
     * @return string
     */
    public function getTitle()
    {
        return Yii::t('yuncms/support', 'Support reminder');
    }

    /**
     * 获取消息模板
     * @return string
     */
    public function getTemplate()
    {
        return Yii::t('yuncms/support', '{nickname} support your {entity}');
    }

    /**
     * 该通知被推送的通道
     * @return array
     */
    public function broadcastOn()
    {
        return ['cloudPush', 'database'];
    }

    /**
     * 推送到数据库
     * @return array
     */
    public function toDatabase()
    {
        return [
            'verb' => $this->verb,
            'template' => $this->getTemplate(),
            'data' => $this->getData(),
        ];
    }

    /**
     * 推送到CloudPush 渠道
     * @return CloudPushMessage
     * @throws \yii\base\InvalidConfigException
     */
    public function toCloudPush()
    {
        return Yii::createObject([
            'class' => CloudPushMessage::class,
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'extParameters' => $this->getData()
        ]);
    }
}
