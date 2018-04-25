<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\support\rest\models;

use yuncms\rest\models\User;

/**
 * Class Support
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Support extends \yuncms\support\models\Support
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}