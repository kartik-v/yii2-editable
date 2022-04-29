<?php
/**
 * @package   yii2-editable
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2022
 * @version   1.8.0
 */

namespace kartik\editable;

use kartik\base\AssetBundle;

/**
 * Asset bundle for the [[Editable]] widget.
 *
 * @see http://github.com/kartik-v/yii2-editable
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class EditableAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->depends = array_merge($this->depends, ['kartik\popover\PopoverXAsset']);
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/editable']);
        $this->setupAssets('js', ['js/editable']);
        parent::init();
    }
}