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