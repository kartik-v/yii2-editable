<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014
 * @package yii2-editable
 * @version 1.4.0
 */

namespace kartik\editable;

use Yii;
use kartik\widgets\AssetBundle;

/**
 * Asset bundle for Pjax processing of Editable widget to reinitialize 
 * bootstrap-popover-x on pjax completion
 *
 * @see http://plugins.krajee.com/popover-x
 * @see http://github.com/kartik-v/bootstrap-popover-x
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class EditablePjaxAsset extends AssetBundle
{
    public function init()
    {
        $this->setSourcePath('@vendor/kartik-v/yii2-editable/assets');
        $this->setupAssets('js', ['js/editable-pjax']);
        parent::init();
    }
}
