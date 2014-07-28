/*!
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014
 * @package yii2-editable
 * @version 1.0.0
 *
 * Editable Extension jQuery plugin
 *
 * Built for Yii Framework 2.0
 * Author: Kartik Visweswaran
 * Year: 2014
 * For more Yii related demos visit http://demos.krajee.com
 */
(function ($) {
    var isEmpty = function (value, trim) {
        return value === null || value === undefined || value == []
            || value === '' || trim && $.trim(value) === '';
    };

    var Editable = function (element, options) {
        this.$element = $(element);
        this.init(options);
        this.listen();
    };

    Editable.prototype = {
        constructor: Editable,
        init: function (options) {
            var self = this;
            self.$container = $('#' + options.containerId);
            self.$form = self.$container.find('.kv-editable-form');
            self.$value = self.$container.find('.kv-editable-value');
            self.$popover = self.$container.find('.kv-editable-popover');
            self.$btnSubmit = self.$container.find('button.kv-editable-submit');
            self.$btnReset = self.$container.find('button.kv-editable-reset');
            self.$loading = self.$container.find('.kv-editable-loading');
            self.$target = self.$container.find(options.target);
            self.valueIfNull = options.valueIfNull;
            self.placement = options.placement;
        },
        refreshPosition: function() {
            var self = this, $dialog = self.$popover, placement = self.placement, $target = self.$target,
                actualWidth = $dialog[0].offsetWidth, actualHeight = $dialog[0].offsetHeight,
                position, pos = $.extend({}, ($target.position()), {
                    width: $target[0].offsetWidth, height: $target[0].offsetHeight
                });
            switch (placement) {
                case 'bottom':
                    position = {top: pos.top + pos.height, left: pos.left + pos.width / 2 - actualWidth / 2}
                    break;
                case 'top':
                    position = {top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2}
                    break;
                case 'left':
                    position = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth}
                    break;
                case 'right':
                    position = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width}
                    break;
            }
            $dialog.css(position);
        },
        listen: function () {
            var self = this, $form = self.$form, $btnSubmit = self.$btnSubmit, $btnReset = self.$btnReset,
                $cont = $form.parent(), $popover = self.$popover, $loading = self.$loading, $el = self.$element, 
                valueIfNull = self.valueIfNull, $parent = $el.closest('.field-' + $el.attr('id')),
                $parent2 = $el.parent(), $message = $parent.find('.help-block'),
                $hasEditable = $form.find('input[name="hasEditable"]'), 
                notActiveForm = isEmpty($parent.attr('class')) || isEmpty($message.attr('class'));
            $btnReset.on('click', function (ev) {
                $hasEditable.val(0);
                setTimeout(function () {
                    $form[0].reset();
                }, 200);
            });
            $form.on('reset', function (ev) {
                setTimeout(function () {
                    $form.data('kvEditableSubmit', false);
                    if (notActiveForm) {
                        $parent2.find('.help-block').remove();
                        $parent2.removeClass('has-error');
                    } else {
                        $parent.removeClass('has-error');
                        $message.html(' ');
                    }
                    $popover.popoverX('refreshPosition');
                }, 200);
            });
            $form.find('input, select').on('change', function(ev) {
                $popover.popoverX('refreshPosition');
            });
            $btnSubmit.on('click', function (ev) {
                $cont.addClass('kv-editable-processing');
                $loading.show();
                $hasEditable.val(1);
                $form.find('input, select').each(
                    function(index){  
                        var $input = $(this);
                        $input.trigger('change');
                    }
                );
                var chkError = '', objActiveForm = self.$form.data('yiiActiveForm');
                $.ajax({
                    type: $form.attr('method'),
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function (data) {
                        var out = !isEmpty(data.output) ? data.output : self.$element.val();
                        $popover.popoverX('refreshPosition');
                        if (!isEmpty(data.message)) {
                            var $msgBlock = $parent2.find('.help-block');
                            if (notActiveForm) {
                                if (isEmpty($msgBlock.attr('class'))) {
                                    $msgBlock = $(document.createElement("div")).attr({class: 'help-block'}).appendTo($parent2);
                                }
                                $msgBlock.html(data.message);
                                $parent2.addClass('has-error');
                            } else {
                                $parent.addClass('has-error');
                                $message.html(data.message);
                            }
                            $loading.hide();
                            $cont.removeClass('kv-editable-processing');
                            return;
                        }
                        $form.find('.help-block').each(function () {
                            chkError = $(this).text();
                            if (!isEmpty(chkError.trim())) {
                                $loading.hide();
                                return;
                            } 
                        });
                        if (isEmpty(chkError.trim())) {
                            $loading.hide();
                            if (isEmpty(out)) {
                                out = valueIfNull;
                            }
                            if (notActiveForm) {
                                $parent2.find('.help-block').remove();
                                $parent2.removeClass('has-error');
                            } else {
                                $parent.removeClass('has-error');
                                $message.html('');
                                $popover.popoverX('hide');
                                self.$value.html(out);
                                if (objActiveForm != undefined) {
                                    $form.yiiActiveForm('destroy');
                                    $form.yiiActiveForm(objActiveForm.attributes, objActiveForm.settings);
                                }
                            }
                        }
                        $cont.removeClass('kv-editable-processing');
                    }
                });
            });
        }
    };

    //Editable plugin definition
    $.fn.editable = function (option) {
        var args = Array.apply(null, arguments);
        args.shift();
        return this.each(function () {
            var $this = $(this),
                data = $this.data('editable'),
                options = typeof option === 'object' && option;

            if (!data) {
                $this.data('editable', (data = new Editable(this, $.extend({}, $.fn.editable.defaults, options, $(this).data()))));
            }

            if (typeof option === 'string') {
                data[option].apply(data, args);
            }
        });
    };

    $.fn.editable.defaults = {
        containerId: '',
        valueIfNull: '<em>(not set)</em>',
        placement: 'right'
    };

})(window.jQuery);