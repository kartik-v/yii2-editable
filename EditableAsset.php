<?php
/**
 * @package   yii2-editable
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2016
 * @version   1.7.5
 */

namespace kartik\editable;

use Yii;
use kartik\base\AssetBundle;

/**
 * Asset bundle for Editable widget.
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
        $this->setSourcePath('@vendor/kartik-v/yii2-editable/assets');
        $this->setupAssets('css', ['css/editable']);
        $this->setupAssets('js', ['js/editable']);
        parent::init();
    }
}