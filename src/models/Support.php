<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\support\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yuncms\db\ActiveRecord;
use yuncms\support\notifications\SupportNotification;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%support}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $model_id
 * @property string $model_class
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property ActiveRecord $source
 *
 */
class Support extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%support}}';
    }

    /**
     * 定义行为
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id'], 'required'],
            [['user_id', 'model_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms/support', 'ID'),
            'user_id' => Yii::t('yuncms/support', 'User ID'),
            'model_id' => Yii::t('yuncms/support', 'Model ID'),
            'model_class' => Yii::t('yuncms/support', 'Model Class'),
            'created_at' => Yii::t('yuncms/support', 'Created At'),
            'updated_at' => Yii::t('yuncms/support', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne($this->model_class, ['id' => 'model_id']);
    }

    /**
     * 获取源标题
     * @return string
     */
    public function getSourceTitle()
    {
        return null;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $this->source->updateCountersAsync(['supports' => 1]);
            $this->sendNotice();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * 发送通知
     */
    public function sendNotice()
    {
        try {
            Yii::$app->notification->send($this->source->user, new SupportNotification([
                'data' => [
                    'nickname' => $this->user->nickname,
                    'entity' => $this->getSourceTitle(),
                    'entity_class' => self::class,
                    'source' => $this->source->toArray(),
                    'source_id' => $this->model_id,
                    'source_class' => $this->model_class,
                ]
            ]));
        } catch (InvalidConfigException $e) {
            Yii::error($e->getMessage(), __METHOD__);
        }
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        $this->source->updateCountersAsync(['supports' => -1]);
        parent::afterDelete();
    }

    /**
     * @inheritdoc
     * @return SupportQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SupportQuery(get_called_class());
    }
}
