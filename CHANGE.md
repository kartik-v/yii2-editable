version 1.4.0
=============
**Date:** 22-Oct-2014

1. enh #14: Raise new `editableAjaxError` event for errors faced via ajax
2. New property `showAjaxErrors` included for the editable jQuery plugin.

version 1.3.0
=============
**Date:** 04-Sep-2014

1. enh #8: Enhance `beforeInput` & `afterInput` to be passed as callback functions.
2. enh #9: Included client plugin events `editableChange`, `editableSubmit`, `editableReset`, `editableSuccess`, and `editableError`.
3. PSR4 alias change

version 1.2.0
=============
**Date:** 26-Aug-2014

1. enh #4: Better fix to reinitialize form error blocks for each ajax call.
2. enh #6: Added `displayValueConfig` to auto calculate display value.


version 1.1.0
=============
**Date:** 21-Aug-2014

1. enh #2: Enhancements to the widget for rendering and processing via Pjax.
2. enh #4: Reinitialize form error blocks for each ajax call.
3. enh #5: More correct valueIfNull and displayValue null validation check.
   
version 1.0.0
=============
**Date:** 27-Jul-2014

### Initial release

1. Set any readable markup on your view, DetailView, or GridView to be editable. (**Under Process:** The widgets `\kartik\grid\GridView` 
   and `\kartik\detail\DetailView` widgets will be enhanced to use this extension in a very easy way.
2. Provides two display formats for setting up your editable content . 
   - **Link**: Convert the editable content as a clickable link for popover.
   - **Button**: Do not convert the editable content to a link, but instead display a button beside it for editing content.
3. Uses Yii 2.0 ActiveForm for editing content. Hence all features of Yii ActiveForm, including model validation rules are available.
4. For editing the content, you can configure it to use any of the HTML inputs, or widgets available from **kartik-v/yii2-widgets** or other input widgets from https://github.com/kartik-v. 
   In addition, one can also use HTML 5 inputs or any custom input widget to edit your content.
5. Entirely control the way the form content is displayed in the popover. By default, the widget displays the input to be edited. In addition, one can place
   more form fields or markup before and after this default input.
6. Uses AJAX based form submission to process quick editing of data and a seamless user experience.
7. Uses advanced features of the [yii2-popover-x extension](http://demos.krajee.com/popover-x), to control display formats for your editable popover form. This
   uses the enhanced [bootstrap-popover-x](http://plugins.krajee.com/popover-x) by Krajee.