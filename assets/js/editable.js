/*!
 * @package   yii2-editable
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015
 * @version   1.7.2
 *
 * Editable Extension jQuery plugin
 *
 * Built for Yii Framework 2.0
 * Author: Kartik Visweswaran
 * Year: 2015
 * For more Yii related demos visit http://demos.krajee.com
 */
(function ($) {
    "use strict";
    var isEmpty = function (value, trim) {
            return value === null || value === undefined || value.length === 0 || (trim && $.trim(value) === '');
        },
        Editable = function (element, options) {
            this.$container = $(element);
            this.init(options);
            this.listen();
        };

    Editable.prototype = {
        constructor: Editable,
        init: function (options) {
            var self = this;
            self.$input = self.$container.find('.kv-editable-input');
            self.$form = self.$container.find('.kv-editable-form');
            self.$value = self.$container.find('.kv-editable-value');
            self.$popover = self.$container.find('.kv-editable-popover');
            self.$btnSubmit = self.$container.find('button.kv-editable-submit');
            self.$btnReset = self.$container.find('button.kv-editable-reset');
            self.$loading = self.$container.find('.kv-editable-loading');
            self.$target = self.$container.find(options.target);
            $.each(options, function (key, value) {
                self[key] = value;
            });
        },
        refreshPosition: function () {
            var self = this, $dialog = self.$popover, placement = self.placement, $target = self.$target,
                actualWidth = $dialog[0].offsetWidth, actualHeight = $dialog[0].offsetHeight,
                position, pos = $.extend({}, ($target.position()), {
                    width: $target[0].offsetWidth, height: $target[0].offsetHeight
                });
            switch (placement) {
                case 'bottom':
                    position = {top: pos.top + pos.height, left: pos.left + pos.width / 2 - actualWidth / 2};
                    break;
                case 'top':
                    position = {top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2};
                    break;
                case 'left':
                    position = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth};
                    break;
                case 'right':
                    position = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width};
                    break;
                default:
                    throw ("Invalid editable placement '" + placement + "'!");
            }
            $dialog.css(position);
        },
        listen: function () {
            var self = this, $form = self.$form, $btnSubmit = self.$btnSubmit, $btnReset = self.$btnReset,
                $cont = $form.parent(), $el = self.$container, $popover = self.$popover, $loading = self.$loading, $input = self.$input,
                $parent = $input.closest('.field-' + $input.attr('id')), $parent2 = $input.closest('.kv-editable-parent'),
                $message = $parent.find('.help-block'), displayValueConfig = self.displayValueConfig, settings,
                $hasEditable = $form.find('input[name="hasEditable"]'), showError, chkError = '', out = '',
                objActiveForm = self.$form.data('yiiActiveForm'), $msgBlock = $parent2.find('.kv-help-block'),
                notActiveForm = isEmpty($parent.attr('class')) || isEmpty($message.attr('class'));
            showError = function (message) {
                if (notActiveForm) {
                    if (isEmpty($msgBlock.attr('class'))) {
                        $msgBlock = $(document.createElement("div")).attr({class: 'help-block kv-help-block'}).appendTo($parent2);
                    }
                    $msgBlock.html(message);
                } else {
                    $message.html(message).removeClass('kv-help-block').addClass('kv-help-block');
                }
                $parent2.addClass('has-error');
                $loading.hide();
                $cont.removeClass('kv-editable-processing');
            };
            $form.on('submit', function (ev) {
                ev.preventDefault();
            }).on('keyup', function (ev) {
                if (ev.which === 13 && self.submitOnEnter) {
                    $btnSubmit.trigger('click');
                }
            }).on('reset', function () {
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
            $form.find('input, select').on('change', function () {
                $popover.popoverX('refreshPosition');
                $el.trigger('editableChange', [$input.val()]);
            });
            $btnReset.on('click', function () {
                $hasEditable.val(0);
                setTimeout(function () {
                    $form[0].reset();
                }, 200);
                $el.trigger('editableReset');
            });
            $btnSubmit.on('click', function () {
                $cont.addClass('kv-editable-processing');
                $loading.show();
                $hasEditable.val(1);
                $form.find('input, select').each(function () {
                    $(this).trigger('change');
                });
                settings = $.extend(self.ajaxSettings, {
                    type: $form.attr('method'),
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    dataType: 'json',
                    error: function (request, status, message) {
                        if (self.showAjaxErrors) {
                            showError(message);
                        }
                        $el.trigger('editableAjaxError', [request, status, message]);
                    },
                    success: function (data) {
                        out = !isEmpty(data.output) ? data.output : $input.val();
                        $popover.popoverX('refreshPosition');
                        if (!isEmpty(data.message)) {
                            showError(data.message);
                            $el.trigger('editableError', [$input.val(), $form, data]);
                            return;
                        } else {
                            if (!isEmpty($msgBlock.attr('class'))) {
                                $parent.removeClass('has-error');
                                $msgBlock.html('').hide();
                                $message.html('');
                            }
                        }
                        $form.find('.help-block').each(function () {
                            chkError = $(this).text();
                            if (!isEmpty(chkError.trim())) {
                                $loading.hide();
                            }
                        });
                        if (isEmpty(chkError.trim())) {
                            $loading.hide();
                            if (isEmpty(out)) {
                                out = self.valueIfNull;
                            } else {
                                if (displayValueConfig[out] !== undefined) {
                                    out = displayValueConfig[out];
                                }
                            }
                            if (notActiveForm) {
                                $parent2.find('.help-block').remove();
                                $parent2.removeClass('has-error');
                                $message.html('');
                                $popover.popoverX('hide');
                                self.$value.html(out);
                            } else {
                                $parent.removeClass('has-error');
                                $message.html('');
                                $popover.popoverX('hide');
                                self.$value.html(out);
                                if (objActiveForm) {
                                    $form.yiiActiveForm('destroy');
                                    $form.yiiActiveForm(objActiveForm.attributes, objActiveForm.settings);
                                }
                            }
                            $el.trigger('editableSuccess', [$input.val(), $form, data]);
                        } else {
                            $el.trigger('editableError', [$input.val(), $form, data]);
                        }
                        $cont.removeClass('kv-editable-processing');
                    }
                });
                $.ajax(settings);
                $el.trigger('editableSubmit', [$input.val(), $form]);
            });
        }
    };

    $.fn.editable = function (option) {
        var args = Array.apply(null, arguments);
        args.shift();
        return this.each(function () {
            var $this = $(this),
                data = $this.data('editable'),
                options = typeof option === 'object' && option;
            if (!data) {
                data = new Editable(this, $.extend({}, $.fn.editable.defaults, options, $(this).data()));
                $this.data('editable', data);
            }
            if (typeof option === 'string') {
                data[option].apply(data, args);
            }
        });
    };

    $.fn.editable.defaults = {
        valueIfNull: '<em>(not set)</em>',
        placement: 'right',
        displayValueConfig: {},
        ajaxSettings: {},
        showAjaxErrors: true,
        submitOnEnter: true
    };

    $.fn.editable.Constructor = Editable;
})(window.jQuery);