<?php
/**
 * @package   yii2-editable
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2021
 * @version   1.7.9
 */

namespace kartik\editable;

use Closure;
use Exception;
use kartik\base\Config;
use kartik\base\InputWidget;
use kartik\popover\PopoverX;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

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
     * Editable LINK display format (default)
     */
    const FORMAT_LINK = 'link';
    /**
     * Editable BUTTON display format
     */
    const FORMAT_BUTTON = 'button';
    /**
     * @var string editable prebuilt inline template number 1 for content before
     */
    const INLINE_BEFORE_1 = <<< HTML
<div class="kv-editable-form-inline">
    <div class="form-group">
        {loading}
    </div>
HTML;
    /**
     * Editable prebuilt inline template number 1 for content after
     */
    const INLINE_AFTER_1 = <<< HTML
    <div class="form-group">
        {buttons}{close}
    </div>
</div>
HTML;
    /**
     * Editable prebuilt inline template number 2 for content before
     */
    const INLINE_BEFORE_2 = <<< HTML
<div class="card-header panel-heading">
    {close}
    {header}
</div>
<div class="card-body panel-body">
HTML;
    /**
     * Editable prebuilt inline template number 2 for content after
     */
    const INLINE_AFTER_2 = <<< HTML
</div>
<div class="card-footer panel-footer">
    {loading}{buttons}
</div>
HTML;

    /**
     * Hidden input
     */
    const INPUT_HIDDEN = 'hiddenInput';
    /**
     * Text input
     */
    const INPUT_TEXT = 'textInput';
    /**
     * Text area
     */
    const INPUT_TEXTAREA = 'textArea';
    /**
     * Password input
     */
    const INPUT_PASSWORD = 'passwordInput';
    /**
     * Dropdown list allowing single select
     */
    const INPUT_DROPDOWN_LIST = 'dropDownList';
    /**
     * List box allowing multiple select
     */
    const INPUT_LIST_BOX = 'listBox';
    /**
     * Checkbox input
     */
    const INPUT_CHECKBOX = 'checkbox';
    /**
     * Radio input
     */
    const INPUT_RADIO = 'radio';
    /**
     * Checkbox inputs as a list allowing multiple selection
     */
    const INPUT_CHECKBOX_LIST = 'checkboxList';
    /**
     * Radio inputs as a list
     */
    const INPUT_RADIO_LIST = 'radioList';
    /**
     * Bootstrap styled checkbox button group
     */
    const INPUT_CHECKBOX_BUTTON_GROUP = 'checkboxButtonGroup';
    /**
     * Bootstrap styled radio button group
     */
    const INPUT_RADIO_BUTTON_GROUP = 'radioButtonGroup';
    /**
     * Krajee styled multiselect input that allows formatted checkbox list and radio list
     */
    const INPUT_MULTISELECT = 'multiselect';
    /**
     * File input
     */
    const INPUT_FILE = 'fileInput';
    /**
     * Other HTML5 input (e.g. color, range, email etc.)
     */
    const INPUT_HTML5 = 'input';
    /**
     * Input widget
     */
    const INPUT_WIDGET = 'widget';
    /**
     * Krajee dependent dropdown input widget [[\kartik\depdrop\DepDrop]]
     */
    const INPUT_DEPDROP = '\kartik\depdrop\DepDrop';
    /**
     * Krajee select2 input widget [[\kartik\select2\Select2]]
     */
    const INPUT_SELECT2 = '\kartik\select2\Select2';
    /**
     * Krajee typeahead input widget [[\kartik\typeahead\Typeahead]]
     */
    const INPUT_TYPEAHEAD = '\kartik\typeahead\Typeahead';
    /**
     * Krajee switch input widget [[\kartik\switchinput\SwitchInput]]
     */
    const INPUT_SWITCH = '\kartik\switchinput\SwitchInput';
    /**
     * Krajee touch spin input widget [[\kartik\touchspin\TouchSpin]]
     */
    const INPUT_SPIN = '\kartik\touchspin\TouchSpin';
    /**
     * Krajee star rating input widget [[\kartik\rating\StarRating]]
     */
    const INPUT_RATING = '\kartik\rating\StarRating';
    /**
     * Krajee range input widget [[\kartik\range\RangeInput]]
     */
    const INPUT_RANGE = '\kartik\range\RangeInput';
    /**
     * Krajee color input widget [[\kartik\color\ColorInput]]
     */
    const INPUT_COLOR = '\kartik\color\ColorInput';
    /**
     * Krajee file input widget [[\kartik\file\FileInput]]
     */
    const INPUT_FILEINPUT = '\kartik\file\FileInput';
    /**
     * Krajee date picker input widget [[\kartik\date\DatePicker]]
     */
    const INPUT_DATE = '\kartik\date\DatePicker';
    /**
     * Krajee Time picker input widget [[\kartik\time\TimePicker]]
     */
    const INPUT_TIME = '\kartik\time\TimePicker';
    /**
     * Krajee date time Picker input widget [[\kartik\datetime\DateTimePicker]]
     */
    const INPUT_DATETIME = '\kartik\datetime\DateTimePicker';
    /**
     * Krajee date range picker input widget [[\kartik\daterange\DateRangePicker]]
     */
    const INPUT_DATE_RANGE = '\kartik\daterange\DateRangePicker';
    /**
     * Krajee sortable input widget [[\kartik\sortinput\SortableInput]]
     */
    const INPUT_SORTABLE = '\kartik\sortinput\SortableInput';
    /**
     * Krajee slider input widget [[\kartik\slider\Slider]]
     */
    const INPUT_SLIDER = '\kartik\slider\Slider';
    /**
     * Krajee mask money input widget [[\kartik\money\MaskMoney]]
     */
    const INPUT_MONEY = '\kartik\money\MaskMoney';
    /**
     * Krajee checkbox extended input widget [[\kartik\checkbox\CheckboxX]]
     */
    const INPUT_CHECKBOX_X = '\kartik\checkbox\CheckboxX';
    /**
     * Loading indicator markup for the editable
     */
    const LOAD_INDICATOR = '<div class="kv-editable-loading" style="display:none">&nbsp;</div>';
    /**
     * CSS setting for the editable parent
     */
    const CSS_PARENT = "kv-editable-parent form-group";

    /**
     * @var string the identifier for the PJAX widget container if the editable widget is to be rendered inside a PJAX
     * container. This will ensure the PopoverX plugin is initialized correctly after a PJAX request is completed.
     * If this is not set, no re-initialization will be done for pjax.
     */
    public $pjaxContainerId;

    /**
     * @var string the display format for the editable. Accepts one of the following values.
     * - `'link' or [[Editable::FORMAT_LINK]]: will convert the text to a clickable editable link.
     * - `'button' or [[Editable::FORMAT_BUTTON]]: will display a separate button beside the text.
     * Defaults to [[Editable::FORMAT_LINK]] if you do not set it as [[Editable::FORMAT_BUTTON]].
     */
    public $format = self::FORMAT_LINK;

    /**
     * @var boolean whether to show the editable input as a popover. Defaults to `true`. If set to `false`, it will be
     * rendered inline.
     */
    public $asPopover = true;

    /**
     * @var array the settings for the inline editable when [[asPopover]] is `false`. The following properties are
     * recognized:
     * - `options`: _array_, the HTML attributes for the `div` panel container that will enclose the inline content. By
     * default the options will be set to `['class' => 'card panel panel-default']`.
     * - `closeButton`: _string_, the markup for rendering the close button to close the inline panel. Note the markup must
     * have the CSS class `kv-editable-close` to trigger the closure of the inline panel. The `closeButton`
     * defaults to `<button class="kv-editable-close close">&times;</button>`.
     * - `templateBefore`: _string_, the template for inline content rendered before the input. Defaults to
     * [[INLINE_BEFORE_1]].
     * - `templateAfter`: _string_, he template for inline content rendered after the input. Defaults to
     * [[INLINE_AFTER_1]]. The following tokens in the templates above will be automatically replaced:
     *    - '{header}': the header generated via `preHeader` and `header` properties.
     *    - '{inputs}': the main form input content (combining `beforeInput`, the input/widget generated based on
     *      `inputType`, and `afterInput`)
     *    - '{buttons}': the form action buttons (submit and reset).
     *    - '{loading}': the loading indicator.
     *    - '{close}': the close button to close the inline content as set in `inlineSettings['closeButton']`.
     */
    public $inlineSettings = [];

    /**
     * @var array the HTML attributes for the editable button to be displayed when the format has been set to 'button':
     * - `label`: _string_, the editable button label. This is not HTML encoded. Defaults to [[defaultEditableBtnIcon]].
     */
    public $editableButtonOptions = [];

    /**
     * @var array the HTML attributes for the editable value displayed
     */
    public $editableValueOptions = [];

    /**
     * @var array the HTML attributes for the editable container
     */
    public $containerOptions = [];

    /**
     * @var array the HTML attributes for the input container applicable only when not using with ActiveForm
     */
    public $inputContainerOptions = [];

    /**
     * @var string the popover contextual type. Must be one of the [[PopoverX::TYPE]] constants Defaults to
     * [[PopoverX::TYPE_DEFAULT]] or `default`. This will be applied only if [[asPopover]] is `true`.
     */
    public $type = PopoverX::TYPE_DEFAULT;

    /**
     * @var string the size of the popover window. One of the `PopoverX::SIZE` constants. This will be applied only
     * if [[asPopover]] is `true`.
     */
    public $size;

    /**
     * @var string the popover placement. Must be one of the `PopoverX::ALIGN` constants Defaults to
     * [[PopoverX::ALIGN_RIGHT]] or `right`. This will be applied only if [[asPopover]] is `true`.
     */
    public $placement = PopoverX::ALIGN_RIGHT;

    /**
     * @var string the header content placed before the header text in the popover dialog or inline panel. This
     * defaults to: '{icon} Edit' - where {icon} is the [[defaultPreHeaderIcon]] markup.
     */
    public $preHeader;

    /**
     * @var string the header content in the popover dialog or inline panel. If not set, this will be auto generated
     * based on the attribute label or set to null.
     */
    public $header;

    /**
     * @var string the footer content in the popover dialog or inline panel. The following special tags/variables will
     * be parsed and replaced in the footer:
     * - `{loading}`: _string_, will be replaced with the loading indicator.
     * - `{buttons}`: _string_, will be replaced with the submit and reset button. If this is set to null or an empty
     * string, it will not be displayed.
     */
    public $footer = '{loading}{buttons}';

    /**
     * @var string the value to be displayed. If not set, this will default to the attribute value. If the attribute
     * value is null, then this will display the value as set in [[valueIfNull]].
     */
    public $displayValue;

    /**
     * @var array the configuration to auto-calculate display value, based on the value of the editable input. This
     * should be a single dimensional array whose keys must match the input value, and the array values must be the
     * description to be displayed. For example, to display user friendly bool values, you could configure this as
     * `[0 => 'Inactive', 1 => 'Active']`. If this is set, it will override any value set in `displayValue`.
     */
    public $displayValueConfig = [];

    /**
     * @var string the value to display if the displayValue is null. Defaults to '<em>(not set)</em>'.
     */
    public $valueIfNull;

    /**
     * @var array the HTML attributes for the container enclosing the main content in the popover dialog.
     */
    public $contentOptions = [];

    /**
     * @var array the class for the ActiveForm widget to be used. The class must extend from `\yii\widgets\ActiveForm`.
     * This defaults to `\kartik\form\ActiveForm`.
     */
    public $formClass = '\kartik\form\ActiveForm';

    /**
     * @var array the options for the ActiveForm widget class selected in `formClass`.
     */
    public $formOptions = [];

    /**
     * @var array the input type to render for the editing the input in the editable form. This must be one of the
     * `Editable::TYPE` constants.
     */
    public $inputType = self::INPUT_TEXT;

    /**
     * @var string any custom widget class to use. Will only be used if the `inputType` is set to
     * [[INPUT_WIDGET]]
     */
    public $widgetClass;

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
     * @var boolean whether to auto submit/save the form on pressing ENTER key.
     */
    public $submitOnEnter = true;

    /**
     * @var boolean whether to select all text in the input when editable is opened.
     */
    public $selectAllOnEdit = true;

    /**
     * @var boolean whether to HTML encode the output via javascript after editable update. Defaults to `true`. Note that
     * this is only applied, if you do not return an output value via your AJAX response action. If you return an
     * output value via AJAX it will not be HTML encoded.
     */
    public $encodeOutput = true;

    /**
     * @var boolean whether to close the editable form when it loses focus.
     */
    public $closeOnBlur = false;

    /**
     * @var integer editable submission validation delay (in micro-seconds).
     */
    public $validationDelay = 500;

    /**
     * @var integer editable reset delay (in micro-seconds).
     */
    public $resetDelay = 200;

    /**
     * @var integer|string editable fade animation delay (in micro-seconds). If entered as a string, it can be one of
     * `'slow'` or `'fast'`.
     * @see http://api.jquery.com/fadein/
     */
    public $animationDelay = 300;

    /**
     * @var array the options for the input. If the inputType is one of the HTML inputs, this will capture the HTML
     * attributes. If the `inputType` is set to [[INPUT_WIDGET]] or set to an input widget from the `\kartik\`
     * namespace, then this will capture the widget options.
     */
    public $options = [];

    /**
     * @var array the ActiveField configuration, if you are using with `model`.
     */
    public $inputFieldConfig = [];

    /**
     * @var string|Closure the content to be placed before the rendered input. If not set as a string, this can be
     * passed as a callback function of the following signature:
     *
     * ```
     * function ($form, $widget) {
     *    // echo $form->field($widget->model, 'attrib');
     * }
     * ```
     *
     * where:
     *
     * - `$form`: _ActiveForm_, is the active form instance for the editable form
     * - `$widget`: _Editable_, is the current editable widget instance
     */
    public $beforeInput;

    /**
     * @var string|Closure the content to be placed after the rendered input. If not set as a string, this can be
     * passed as a callback function of the following signature:
     * `
     * function ($form, $widget) {
     *    // echo $form->field($widget->model, 'attrib');
     * }
     * `
     *
     * where:
     *
     * - `$form`: _ActiveForm_, is the active form instance for the editable form
     * - `$widget`: _Editable_, is the current editable widget instance
     */
    public $afterInput;

    /**
     * @var boolean whether you wish to automatically display the form submit and reset buttons. Defaults to `true`.
     */
    public $showButtons = true;

    /**
     * @var boolean whether you want to show the button labels. Defaults to `false`.
     */
    public $showButtonLabels = false;

    /**
     * @var string the template for rendering the buttons
     */
    public $buttonsTemplate = "{reset}{submit}";

    /**
     * @var string the default icon for editable button set as the label in [[editableButtonOptions]]. Defaults to
     *   `<i class="glyphicon glyphicon-pencil"></i> for [[bsVersion]] = '3.x' and
     *   `<i class="fas fa-pencil-alt"></i> for [[bsVersion]] = '4.x'
     */
    public $defaultEditableBtnIcon;

    /**
     * @var string the default icon for editable button used in the [[preHeader]]. Defaults to:
     *   `<i class="glyphicon glyphicon-ok"></i> ` for [[bsVersion]] = '3.x' and
     *   `<i class="fas fa-check"></i> ` for [[bsVersion]] = '4.x'
     */
    public $defaultSubmitBtnIcon;

    /**
     * @var string the default icon for editable button used in the [[preHeader]]. Defaults to:
     *   `<i class="glyphicon glyphicon-ban-circle"></i> ` for [[bsVersion]] = '3.x' and
     *   `<i class="fas fa-ban"></i> ` for [[bsVersion]] = '4.x'
     */
    public $defaultResetBtnIcon;

    /**
     * @var string the default icon for editable button used in the [[preHeader]]. Defaults to:
     *   `<i class="glyphicon glyphicon-edit"></i> ` for [[bsVersion]] = '3.x' and
     *   `<i class="fas fa-edit"></i> Edit` for [[bsVersion]] = '4.x'
     */
    public $defaultPreHeaderIcon;

    /**
     * @var array the HTML attributes for the form submit button. The following special properties are additionally
     * recognized:
     * - `icon`: _string_, the icon for the button. Defaults to [[defaultSubmitBtnIcon]].
     * - `label`: _string_, the label of the button. This is HTML encoded. Defaults to `Apply` and is translated via yii
     *   i18n message files.
     */
    public $submitButton = ['class' => 'btn btn-sm btn-primary'];

    /**
     * @var array the HTML attributes for the form reset button. The following special properties are additionally
     * recognized:
     * - `icon`: _string_, the icon for the button. Defaults to [[defaultResetBtnIcon]]
     * - `label`: _string_, the label of the button. This is HTML encoded. Defaults to `Reset` and is translated via yii
     * i18n message files.
     */
    public $resetButton = [];

    /**
     * @var array additional data to be passed when editable is submitted via ajax request as `$key => $value` pairs.
     * This will generate hidden inputs in the editable form with input name as `$key` and input value as `$value`.
     */
    public $additionalData = [];

    /**
     * @var array the generated configuration for the [[PopoverX]] widget.
     */
    protected $_popoverOptions = [];

    /**
     * @var array the HTML attributes or options for the input/widget
     */
    protected $_inputOptions = [];

    /**
     * @var ActiveForm active form instance
     */
    protected $_form;

    /**
     * @var string the i18n message category
     */
    protected $_msgCat = 'kveditable';

    /**
     * @var array configuration of icons for BS3 and BS4
     */
    protected static $_icons = [
        'defaultEditableBtnIcon' => ['pencil', 'pencil-alt'],
        'defaultSubmitBtnIcon' => ['ok', 'check'],
        'defaultResetBtnIcon' => ['ban-circle', 'ban'],
        'defaultPreHeaderIcon' => ['edit', 'edit'],
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initEditable();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->runEditable();
    }

    /**
     * Registers the client assets for [[Editable]] widget.
     */
    public function registerAssets()
    {
        $view = $this->getView();
        EditableAsset::register($view);
        $this->pluginOptions = [
            'valueIfNull' => $this->valueIfNull,
            'asPopover' => $this->asPopover,
            'placement' => $this->placement,
            'target' => $this->format === self::FORMAT_BUTTON ? '.kv-editable-button' : '.kv-editable-link',
            'displayValueConfig' => $this->displayValueConfig,
            'ajaxSettings' => $this->ajaxSettings,
            'showAjaxErrors' => $this->showAjaxErrors,
            'submitOnEnter' => $this->submitOnEnter,
            'selectAllOnEdit' => $this->selectAllOnEdit,
            'encodeOutput' => $this->encodeOutput,
            'closeOnBlur' => $this->closeOnBlur,
            'validationDelay' => $this->validationDelay,
            'resetDelay' => $this->resetDelay,
            'animationDelay' => $this->animationDelay,
        ];
        $this->registerPlugin('editable', 'jQuery("#' . $this->containerOptions['id'] . '")');
        if (!empty($this->pjaxContainerId)) {
            EditablePjaxAsset::register($view);
            $toggleButton = $this->_popoverOptions['toggleButton']['id'];
            $initPjaxVar = 'kvEdPjax_' . str_replace('-', '_', $this->_popoverOptions['options']['id']);
            $view->registerJs("var {$initPjaxVar} = false;", View::POS_HEAD);
            if ($this->asPopover) {
                $js = "initEditablePjax('{$this->pjaxContainerId}', '{$toggleButton}', '{$initPjaxVar}');";
                $view->registerJs($js);
            }
        }
        if ($this->inputType === Editable::INPUT_TIME) {
            $this->_popoverOptions['toggleButton']['data-show'] = true;
        }
    }

    /**
     * Gets the form instance for use at runtime
     *
     * @return ActiveForm
     */
    public function getForm()
    {
        return $this->_form;
    }

    /**
     * Initialize default icons
     * @throws InvalidConfigException|Exception
     */
    protected function initIcons()
    {
        $notBs3 = !$this->isBs(3);
        $prefix = $this->getDefaultIconPrefix();
        foreach (static::$_icons as $icon => $setting) {
            if (!isset($this->$icon)) {
                $css = $notBs3 ? $setting[1] : $setting[0];
                $this->$icon = Html::tag('i', '', ['class' => $prefix . $css]);
            }
        }
    }

    /**
     * Initializes the widget
     */
    protected function initEditable()
    {
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
        if (!empty($this->formClass) && !class_exists($this->formClass)) {
            throw new InvalidConfigException("The form class '{$this->formClass}' does not exist.");
        }
        Config::validateInputWidget($this->inputType);
        $this->initI18N(__DIR__);
        $this->initIcons();
        $this->initOptions();
        $this->_popoverOptions['options']['id'] = $this->options['id'] . '-popover';
        $this->_popoverOptions['toggleButton']['id'] = $this->options['id'] . '-targ';
        if (!$this->isBs(3)) {
            $this->_popoverOptions['bsVersion'] = $this->bsVersion;
        }
        $this->registerAssets();
        echo Html::beginTag('div', $this->containerOptions);
        if ($this->format == self::FORMAT_BUTTON) {
            echo Html::tag('div', $this->displayValue, $this->editableValueOptions);
        }
        if ($this->asPopover === true) {
            PopoverX::begin($this->_popoverOptions);
        } elseif ($this->format !== self::FORMAT_BUTTON) {
            echo $this->renderToggleButton();
        }
        echo Html::beginTag('div', $this->contentOptions);
        /**
         * @var string|ActiveForm $class
         */
        $class = $this->formClass;
        if (!class_exists($class)) {
            throw new InvalidConfigException("The form class '{$class}' set via 'Editable::formClass' does not exist.");
        }
        $this->_form = $class::begin($this->formOptions);
        if (!$this->_form instanceof ActiveForm) {
            throw new InvalidConfigException("The form class '{$class}' MUST extend from '\yii\widgets\ActiveForm'.");
        }
    }

    /**
     * Runs the editable widget
     */
    protected function runEditable()
    {
        if (!$this->asPopover) {
            echo Html::beginTag('div', $this->inlineSettings['options']);
        }
        $this->renderFormFields();
        if (!$this->asPopover) {
            echo "</div>\n"; // inline options
        }
        /**
         * @var ActiveForm $class
         */
        $class = $this->formClass;
        $class::end();
        echo "</div>\n"; // content options
        if ($this->asPopover === true) {
            PopoverX::end();
        } elseif ($this->format == self::FORMAT_BUTTON) {
            echo $this->renderToggleButton();
        }
        echo "</div>\n"; // options
    }

    /**
     * Renders the toggle button
     *
     * @return string
     */
    protected function renderToggleButton()
    {
        $options = $this->_popoverOptions['toggleButton'];
        $label = ArrayHelper::remove($options, 'label', '');
        return Html::button($label, $options);
    }

    /**
     * Initializes the inline settings & options.
     */
    protected function initInlineOptions()
    {
        $title = Yii::t('kveditable', 'Close');
        $defaultSettings = [
            'templateBefore' => self::INLINE_BEFORE_1,
            'templateAfter' => self::INLINE_AFTER_1,
            'options' => ['class' => 'card panel panel-default'],
            'closeButton' => Html::button('&times;', ['class' => 'kv-editable-close kv-btn-close', 'title' => $title]),
        ];
        $this->inlineSettings = array_replace_recursive($defaultSettings, $this->inlineSettings);
        Html::addCssClass($this->contentOptions, 'kv-editable-inline');
        Html::addCssStyle($this->contentOptions, 'display:none');
    }

    /**
     * Initializes the widget options. This method sets the default values for various widget options.
     *
     * @throws InvalidConfigException
     */
    protected function initOptions()
    {
        $defaultBtnCss = $this->getDefaultBtnCss();
        if (!isset($this->resetButton['class'])) {
            $this->resetButton['class'] = 'btn btn-sm ' . $defaultBtnCss;
        }
        if (!isset($this->editableButtonOptions['class'])) {
            $this->editableButtonOptions['class'] = 'btn btn-sm ' . $defaultBtnCss;
        }
        Html::addCssClass($this->inputContainerOptions, self::CSS_PARENT);
        if ($this->asPopover !== true) {
            $this->initInlineOptions();
        }
        if ($this->hasModel()) {
            $options = ArrayHelper::getValue($this->inputFieldConfig, 'options', []);
            Html::addCssClass($options, self::CSS_PARENT);
            $this->inputFieldConfig['options'] = $options;
        }
        if (!Config::isHtmlInput($this->inputType)) {
            if ($this->widgetClass === 'kartik\datecontrol\DateControl') {
                $options = ArrayHelper::getValue($this->options, 'options.options', []);
                Html::addCssClass($options, 'kv-editable-input');
                $this->options['options']['options'] = $options;
                $this->options['widgetOptions']['options'] = $options;
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
        $value = ($this->hasModel() && !isset($this->value)) ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
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
            throw new InvalidConfigException(
                "Your editable value cannot be an array or object for parsing with 'displayValueConfig'. The array keys in 'displayValueConfig' must be a simple string or number. For advanced display value calculations, you must use your controller AJAX action to return 'output' as a JSON encoded response which will be used as a display value."
            );
        }
        if ($hasDisplayConfig && isset($this->displayValueConfig[$value])) {
            $this->displayValue = $this->displayValueConfig[$value];
        }
        Html::addCssClass($this->containerOptions, 'kv-editable');
        Html::addCssClass($this->contentOptions, 'kv-editable-content');
        Html::addCssClass($this->formOptions['options'], 'kv-editable-form');
        $class = 'kv-editable-value';
        if ($this->format == self::FORMAT_BUTTON) {
            if (!$this->asPopover) {
                $before = ArrayHelper::getValue($this->inlineSettings, 'templateBefore', '');
                if ($before === self::INLINE_BEFORE_1) {
                    Html::addCssClass($this->containerOptions, 'kv-editable-inline-1');
                } elseif ($before === self::INLINE_BEFORE_2) {
                    Html::addCssClass($this->containerOptions, 'kv-editable-inline-2');
                }
            }
            Html::addCssClass($this->editableButtonOptions, 'kv-editable-button');
        } elseif (empty($this->editableValueOptions['class'])) {
            $class = ['kv-editable-value', 'kv-editable-link'];
        }
        Html::addCssClass($this->editableValueOptions, $class);
        $this->_popoverOptions['type'] = $this->type;
        $this->_popoverOptions['placement'] = $this->placement;
        $this->_popoverOptions['size'] = $this->size;
        if (!isset($this->preHeader)) {
            $this->preHeader = $this->defaultPreHeaderIcon . ' ' . Yii::t('kveditable', 'Edit') . ' ';
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
                $this->editableButtonOptions['label'] = $this->defaultEditableBtnIcon;
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
     * Generates the editable action buttons
     *
     * @return string
     */
    protected function renderActionButtons()
    {
        $submitOpts = $this->submitButton;
        $resetOpts = $this->resetButton;
        $submitIcon = ArrayHelper::remove($submitOpts, 'icon', $this->defaultSubmitBtnIcon);
        $resetIcon = ArrayHelper::remove($resetOpts, 'icon', $this->defaultResetBtnIcon);
        $submitLabel = ArrayHelper::remove($submitOpts, 'label', Yii::t('kveditable', 'Apply'));
        $resetLabel = ArrayHelper::remove($resetOpts, 'label', Yii::t('kveditable', 'Reset'));
        if ($this->showButtonLabels === false) {
            if (empty($submitOpts['title'])) {
                $submitOpts['title'] = $submitLabel;
            }
            if (empty($resetOpts['title'])) {
                $resetOpts['title'] = $resetLabel;
            }
            $submitLabel = $submitIcon;
            $resetLabel = $resetIcon;
        } else {
            $submitLabel = $submitIcon . ' ' . Html::encode($submitLabel);
            $resetLabel = $resetIcon . ' ' . Html::encode($resetLabel);
        }
        $submitOpts['type'] = 'button';
        $resetOpts['type'] = 'button';
        Html::addCssClass($submitOpts, 'kv-editable-submit');
        Html::addCssClass($resetOpts, 'kv-editable-reset');
        $params = [
            '{reset}' => Html::button($resetLabel, $resetOpts),
            '{submit}' => Html::button($submitLabel, $submitOpts),
        ];
        return strtr($this->buttonsTemplate, $params);
    }

    /**
     * Generates the popover footer
     *
     * @return string
     */
    protected function renderFooter()
    {
        return strtr($this->footer, ['{loading}' => self::LOAD_INDICATOR, '{buttons}' => $this->renderActionButtons()]);
    }

    /**
     * Parses the inline template and returns the generated content
     *
     * @param string $template the template setting
     *
     * @return string
     */
    protected function parseTemplate($template)
    {
        if ($this->asPopover) {
            return '';
        }
        $params = [
            '{header}' => $this->_popoverOptions['header'],
            '{close}' => $this->inlineSettings['closeButton'],
            '{loading}' => self::LOAD_INDICATOR,
        ];
        $out = strtr($this->inlineSettings[$template], $params);
        if (strpos($out, '{buttons}') === false) {
            return $out;
        }
        return strtr($out, ['{buttons}' => $this->renderActionButtons()]);
    }

    /**
     * Renders the editable form fields
     */
    protected function renderFormFields()
    {
        echo $this->parseTemplate('templateBefore');
        echo Html::hiddenInput('hasEditable', 0) . "\n";
        foreach ($this->additionalData as $name => $value) {
            echo Html::hiddenInput($name, $value) . "\n";
        }
        $before = $this->beforeInput;
        $after = $this->afterInput;
        if ($before !== null && is_string($before) || is_callable($before)) {
            echo (is_callable($before) ? call_user_func($before, $this->_form, $this) : $before) . "\n";
        }
        if ($this->inputType === self::INPUT_HTML5) {
            echo $this->renderHtml5Input() . "\n";
        } elseif ($this->inputType === self::INPUT_WIDGET) {
            echo $this->renderWidget($this->widgetClass) . "\n";
        } elseif (Config::isHtmlInput($this->inputType)) {
            echo $this->renderInput() . "\n";
        } elseif (Config::isInputWidget($this->inputType)) {
            echo $this->renderWidget($this->inputType) . "\n";
        }
        if ($after !== null && is_string($after) || is_callable($after)) {
            echo (is_callable($after) ? call_user_func($after, $this->_form, $this) : $after) . "\n";
        }
        echo $this->parseTemplate('templateAfter');
    }

    /**
     * Gets the active field instance for the configured editable input
     *
     * @param boolean|string $label the label for the field
     *
     * @return ActiveField
     */
    protected function getField($label = false)
    {
        return $this->_form->field($this->model, $this->attribute, $this->inputFieldConfig)->label($label);
    }

    /**
     * Generates the widget output markup
     *
     * @param string $content the content to render
     *
     * @return string
     */
    protected function getOutput($content)
    {
        return Html::tag('div', $content, $this->inputContainerOptions);
    }

    /**
     * Renders the HTML 5 input
     *
     * @return string
     */
    protected function renderHtml5Input()
    {
        $type = ArrayHelper::remove($this->_inputOptions, 'type', 'text');
        $out = Html::input($type, $this->name, $this->value, $this->_inputOptions);
        if ($this->hasModel()) {
            if (isset($this->_form)) {
                return $this->getField()->input($type, $this->_inputOptions);
            }
            $out = Html::activeInput($this->type, $this->model, $this->attribute, $this->_inputOptions);
        }
        return $this->getOutput($out);
    }

    /**
     * Renders a widget
     *
     * @param string $class the input widget class name
     *
     * @return string
     * @throws Exception
     */
    protected function renderWidget($class)
    {
        if ($this->hasModel()) {
            if (isset($this->_form)) {
                return $this->getField()->widget($class, $this->_inputOptions);
            }
            $defaults = ['model' => $this->model, 'attribute' => $this->attribute];

        } else {
            $defaults = ['name' => $this->name, 'value' => $this->value];
        }
        $options = ArrayHelper::merge($this->_inputOptions, $defaults);
        /**
         * @var InputWidget $class
         */
        $field = $class::widget($options);
        return $this->getOutput($field);
    }

    /**
     * Renders all native HTML inputs (except [[INPUT_HTML5]])
     *
     * @return string
     */
    protected function renderInput()
    {
        $list = Config::isDropdownInput($this->inputType);
        $input = $this->inputType;
        if ($this->hasModel()) {
            if (isset($this->_form)) {
                $field = $this->getField();
                return $list ? $field->$input($this->data, $this->_inputOptions) : $field->$input($this->_inputOptions);
            }
            $input = 'active' . ucfirst($this->inputType);
        }
        $value = $this->value;
        if ($input === 'radio' || $input === 'checkbox') {
            $this->options['value'] = $value;
            $value = ArrayHelper::remove($this->_inputOptions, 'checked', false);
        }
        if ($list) {
            $field = Html::$input($this->name, $value, $this->data, $this->_inputOptions);
        } else {
            $field = Html::$input($this->name, $value, $this->_inputOptions);
        }
        return $this->getOutput($field);
    }
}
