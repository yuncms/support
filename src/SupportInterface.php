<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\support;

/**
 * Interface SupportInterface
 * @property \yuncms\user\models\User $user
 * @package yuncms\support
 */
interface SupportInterface
{
    /**
     * 获取标题
     * @return string
     */
    public function getTitle();

    /**
     * @return \yuncms\user\models\User
     */
    public function getUser();

}