<?php

/**
 * @package   yii2-editable
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015
 * @version   1.7.2
 */

namespace kartik\editable;

use Yii;
use yii\web\View;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use kartik\base\Config;
use kartik\base\InputWidget;
use kartik\popover\PopoverX;

/**
 * An extended editable widget for Yii Framework 2.
 *
 * @see http://github.com/kartik-v/yii2-editable
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class Editable extends InputWidget
{
    /**
     * Editable display formats
     */
    const FORMAT_LINK = 'link';
    const FORMAT_BUTTON = 'button';

    /**
     * Edit input types
     */
    // input types
    const INPUT_HIDDEN = 'hiddenInput';
    const INPUT_TEXT = 'textInput';
    const INPUT_PASSWORD = 'passwordInput';
    const INPUT_TEXTAREA = 'textArea';
    const INPUT_CHECKBOX = 'checkbox';
    const INPUT_RADIO = 'radio';
    const INPUT_LIST_BOX = 'listBox';
    const INPUT_DROPDOWN_LIST = 'dropDownList';
    const INPUT_CHECKBOX_LIST = 'checkboxList';
    const INPUT_RADIO_LIST = 'radioList';
    const INPUT_FILE = 'fileInput';
    const INPUT_HTML5_INPUT = 'input';
    const INPUT_WIDGET = 'widget';

    // input widget classes
    const INPUT_DEPDROP = '\kartik\depdrop\DepDrop';
    const INPUT_SELECT2 = '\kartik\select2\Select2';
    const INPUT_TYPEAHEAD = '\kartik\typeahead\Typeahead';
    const INPUT_SWITCH = '\kartik\switchinput\SwitchInput';
    const INPUT_SPIN = '\kartik\touchspin\TouchSpin';
    const INPUT_DATE = '\kartik\date\DatePicker';
    const INPUT_TIME = '\kartik\time\TimePicker';
    const INPUT_DATETIME = '\kartik\datetime\DateTimePicker';
    const INPUT_DATE_RANGE = '\kartik\daterange\DateRangePicker';
    const INPUT_SORTABLE = '\kartik\sortinput\SortableInput';
    const INPUT_RANGE = '\kartik\range\RangeInput';
    const INPUT_COLOR = '\kartik\color\ColorInput';
    const INPUT_RATING = '\kartik\rating\StarRating';
    const INPUT_FILEINPUT = '\kartik\file\FileInput';
    const INPUT_SLIDER = '\kartik\slider\Slider';
    const INPUT_MONEY = '\kartik\money\MaskMoney';
    const INPUT_CHECKBOX_X = '\kartik\checkbox\CheckboxX';

    const CSS_PARENT = "kv-editable-parent form-group";

    /**
     * @var string the identifier for the PJAX widget container if the editable
     * widget is to be rendered inside a PJAX container. This will ensure the
     * PopoverX plugin is initialized correctly after a PJAX request is completed.
     * If this is not set, no re-initialization will be done for pjax.
     */
    public $pjaxContainerId;

    /**
     * @var string the display format for the editable. Accepts one of the following values.
     * - 'link' or [[Editable::FORMAT_LINK]]: will convert the text to a clickable editable link.
     * - 'button' or [[Editable::FORMAT_BUTTON]]: will display a separate button beside the text.
     * Defaults to [[Editable::FORMAT_LINK]] if you do not set it as [[Editable::FORMAT_BUTTON]].
     */
    public $format = self::FORMAT_LINK;

    /**
     * @var array the HTML attributes for the editable button to be displayed when the format
     * has been set to 'button':
     * - label: string, the editable button label. This is not HTML encoded.
     *   Defaults to <i class="glyphicon glyphicon-pencil"></i>
     */
    public $editableButtonOptions = ['class' => 'btn btn-sm btn-default'];

    /**
     * @var array the HTML attributes for the editable value displayed
     */
    public $editableValueOptions = [];

    /**
     * @var array the HTML attributes for the editable container
     */
    public $containerOptions = [];

    /**
     * @var array the HTML attributes for the input container
     * applicable only when not using with ActiveForm
     */
    public $inputContainerOptions = [];

    /**
     * @var string the popover contextual type. Must be one of the [[PopoverX::TYPE]] constants
     * Defaults to `PopoverX::TYPE_DEFAULT` or `default`.
     */
    public $type = PopoverX::TYPE_DEFAULT;

    /**
     * @var string the size of the popover window. One of the [[PopoverX::SIZE]] constants
     */
    public $size;

    /**
     * @var string the popover placement. Must be one of the [[PopoverX::ALIGN]] constants
     * Defaults to `PopoverX::ALIGN_RIGHT` or `right`.
     */
    public $placement = PopoverX::ALIGN_RIGHT;

    /**
     * @var string the header content placed before the header text in the popover dialog.
     * This defaults to `<i class="glyphicon glyphicon-edit"></i> Edit`;
     */
    public $preHeader;

    /**
     * @var string the header content in the popover dialog. If not set, this
     * will be auto generated based on the attribute label or set to null.
     */
    public $header;

    /**
     * @var string the footer content in the popover dialog. The following special
     * tags/variables will be parsed and replaced in the footer:
     * {buttons} - string, will be replaced with the submit and reset button.
     * If this is set to null or an empty string, it will not be displayed.
     */
    public $footer = '{buttons}';

    /**
     * @var string the value to be displayed. If not set, this will default to the
     * attribute value. If the attribute value is null, then this will display the
     * value as set in [[valueIfNull]].
     */
    public $displayValue;

    /**
     * @var array the configuration to auto-calculate display value, based on the
     * value of the editable input. This should be a single dimensional array whose
     * keys must match the input value, and the array values must be the description
     * to be displayed. For example, to display user friendly boolean values, you could
     * configure this as `[0 => 'Inactive', 1 => 'Active']`. If this is set, it will
     * override any value set in `displayValue`.
     */
    public $displayValueConfig = [];

    /**
     * @var string the value to display if the displayValue is null.
     * Defaults to '<em>(not set)</em>'.
     */
    public $valueIfNull;

    /**
     * @var array the HTML attributes for the container enclosing the main content
     * in the popover dialog.
     */
    public $contentOptions = [];

    /**
     * @var array the class for the ActiveForm widget to be used. The class must
     * extend from `\yii\widgets\ActiveForm`. This defaults to `\kartik\widgets\ActiveForm`.
     */
    public $formClass = '\yii\widgets\ActiveForm';

    /**
     * @var array the options for the ActiveForm widget class selected in `formClass`.
     */
    public $formOptions = [];

    /**
     * @var array the input type to render for the editing the input in the editable form.
     * This must be one of the [[Editable::TYPE]] constants.
     */
    public $inputType = self::INPUT_TEXT;

    /**
     * @var string any custom widget class to use. Will only be used if the `inputType` is
     * set to [[Editable::INPUT_WIDGET]]
     */
    public $widgetClass;
    
    /**
     * @var boolean whether to auto guess the input type and set the editable input options. When 
     * this is set to `true` the Editable widget will automatically detect the core input options
     * for common input types (including HTML input types, krajee widgets and DateControl) and append 
     * the `kv-editable-input` class to the input. You can set this to false to set the `kv-editable-input`
     * class manually for any other custom/third party widgets. You would do this typically when you set 
     * inputType to `Editable::INPUT_WIDGET`. Defaults to `true`.
     */
    public $autoGuessInput = true;

    /**
     * @var boolean additional ajax settings to pass to the plugin.
     * @see http://api.jquery.com/jquery
     */
    public $ajaxSettings = [];

    /**
     * @var boolean whether to display any ajax processing errors. Defaults to `true`.
     */
    public $showAjaxErrors = true;

    /**
     * @var boolean whether to auto submit/save the form on pressing ENTER key. Defaults to `true`.
     */
    public $submitOnEnter = true;

    /**
     * @var array the options for the input. If the inputType is one of the HTML inputs, this will
     * capture the HTML attributes. If the `inputType` is set to [[Editable::INPUT_WIDGET]]
     * or set to an input widget from the `\kartik\` namespace, then this will capture the widget
     * options.
     */
    public $options = [];

    /**
     * @var array the ActiveField configuration, if you are using with `model`.
     */
    public $inputFieldConfig = [];

    /**
     * @var string|Closure the content to be placed before the rendered input. If not set as a string,
     * this can be passed as a callback function of the following signature:
     * ```
     * function ($form, $widget) {
     *    // echo $form->field($widget->model, 'attrib');
     * }
     * ```
     * where:
     * - $form mixed is the active form instance for the editable form
     * - $widget mixed is the current editable widget instance
     */
    public $beforeInput;

    /**
     * @var string|Closure the content to be placed after the rendered input. If not set as a string,
     * this can be passed as a callback function of the following signature:
     * ```
     * function ($form, $widget) {
     *    // echo $form->field($widget->model, 'attrib');
     * }
     * ```
     * where:
     * - $form mixed is the active form instance for the editable form
     * - $widget mixed is the current editable widget instance
     */
    public $afterInput;

    /**
     * @var boolean whether you wish to automatically display the form submit and reset buttons.
     * Defaults to `true`.
     */
    public $showButtons = true;

    /**
     * @var array the HTML attributes for the form submit button. The following special property
     * is recognized:
     * - label: string, the label of the button. Defaults to `<i class="glyphicon glyphicon-ok"></i> `.
     */
    public $submitButton = ['class' => 'btn btn-sm btn-primary'];

    /**
     * @var array the HTML attributes for the form reset button. The following special property
     * is recognized:
     * - label: string, the label of the button. Defaults to `<i class="glyphicon glyphicon-ban-circle"></i> `.
     */
    public $resetButton = ['class' => 'btn btn-sm btn-default'];

    /**
     * @var array the generated configuration for the `kartik\popover\PopoverX` widget.
     */
    protected $_popoverOptions = [];

    /**
     * @var array the HTML attributes or options for the input/widget
     */
    protected $_inputOptions = [];

    /**
     * @var \yii\widgets\ActiveForm instance
     */
    protected $_form;

    /**
     * Initializes the widget
     *
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->_msgCat = 'kveditable';
        parent::init();
        if (empty($this->inputType)) {
            throw new InvalidConfigException("The 'type' of editable input must be set.");
        }
        if (!Config::isValidInput($this->inputType)) {
            throw new InvalidConfigException("Invalid input type '{$this->inputType}'.");
        }
        if ($this->inputType === self::INPUT_WIDGET && empty($this->widgetClass)) {
            throw new InvalidConfigException("The 'widgetClass' must be set when the 'inputType' is set to 'widget'.");
        }
        if (Config::isDropdownInput($this->inputType) && !isset($this->data)) {
            throw new InvalidConfigException("You must set the 'data' property for '{$this->inputType}'.");
        }
        Config::validateInputWidget($this->inputType);
        $this->initI18N(__DIR__);
        $this->initOptions();
        $this->_popoverOptions['options']['id'] = $this->options['id'] . '-popover';
        $this->_popoverOptions['toggleButton']['id'] = $this->options['id'] . '-targ';
        $this->registerAssets();
        echo Html::beginTag('div', $this->containerOptions);
        if ($this->format == self::FORMAT_BUTTON) {
            echo Html::tag('div', $this->displayValue, $this->editableValueOptions);
        }

        PopoverX::begin($this->_popoverOptions);

        if (!empty($this->formClass) && !class_exists($this->formClass)) {
            throw new InvalidConfigException("The form class '{$this->formClass}' does not exist.");
        }
        $class = $this->formClass;
        echo Html::beginTag('div', $this->contentOptions);
        $this->_form = $class::begin($this->formOptions);
        if (!$this->_form instanceof \yii\widgets\ActiveForm) {
            throw new InvalidConfigException("The form class '{$class}' MUST extend from \yii\widgets\ActiveForm.");
        }

    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various widget options.
     *
     * @throws InvalidConfigException
     */
    protected function initOptions()
    {
        Html::addCssClass($this->inputContainerOptions, self::CSS_PARENT);
        if ($this->hasModel()) {
            $options = ArrayHelper::getValue($this->inputFieldConfig, 'options', []);
            Html::addCssClass($options, self::CSS_PARENT);
            $this->inputFieldConfig['options'] = $options;
        }
        if (!Config::isHtmlInput($this->inputType) && $this->autoGuessInput) {
            if ($this->widgetClass === 'kartik\datecontrol\DateControl') {
                $options = ArrayHelper::getValue($this->options, 'options.options', []);
                Html::addCssClass($options, 'kv-editable-input');
                $this->options['options']['options'] = $options;
            } elseif ($this->inputType !== self::INPUT_WIDGET) {
                $options = ArrayHelper::getValue($this->options, 'options', []);
                Html::addCssClass($options, 'kv-editable-input');
                $this->options['options'] = $options;
            }
        } else {
            $css = empty($this->options['class']) ? ' form-control' : '';
            Html::addCssClass($this->options, 'kv-editable-input' . $css);
        }
        $this->_inputOptions = $this->options;
        $this->containerOptions['id'] = $this->options['id'] . '-cont';
        $value = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
        if ($value === null && !empty($this->valueIfNull)) {
            $value = $this->valueIfNull;
        }
        if (!isset($this->displayValue)) {
            $this->displayValue = $value;
        }
        if ($this->valueIfNull === null || $this->valueIfNull === '') {
            $this->valueIfNull = '<em>' . Yii::t('kveditable', '(not set)') . '</em>';
        }
        if ($this->displayValue === null || $this->displayValue === '') {
            $this->displayValue = $this->valueIfNull;
        }
        $hasDisplayConfig = is_array($this->displayValueConfig) && !empty($this->displayValueConfig);
        if ($hasDisplayConfig && (is_array($this->value) || is_object($this->value))) {
            throw new InvalidConfigException("Your editable value cannot be an array or object for parsing with 'displayValueConfig'. The array keys in 'displayValueConfig' must be a simple string or number. For advanced display value calculations, you must use your controller AJAX action to return 'output' as a JSON encoded response which will be used as a display value.");
        }
        if ($hasDisplayConfig && !empty($this->displayValueConfig[$value])) {
            $this->displayValue = $this->displayValueConfig[$value];
        }
        Html::addCssClass($this->containerOptions, 'kv-editable');
        Html::addCssClass($this->contentOptions, 'kv-editable-content');
        Html::addCssClass($this->formOptions['options'], 'kv-editable-form');
        if ($this->format == self::FORMAT_BUTTON) {
            Html::addCssClass($this->editableButtonOptions, 'kv-editable-button');
            Html::addCssClass($this->editableValueOptions, 'kv-editable-value');
        } else {
            Html::addCssClass($this->editableValueOptions, 'kv-editable-value kv-editable-link');
        }
        $this->_popoverOptions['type'] = $this->type;
        $this->_popoverOptions['placement'] = $this->placement;
        $this->_popoverOptions['size'] = $this->size;
        if (!isset($this->preHeader)) {
            $this->preHeader = '<i class="glyphicon glyphicon-edit"></i> ' . Yii::t('kveditable', 'Edit') . ' ';
        }
        if ($this->header == null) {
            $attribute = $this->attribute;
            if (strpos($attribute, ']') > 0) {
                $tags = explode(']', $attribute);
                $attribute = array_pop($tags);
            }
            $this->_popoverOptions['header'] = $this->preHeader .
                ($this->hasModel() ? $this->model->getAttributeLabel($attribute) : '');
        } else {
            $this->_popoverOptions['header'] = $this->preHeader . $this->header;
        }
        $this->_popoverOptions['footer'] = $this->renderFooter();
        $this->_popoverOptions['options']['class'] = 'kv-editable-popover skip-export';
        if ($this->format == self::FORMAT_BUTTON) {
            if (empty($this->editableButtonOptions['label'])) {
                $this->editableButtonOptions['label'] = '<i class="glyphicon glyphicon-pencil"></i>';
            }
            Html::addCssClass($this->editableButtonOptions, 'kv-editable-toggle');
            $this->_popoverOptions['toggleButton'] = $this->editableButtonOptions;
        } else {
            $this->_popoverOptions['toggleButton'] = $this->editableValueOptions;
            $this->_popoverOptions['toggleButton']['label'] = $this->displayValue;
        }
        if (!empty($this->footer)) {
            Html::addCssClass($this->_popoverOptions['options'], 'has-footer');
        }
    }

    /**
     * Generates the popover footer
     *
     * @return string
     */
    protected function renderFooter()
    {
        $submitLabel = ArrayHelper::remove($this->submitButton, 'label',
            '<i class="glyphicon glyphicon-save"></i> ' . Yii::t('kveditable', 'Apply')
        );
        $resetLabel = ArrayHelper::remove($this->resetButton, 'label',
            '<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('kveditable', 'Reset')
        );
        $this->submitButton['type'] = 'button';
        $this->resetButton['type'] = 'button';
        Html::addCssClass($this->submitButton, 'kv-editable-submit');
        Html::addCssClass($this->resetButton, 'kv-editable-reset');
        $buttons = Html::button($submitLabel, $this->submitButton) .
            Html::button($resetLabel, $this->resetButton);
        return Html::tag('div', '&nbsp;', ['class' => 'kv-editable-loading', 'style' => 'display:none;']) .
        strtr($this->footer, [
            '{buttons}' => $buttons
        ]);
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        EditableAsset::register($view);
        $this->pluginOptions = [
            'valueIfNull' => $this->valueIfNull,
            'placement' => $this->placement,
            'target' => $this->format == self::FORMAT_BUTTON ? '.kv-editable-button' : '.kv-editable-link',
            'displayValueConfig' => $this->displayValueConfig,
            'showAjaxErrors' => $this->showAjaxErrors,
            'ajaxSettings' => $this->ajaxSettings,
            'submitOnEnter' => $this->submitOnEnter
        ];
        $this->registerPlugin('editable', 'jQuery("#' . $this->containerOptions['id'] . '")');
        if (!empty($this->pjaxContainerId)) {
            EditablePjaxAsset::register($view);
            $toggleButton = $this->_popoverOptions['toggleButton']['id'];
            $initPjaxVar = 'kvEdPjax_' . str_replace('-', '_', $this->_popoverOptions['options']['id']);
            $view->registerJs("var {$initPjaxVar} = false;", View::POS_HEAD);
            $js = "initEditablePjax('{$this->pjaxContainerId}', '{$toggleButton}', '{$initPjaxVar}');";
            $view->registerJs($js);
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $class = $this->formClass;
        echo $this->renderFormFields();
        $class::end();
        echo "</div>\n"; // content options
        PopoverX::end();
        echo "</div>\n"; // options
    }

    /**
     * Renders the editable form fields
     *
     * @return string
     */
    protected function renderFormFields()
    {
        $out = Html::hiddenInput('hasEditable', 0) . "\n";
        if ($this->beforeInput !== null) {
            if (is_string($this->beforeInput)) {
                $out .= $this->beforeInput . "\n";
            } else {
                $out .= call_user_func($this->beforeInput, $this->_form, $this) . "\n";
            }
        }
        if ($this->inputType === self::INPUT_HTML5_INPUT) {
            $out .= $this->renderHtml5Input() . "\n";
        } elseif ($this->inputType === self::INPUT_WIDGET) {
            $out .= $this->renderWidget($this->widgetClass) . "\n";
        } elseif (Config::isHtmlInput($this->inputType)) {
            $out .= $this->renderInput() . "\n";
        } elseif (Config::isInputWidget($this->inputType)) {
            $out .= $this->renderWidget($this->inputType) . "\n";
        }
        if ($this->afterInput !== null) {
            if (is_string($this->afterInput)) {
                $out .= $this->afterInput . "\n";
            } else {
                $out .= call_user_func($this->afterInput, $this->_form, $this) . "\n";
            }
        }
        return $out;
    }

    /**
     * Renders the HTML 5 input
     *
     * @return string
     */
    protected function renderHtml5Input()
    {
        $type = ArrayHelper::remove($this->_inputOptions, 'type', 'text');
        $field = Html::input($type, $this->name, $this->value, $this->_inputOptions);
        if ($this->hasModel()) {
            if (isset($this->_form)) {
                return $this->_form
                    ->field($this->model, $this->attribute, $this->inputFieldConfig)
                    ->input($type, $this->_inputOptions)
                    ->label(false);
            }
            $field = Html::activeInput($type, $this->name, $this->value, $this->_inputOptions);
        }
        return Html::tag('div', $field, $this->inputContainerOptions);
    }

    /**
     * Renders a widget
     *
     * @return string
     */
    protected function renderWidget($class)
    {
        if ($this->hasModel()) {
            if (isset($this->_form)) {
                return $this->_form
                    ->field($this->model, $this->attribute, $this->inputFieldConfig)
                    ->widget($class, $this->_inputOptions)
                    ->label(false);
            }
            $options = ArrayHelper::merge($this->_inputOptions, [
                'model' => $this->model,
                'attribute' => $this->attribute
            ]);
        } else {
            $options = ArrayHelper::merge($this->_inputOptions, [
                'name' => $this->name,
                'value' => $this->value
            ]);
        }
        $field = $class::widget($options);
        return Html::tag('div', $field, $this->inputContainerOptions);
    }

    /**
     * Renders all other HTML inputs (except HTML5)
     *
     * @return string
     */
    protected function renderInput()
    {
        $list = Config::isDropdownInput($this->inputType);
        $input = $this->inputType;
        if ($this->hasModel()) {
            if (isset($this->_form)) {
                return $list ?
                    $this->_form
                        ->field($this->model, $this->attribute, $this->inputFieldConfig)
                        ->$input($this->data, $this->_inputOptions)
                        ->label(false) :
                    $this->_form
                        ->field($this->model, $this->attribute, $this->inputFieldConfig)
                        ->$input($this->_inputOptions)
                        ->label(false);
            }
            $input = 'active' . ucfirst($this->inputType);
        }
        $checked = false;
        if ($input == 'radio' || $input == 'checkbox') {
            $this->options['value'] = $this->value;
            $checked = ArrayHelper::remove($this->_inputOptions, 'checked', false);
        }
        if ($list) {
            $field = Html::$input($this->name, $this->value, $this->data, $this->_inputOptions);
        } else {
            $field = ($input == 'checkbox' || $input == 'radio') ?
                Html::$input($this->name, $checked, $this->_inputOptions) :
                Html::$input($this->name, $this->value, $this->_inputOptions);
        }
        return Html::tag('div', $field, $this->inputContainerOptions);
    }

    /**
     * Gets the form instance for use at runtime
     *
     * @return \yii\widgets\ActiveForm
     */
    public function getForm()
    {
        return $this->_form;
    }
}