Change Log: `yii2-editable`
===========================

## Version 1.7.8

**Date:** 03-Oct-2018

- (enh #181, #182): Correct composer dependencies.

## Version 1.7.7

**Date:** 16-Sep-2018

- (enh #180): Update Persian Translations.
- (bug #178, #179): Correct bugs in editable.js variable calls.
- Reorganize source code in `src` directory.
- (enh #174): Correct HTML Encode.
- (enh #167): Correct widget options for widgets like DateControl.

## Version 1.7.6

**Date:** 28-Jun-2017

- Chronlogically sort issues and enhancements in CHANGE Log.
- (enh #157): Add Turkish translations.
- (enh #153): New property `resetDelay` to control the delay in processing callback during editable reset. 
- (enh #152): New property `closeOnBlur` that automatically closes the form on blur.
- (enh #152): New property `animationDelay` to control fade animation delay for popover or inline element.  
- (enh #150): Code enhancements and optimizations. 
- (enh #149): New property `validationDelay` to control client validation delay for active form.  
- (enh #148): New property `selectAllOnEdit` that automatically selects all text in the input on edit. 
- (enh #147): Fix disappearing form when pressing enter.
- (enh #146): Close inline form on escape.

## Version 1.7.5

**Date:** 08-Jan-2017

- (bug #141): Close inline editable tags correctly.
- (bug #139): Better validation of value set in displayValueConfig.
- (bug #136): New property `additionalData` to send additional data as key-value pairs via editable ajax form POST.
- (enh #126): Fix help block display for non active forms.
- (enh #125): Add Latvian translations.
- (enh #123): Allow advanced form data to be sent via ajax (e.g. file inputs).
- (enh #121): Add Ukranian translations.
- (enh #120): Add Estonian translations.
- (enh #116): New configurable property `pluginOptions['validationDelay']` to control the editable submission validation delay (in micro-seconds).
- (enh #113): Allow ability to configure `valueOptions['class']` in link mode.
- (enh #108): Add Dutch translations.
- (enh #105): Add Indonesian translations.
- Correct Editable input types.
- Add github contribution and issue/PR logging templates.
- Update message config to include all default standard translation files.

## Version 1.7.4

**Date:** 27-Dec-2015

- (enh #104): Enhance jQuery events
    - New event `editableBeforeSubmit` that submits before ajax request
    - Enable events to be aborted and prevent default behavior by `event.preventDefault()` (applicable for most critical editable events)
    - Incorporate event namespace `.editable` on the critical events
- Code optimizations for widget and JS plugin.
- (enh #103): Add Czech Translations.
- (enh #100): New `initEditablePopover` method to initialize popover for AJAX replaced editables.
- (enh #91): Add plugin option `encodeOutput` to encode HTML output.
- (enh #88): Update Russian translations.
- (enh #87): Add jQuery plugin destroy & create methods.
- (enh #85): Add Greek translations.
- (enh #84): Update Portugese translations.
- (enh #64): Better fix for #64 in resetting errors.

## Version 1.7.3

**Date:** 01-Jul-2015

- (enh #78): Added Chinese translations.
- (enh #76): Improved Spanish translations.
- (enh #73): Added Polish translations.
- (enh #70): Added default `en` translations.
- (enh #69): Generate default `en` message translation file.
- (enh #64): Better reset of error container help-block content.
- (bug #63): Fix renderActionButtons to correctly parse submit & reset button options.
- (enh #62): Enhance footer property to include tags for '{loading}' and '{buttons}'.
- (enh #61): New boolean property `showButtonLabels` to control the display of action button labels (will show the label as title on hover).
- (enh #60): Ability to configure action button `icon` and `label` separately.
- (enh #59): New `buttonsTemplate` property for rendering editable form action buttons (reset and submit).
- (enh #58, #3): Ability to render editable content INLINE as an alternative to a POPOVER. 
    - New `asPopover` boolean property added.
    - Enhanced inline form styles
    - INLINE templates and settings to control how content should be rendered
    - Two built in inline templates
- (enh #57): Added translation support for Lithuanian language.
- (enh #56): Reset/Clear help-block error messages before next validation.
- (enh #54): Auto guess input and set input options based on input type.

## Version 1.7.2

**Date:** 29-Mar-2015

- (enh #53): Auto initialize `kv-editable-input` CSS for various input types and widgets.
- (bug #49): Throw exception when an array value is passed as key to `displayValueConfig`.
- (enh #47, #48): New property `submitOnEnter` to control save on ENTER key press.
- (enh #44): Revamp editable widget initialization and auto detection of input.
- (enh #41): Improve validation to retrieve the right translation messages folder.
- (enh #40): Enhanced styling for disabled editable button.
- (enh #39): Better validation for `valueIfNull`.

## Version 1.7.1

**Date:** 13-Feb-2015

- Set copyright year to current.
- (enh #35): Add French translations.
- (enh #34): Various enhancements to plugin code.
- (enh #33): New `ajaxSettings` property that can be used to merge additional ajax settings/options for editable submission.
- (enh #32): Pass additional data to various editable events
    - `editableSubmit`: pass the editable form jquery element in addition to editable input element value
    - `editableSuccess`: pass ajax response data and editable form jquery element in addition to editable input element value
    - `editableError`: pass ajax response data editable form jquery elementin addition to editable input element value 
- (bug #24): More correct `displayValueConfig` validation.

## Version 1.7.0

**Date:** 12-Jan-2015

- Code formatting updates as per Yii2 coding style.
- Revamp to use new Krajee base Module and TranslationTrait.
- (enh #27): Add Spanish translations.
- (bug #25): Fix options setting for PopoverX.

## Version 1.6.0

**Date:** 22-Nov-2014

- (enh #22): Revamp extension to work better with model validation and in EditableColumn.
- (enh #21): Enhancements for rendering widgets and related styling.
- (bug #20): Fix widgets for use in Editable.
- (enh #18): Add Italian language translations.
- (enh #16): Enhance dependency validation using common code base.
- (enh #15): Add Vietnamese language translations.

## Version 1.4.0

**Date:** 22-Oct-2014

- New property `showAjaxErrors` included for the editable jQuery plugin.
- (enh #14): Raise new `editableAjaxError` event for errors faced via ajax

## Version 1.3.0

**Date:** 04-Sep-2014

- PSR4 alias change
- (enh #9): Included client plugin events `editableChange`, `editableSubmit`, `editableReset`, `editableSuccess`, and `editableError`.
- (enh #8): Enhance `beforeInput` & `afterInput` to be passed as callback functions.

## Version 1.2.0

**Date:** 26-Aug-2014

- (enh #6): Added `displayValueConfig` to auto calculate display value.
- (enh #4): Better fix to reinitialize form error blocks for each ajax call.


## Version 1.1.0

**Date:** 21-Aug-2014

- (enh #5): More correct valueIfNull and displayValue null validation check.
- (enh #4): Reinitialize form error blocks for each ajax call.
- (enh #2): Enhancements to the widget for rendering and processing via Pjax.
   
## Version 1.0.0

**Date:** 27-Jul-2014

###Initial release

- Set any readable markup on your view, DetailView, or GridView to be editable. (**Under Process:** The widgets `\kartik\grid\GridView` 
   and `\kartik\detail\DetailView` widgets will be (enhanced to use this extension in a very easy way.
- Provides two display formats for setting up your editable content . 
   - **Link**): Convert the editable content as a clickable link for popover.
   - **Button**): Do not convert the editable content to a link, but instead display a button beside it for editing content.
- Uses Yii 2.0 ActiveForm for editing content. Hence all features of Yii ActiveForm, including model validation rules are available.
- For editing the content, you can configure it to use any of the HTML inputs, or widgets available from **kartik-v/yii2-widgets** or other input widgets from https://github.com/kartik-v. 
   In addition, one can also use HTML 5 inputs or any custom input widget to edit your content.
- Entirely control the way the form content is displayed in the popover. By default, the widget displays the input to be edited. In addition, one can place
   more form fields or markup before and after this default input.
- Uses AJAX based form submission to process quick editing of data and a seamless user experience.
- Uses advanced features of the [yii2-popover-x extension](http://demos.krajee.com/popover-x), to control display formats for your editable popover form. This
   uses the (enhanced [bootstrap-popover-x](http://plugins.krajee.com/popover-x) by Krajee.