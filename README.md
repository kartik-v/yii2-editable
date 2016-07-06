yii2-editable
=============

[![Latest Stable Version](https://poser.pugx.org/kartik-v/yii2-editable/v/stable)](https://packagist.org/packages/kartik-v/yii2-editable)
[![License](https://poser.pugx.org/kartik-v/yii2-editable/license)](https://packagist.org/packages/kartik-v/yii2-editable)
[![Total Downloads](https://poser.pugx.org/kartik-v/yii2-editable/downloads)](https://packagist.org/packages/kartik-v/yii2-editable)
[![Monthly Downloads](https://poser.pugx.org/kartik-v/yii2-editable/d/monthly)](https://packagist.org/packages/kartik-v/yii2-editable)
[![Daily Downloads](https://poser.pugx.org/kartik-v/yii2-editable/d/daily)](https://packagist.org/packages/kartik-v/yii2-editable)

Easily set any displayed content as editable in Yii Framework 2.0. This is an enhanced editable widget for Yii 2.0 that allows easy editing of displayed data, using inputs, widgets and more with numerous configuration possibilities. The extension uses the enhanced [yii2-popover-x](http://demos.krajee.com/popover-x) extension as a popover modal for editing. With release v1.7.3, this extension also allows you to render the editable content inline and offers advanced inline templates for configuration. This extension does not use any external jQuery plugin like X-Editable, instead it uses its own lean and extensible
 jQuery editable plugin - that elaborately reuses functionality available within Yii Framework 2.0.

> NOTE: The latest version of the extension v1.7.5 has been released. Refer the [CHANGE LOG](https://github.com/kartik-v/yii2-editable/blob/master/CHANGE.md) for details.

## Features  

1. Set any readable markup on your view, DetailView, or GridView to be editable. Refer the [EditableColumn](http://demos.krajee.com/grid#editable-column) details in kartik\grid\GridView for using an editable column in your grid.
2. Provides two display formats for setting up your editable content . 
   - **Link**: Convert the editable content as a clickable link for popover.
   - **Button**: Do not convert the editable content to a link, but rather display a button beside it for editing content.
3. Ability to render the content as a POPOVER or INLINE.
4. Advanced configurable inline templates for rendering complex content.
5. Uses Yii 2.0 ActiveForm for editing content. Hence all features of Yii ActiveForm, including model validation rules are available.
6. For editing the content, you can configure it to use any of the HTML inputs, or widgets available from **kartik-v/yii2-widgets** or other input widgets from https://github.com/kartik-v. 
   In addition, you can also use HTML 5 inputs or any custom input widget to edit your content.
7. Entirely control the way the form content is displayed in the popover. By default, the widget displays the input to be edited. In addition, you can place
   more form fields or markup before and after this default input.
8. Uses AJAX based form submission to process quick editing of data and provide a seamless user experience.
9. Uses advanced features of the [yii2-popover-x extension](http://demos.krajee.com/popover-x), to control display formats for your editable popover form. This
   uses the enhanced [bootstrap-popover-x](http://plugins.krajee.com/popover-x) jQuery plugin by Krajee.
10. Easily extend the default editable field by adding more form fields for editing before or after the generated editable input.
11. Configure your own display value irrespective of the value stored internally.
12. Configurable css styles and labels for rendering editable content according to your application or theme.
13. Ability to render and reinitialize automatically the widget via Pjax.
 
> Note: Check the [composer.json](https://github.com/kartik-v/yii2-money/blob/master/composer.json) for this extension's requirements and dependencies. 
Read this [web tip /wiki](http://webtips.krajee.com/setting-composer-minimum-stability-application/) on setting the `minimum-stability` settings for your application's composer.json.

## Demo

You can see detailed [documentation and examples](http://demos.krajee.com/editable) on usage of the extension.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).


> Note: Check the [composer.json](https://github.com/kartik-v/yii2-editable/blob/master/composer.json) for this extension's requirements and dependencies. 
Read this [web tip /wiki](http://webtips.krajee.com/setting-composer-minimum-stability-application/) on setting the `minimum-stability` settings for your application's composer.json.

Either run

```
$ php composer.phar require kartik-v/yii2-editable "@dev"
```

or add

```
"kartik-v/yii2-editable": "@dev"
```

to the ```require``` section of your `composer.json` file.

## Usage

### Editable

```php
use kartik\editable\Editable;
echo Editable::widget([
    'model' => $model, 
    'attribute' => 'rating',
    'type' => 'primary',
    'size'=> 'lg',
    'inputType' => Editable::INPUT_RATING,
    'editableValueOptions' => ['class' => 'text-success h3']
]);
```

## License

**yii2-editable** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.