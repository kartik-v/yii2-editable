<?php
/**
 * @package   yii2-editable
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2021
 * @version   1.7.9
 */

namespace kartik\editable;

use Yii;
use kartik\base\AssetBundle;

/**
 * Asset bundle for Pjax processing of the [[Editable]] widget to reinitialize bootstrap-popover-x on pjax completion
 *
 * @see http://plugins.krajee.com/popover-x
 * @see http://github.com/kartik-v/bootstrap-popover-x
 * @see http://github.com/kartik-v/yii2-editable
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class EditablePjaxAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->depends = array_merge($this->depends, ['kartik\editable\EditableAsset']);
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/editable-pjax']);
        parent::init();
    }
}