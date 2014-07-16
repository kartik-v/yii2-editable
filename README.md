yii2-editable
=============

An enhanced editable widget for Yii 2.0 that allows easy editing of displayed data with numerous configuration possibilities.

## Features  

### _Extension is under development_

> NOTE: This extension depends on the [kartik-v/yii2-widgets](https://github.com/kartik-v/yii2-widgets) extension which in turn depends on the 
[yiisoft/yii2-bootstrap](https://github.com/yiisoft/yii2/tree/master/extensions/bootstrap) extension. Check the 
[composer.json](https://github.com/kartik-v/yii2-editable/blob/master/composer.json) for this extension's requirements and dependencies. 
Note: Yii 2 framework is still in active development, and until a fully stable Yii2 release, your core yii2-bootstrap packages (and its dependencies) 
may be updated when you install or update this extension. You may need to lock your composer package versions for your specific app, and test 
for extension break if you do not wish to auto update dependencies.

## Demo
You can see detailed [documentation and examples](http://demos.krajee.com/editable) on usage of the extension.

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

```

## License

**yii2-editable** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.