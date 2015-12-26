/*!
 * @package   yii2-editable
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015
 * @version   1.7.4
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
        addCss = function ($el, css) {
            $el.removeClass(css).addClass(css);
        },
        Editable = function (element, options) {
            var self = this;
            self.$container = $(element);
            self.init(options);
            self.destroy();
            self.create();
        };

    Editable.prototype = {
        constructor: Editable,
        init: function (options) {
            var self = this, $el = self.$container;
            self.$input = $el.find('.kv-editable-input');
            self.$form = $el.find('.kv-editable-form');
            self.$value = $el.find('.kv-editable-value');
            self.$close = $el.find('.kv-editable-close');
            self.$popover = $el.find('.kv-editable-popover');
            self.$inline = $el.find('.kv-editable-inline');
            self.$btnSubmit = $el.find('button.kv-editable-submit');
            self.$btnReset = $el.find('button.kv-editable-reset');
            self.$loading = $el.find('.kv-editable-loading');
            self.$target = $el.find(options.target);
            $.each(options, function (key, value) {
                self[key] = value;
            });
        },
        htmlEncode: function(data) {
            var self = this;
            if (!self.encodeOutput) {
                return data;
            }
            if (typeof data === 'object') {
                $.each(data, function(key, value) {
                   data[key] = self.htmlEncode(value);
                });
            }
            return data.replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&apos;');
        },
        toggle: function (show, delay) {
            var self = this, $value = self.$value, $inline = self.$inline;
            delay = delay || 'fast';
            if (show) {
                if (!self.asPopover) {
                    $value.fadeOut(delay, function () {
                        $inline.fadeIn(delay);
                        if (self.target === '.kv-editable-button') {
                            addCss(self.$target, 'kv-inline-open');
                        }
                    });
                }
                return;
            }
            if (self.asPopover) {
                self.$popover.popoverX('hide');
            } else {
                $inline.fadeOut(delay, function () {
                    $value.fadeIn(delay);
                    self.$target.removeClass('kv-inline-open');
                });
            }
        },
        refreshPopover: function () {
            var self = this;
            if (self.asPopover) {
                self.$popover.popoverX('refreshPosition');
            }
        },
        raise: function($el, event, params) {
            var e = $.Event(event);
            if (params !== undefined) {
                $el.trigger(e, params);
            } else {
                $el.trigger(e);
            }
            return !e.isDefaultPrevented();
        },
        destroy: function() {
            var self = this, $target = self.target === '.kv-editable-button' ? self.$target : self.$value;
            self.$form.off('.editable');
            self.$form.find('input, select').off('.editable');
            self.$close.off('.editable');
            self.$btnSubmit.off('.editable');
            self.$btnReset.off('.editable');
            $target.off('.editable');
        },
        create: function () {
            var self = this, $form = self.$form, $btnSubmit = self.$btnSubmit, $btnReset = self.$btnReset,
                $cont = $form.parent(), $el = self.$container, $close = self.$close, $inline = self.$inline,
                $target = self.target === '.kv-editable-button' ? self.$target : self.$value,  $loading = self.$loading,
                $input = self.$input, showError, chkError = '', out = '', objActiveForm = $form.data('yiiActiveForm'),
                $parent = $input.closest('.field-' + $input.attr('id')), $message = $parent.find('.help-block'),
                $parent2 = $input.closest('.kv-editable-parent'), displayValueConfig = self.displayValueConfig,
                notActiveForm = isEmpty($parent.attr('class')) || isEmpty($message.attr('class')), settings,
                $hasEditable = $form.find('input[name="hasEditable"]'), $msgBlock = $parent2.find('.kv-help-block');
            showError = function (message) {
                if (notActiveForm) {
                    if (isEmpty($msgBlock.attr('class'))) {
                        $msgBlock = $(document.createElement("div")).attr({class: 'help-block kv-help-block'}).appendTo($parent2);
                    }
                    $msgBlock.html(message);
                } else {
                    $message.html(message);
                    addCss($message, 'kv-help-block');
                }
                addCss($parent2, 'has-error');
                $loading.hide();
                $cont.removeClass('kv-editable-processing');
            };
            $form.on('submit.editable', function (ev) {
                ev.preventDefault();
            }).on('keyup.editable', function (ev) {
                if (ev.which === 13 && self.submitOnEnter) {
                    self.raise($btnSubmit, 'click.editable');
                }
            }).on('reset.editable', function () {
                setTimeout(function () {
                    $form.data('kvEditableSubmit', false);
                    if (notActiveForm) {
                        $parent2.find('.help-block').remove();
                        $parent2.removeClass('has-error');
                    } else {
                        $parent.removeClass('has-error');
                        $message.html(' ');
                    }
                    self.refreshPopover();
                }, 200);
            });
            $form.find('input, select').on('change.editable', function () {
                if (self.raise($el, 'editableChange', [$input.val()])) {
                    self.refreshPopover();
                }
            });
            $btnReset.on('click.editable', function () {
                if (self.raise($el, 'editableReset')) {
                    $hasEditable.val(0);
                    setTimeout(function () {
                        $form[0].reset();
                    }, 200);
                }
            });
            $close.on('click.editable', function () {
                self.toggle(false);
            });
            $target.on('click.editable', function () {
                if (self.asPopover) {
                    self.toggle(true);
                    return;
                }
                var status = !self.$inline.is(':visible');
                self.toggle(status);
            });
            $btnSubmit.on('click.editable', function () {
                var $wrapper = self.asPopover ? $cont : $inline;
                addCss($wrapper, 'kv-editable-processing');
                $loading.show();
                $hasEditable.val(1);
                $form.find('.help-block').each(function () {
                    $(this).html('');
                });
                $form.find('.has-error').removeClass('has-error');
                $form.find('input, select').each(function () {
                    var $el = $(this), v = $el.val(), v1 = v;
                    if ($.isArray(v)) {
                        v1.push('-');
                    } else {
                        v1 = v1 + '-';
                    }
                    $el.val(v1);
                    self.raise($el, 'blur');
                    $el.val(v);
                    self.raise($el, 'blur');
                });
                settings = $.extend({
                    type: $form.attr('method'),
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    dataType: 'json',
                    beforeSend: function (jqXHR) {
                        if (!self.raise($el, 'editableBeforeSubmit', [jqXHR])) {
                            jqXHR.abort();
                        }
                    },
                    error: function (jqXHR, status, message) {
                        if (self.raise($el, 'editableAjaxError', [jqXHR, status, message]) && self.showAjaxErrors) {
                            showError(message);
                        }
                    },
                    success: function (data, status, jqXHR) {
                        chkError = '';
                        out = !isEmpty(data.output) ? data.output : self.htmlEncode($input.val());
                        self.refreshPopover();
                        if (!isEmpty(data.message)) {
                            if (self.raise($el, 'editableError', [$input.val(), $form, data])) {
                                showError(data.message);
                            }
                            return;
                        } else {
                            if (!isEmpty($msgBlock.attr('class'))) {
                                $parent.removeClass('has-error');
                                $msgBlock.html('').hide();
                                $message.html('');
                            }
                        }
                        $form.find('.help-block').each(function () {
                            var str = $(this).text();
                            chkError += str ? str.trim() : '';
                            if (!isEmpty(chkError)) {
                                $loading.hide();
                            }
                        });
                        if (isEmpty(chkError)) {
                            if (self.raise($el, 'editableSuccess', [$input.val(), $form, data, status, jqXHR])) {
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
                                    self.toggle(false);
                                    self.$value.html(out);
                                } else {
                                    $parent.removeClass('has-error');
                                    $message.html('');
                                    self.toggle(false);
                                    self.$value.html(out);
                                    if (objActiveForm) {
                                        $form.yiiActiveForm('destroy');
                                        $form.yiiActiveForm(objActiveForm.attributes, objActiveForm.settings);
                                    }
                                }
                            }

                        } else {
                            self.raise($el, 'editableError', [$input.val(), $form, data]);
                        }
                        $wrapper.removeClass('kv-editable-processing');
                    }
                }, self.ajaxSettings);
                setTimeout(function() {
                    if (self.raise($el, 'editableSubmit', [$input.val(), $form])) {
                        $.ajax(settings);
                    }
                }, 1000);
            });
        }
    };

    $.fn.editable = function (option) {
        var args = Array.apply(null, arguments);
        args.shift();
        return this.each(function () {
            var $this = $(this), data = $this.data('editable'), options = typeof option === 'object' && option;
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
        submitOnEnter: true,
        asPopover: true,
        encodeOutput: true
    };

    $.fn.editable.Constructor = Editable;

})(window.jQuery);