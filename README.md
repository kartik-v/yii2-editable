yii2-editable
=============

Easily set any displayed content as editable in Yii Framework 2.0. This is an enhanced editable widget for Yii 2.0 that allows easy editing of displayed data, using inputs, widgets and more with numerous configuration possibilities.
The extension uses the enhanced [yii2-popover-x](http://demos.krajee.com/popover-x) as a popover modal for editing. This extension does not use any external jQuery plugin like XEditable, instead it uses its own lean and extensible
 jQuery editable plugin - that elaborately reuses functionality available within Yii Framework 2.0.

## Features  

1. Set any readable markup on your view, DetailView, or GridView to be editable. (**Under Process:** The widgets `\kartik\grid\GridView` 
   and `\kartik\detail\DetailView` widgets will be enhanced to use this extension in a very easy way).
2. Provides two display formats for setting up your editable content . 
   - **Link**: Convert the editable content as a clickable link for popover.
   - **Button**: Do not convert the editable content to a link, but rather display a button beside it for editing content.
3. Uses Yii 2.0 ActiveForm for editing content. Hence all features of Yii ActiveForm, including model validation rules are available.
4. For editing the content, you can configure it to use any of the HTML inputs, or widgets available from **kartik-v/yii2-widgets** or other input widgets from https://github.com/kartik-v. 
   In addition, you can also use HTML 5 inputs or any custom input widget to edit your content.
5. Entirely control the way the form content is displayed in the popover. By default, the widget displays the input to be edited. In addition, you can place
   more form fields or markup before and after this default input.
6. Uses AJAX based form submission to process quick editing of data and provide a seamless user experience.
7. Uses advanced features of the [yii2-popover-x extension](http://demos.krajee.com/popover-x), to control display formats for your editable popover form. This
   uses the enhanced [bootstrap-popover-x](http://plugins.krajee.com/popover-x) by Krajee.
   
> NOTE: This extension depends on the [kartik-v/yii2-popover-x](https://github.com/kartik-v/yii2-popover-x) extension which in turn depends on the 
[kartik-v/yii2-widgets](https://github.com/kartik-v/yii2-widgets) extension and [yiisoft/yii2-bootstrap](https://github.com/yiisoft/yii2/tree/master/extensions/bootstrap) extension. Check the 
[composer.json](https://github.com/kartik-v/yii2-editable/blob/master/composer.json) for this extension's requirements and dependencies. 
Note: Yii 2 framework is still in active development, and until a fully stable Yii2 release, your core yii2-bootstrap packages (and its dependencies) 
may be updated when you install or update this extension. You may need to lock your composer package versions for your specific app, and test 
for extension break if you do not wish to auto update dependencies.

## Demo
You can see detailed [documentation and examples](http://demos.krajee.com/editable) on usage of the extension.

### _Demo is under construction_

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

> Note: You must set the `minimum-stability` to `dev` in the **composer.json** file in your application root folder before installation of this extension.

Either run

```
$ php composer.phar require kartik-v/yii2-editable "dev-master"
```

or add

```
"kartik-v/yii2-editable": "dev-master"
```

to the ```require``` section of your `composer.json` file.

## Usage

### Editable

```php
use kartik\editable\Editable;
echo Editable::widget([
    'model'=>$model, 
    'attribute' => 'rating',
    'type'=>'primary',
    'size'=>'lg',
    'inputType'=>Editable::INPUT_RATING,
    'editableValueOptions'=>['class'=>'text-success h3']
]);
```

## License

**yii2-editable** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.