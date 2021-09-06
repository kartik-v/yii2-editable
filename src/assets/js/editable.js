/*!
 * @package   yii2-editable
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2021
 * @version   1.7.9
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

    var $h, Editable;

    $h = {
        NAMESPACE: '.editable',
        isEmpty: function (value, trim) {
            return value === null || value === undefined || value.length === 0 || (trim && $.trim(value) === '');
        },
        addCss: function ($el, css) {
            $el.removeClass(css).addClass(css);
        },
        handler: function ($element, event, callback) {
            var ev = event + $h.NAMESPACE;
            $element.off(ev).on(ev, callback);
        },
        raise: function ($el, event, params) {
            var e = $.Event(event);
            if (params !== undefined) {
                $el.trigger(e, params);
            } else {
                $el.trigger(e);
            }
            return !e.isDefaultPrevented();
        }
    };

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
            self.$targetEl = self.target === '.kv-editable-button' ? self.$target : self.$value;
            self.initActions();
        },
        initActions: function () {
            var self = this, $form = self.$form, $cont = $form.parent(), $el = self.$container, $inline = self.$inline,
                $loading = self.$loading, $input = self.$input, showError, chkError = '', out = '',
                objActiveForm = $form.data('yiiActiveForm'), $parent = $input.closest('.field-' + $input.attr('id')),
                $message = $parent.find('.help-block'), $parent2 = $input.closest('.kv-editable-parent'),
                displayValueConfig = self.displayValueConfig, $hasEditable = $form.find('input[name="hasEditable"]'),
                notActiveForm = $h.isEmpty($parent.attr('class')) || $h.isEmpty($message.attr('class')),
                $msgBlock = $parent2.find('.kv-help-block');
            showError = function (message) {
                if (notActiveForm) {
                    if (!$msgBlock.length) {
                        $msgBlock = $(document.createElement("div")).attr({class: 'help-block kv-help-block'})
                            .appendTo($parent2);
                    }
                    $msgBlock.html(message).show();
                } else {
                    $h.addCss($message, 'kv-help-block');
                    $message.html(message).show();
                }
                $h.addCss($parent2, 'has-error');
                $loading.hide();
                $cont.removeClass('kv-editable-processing');
            };
            self.actions = {
                formReset: function () {
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
                    }, self.resetDelay);
                },
                formSubmit: function (ev) {
                    ev.preventDefault();
                },
                formChange: function () {
                    if ($h.raise($el, 'editableChange', [$input.val()])) {
                        self.refreshPopover();
                    }
                },
                formKeyup: function (ev) {
                    if (ev.which === 13 && self.submitOnEnter) { // enter key pressed
                        self.submitFlag = true;
                        self.actions.submitClick();
                    }
                },
                formBlur: function (ev) {
                    var delegateTarget = ev.delegateTarget;
                    setTimeout(function () {
                        if (!delegateTarget.contains(document.activeElement) && !self.submitFlag && self.closeOnBlur) {
                            self.toggle(false);
                        }
                    }, 0);
                },
                inlineKeyup: function (ev) {
                    if (ev.which === 27) { // escape key pressed
                        self.actions.closeClick();
                    }
                },
                closeClick: function () {
                    self.toggle(false);
                },
                targetClick: function () {
                    var status;
                    self.submitFlag = false;
                    if (self.asPopover) {
                        self.toggle(true);
                        return;
                    }
                    status = !$inline.is(':visible');
                    self.toggle(status);
                },
                resetClick: function () {
                    if ($h.raise($el, 'editableReset')) {
                        $hasEditable.val(0);
                        setTimeout(function () {
                            $form[0].reset();
                        }, self.resetDelay);
                    }
                },
                submitClick: function () {
                    var $wrapper = self.asPopover ? $cont : $inline, hasFiles = false, settings;
                    $h.addCss($wrapper, 'kv-editable-processing');
                    $loading.show();
                    $hasEditable.val(1);
                    $form.find('.help-block').each(function () {
                        $(this).html('');
                    });
                    $form.find('.has-error').removeClass('has-error');
                    $form.find('input, select').each(function () {
                        var $el = $(this), v = $el.val(), v1 = v, isFile = $el.attr('type') === 'file';
                        if (!hasFiles) {
                            hasFiles = isFile;
                        }
                        if (!$el.attr('disabled')) {
                            if (isFile) {
                                $h.raise($el, 'blur');
                            } else {
                                if ($.isArray(v)) {
                                    v1.push('-');
                                } else {
                                    v1 = v1 + '-';
                                }
                                $el.val(v1);
                                $h.raise($el, 'blur');
                                $el.val(v);
                                $h.raise($el, 'blur');
                            }
                        }
                    });
                    settings = {
                        type: $form.attr('method'),
                        url: $form.attr('action'),
                        dataType: 'json',
                        beforeSend: function (jqXHR) {
                            if (!$h.raise($el, 'editableBeforeSubmit', [jqXHR])) {
                                jqXHR.abort();
                            }
                        },
                        error: function (jqXHR, status, message) {
                            if ($h.raise($el, 'editableAjaxError', [jqXHR, status, message]) && self.showAjaxErrors) {
                                showError(message);
                            }
                        },
                        success: function (data, status, jqXHR) {
                            chkError = '';
                            out = !$h.isEmpty(data.output) ? data.output : self.htmlEncode($input.val());
                            self.refreshPopover();
                            if (!$h.isEmpty(data.message)) {
                                if ($h.raise($el, 'editableError', [$input.val(), $form, data])) {
                                    showError(data.message);
                                }
                                return;
                            } else {
                                if (!$h.isEmpty($msgBlock.attr('class'))) {
                                    $parent.removeClass('has-error');
                                    $msgBlock.html('').hide();
                                    $message.html('');
                                }
                            }
                            $form.find('.help-block').each(function () {
                                var str = $(this).text();
                                chkError += str ? str.trim() : '';
                                if (!$h.isEmpty(chkError)) {
                                    $loading.hide();
                                }
                            });
                            if ($h.isEmpty(chkError)) {
                                if ($h.raise($el, 'editableSuccess', [$input.val(), $form, data, status, jqXHR])) {
                                    $loading.hide();
                                    if ($h.isEmpty(out)) {
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
                                $h.raise($el, 'editableError', [$input.val(), $form, data]);
                            }
                            $wrapper.removeClass('kv-editable-processing');
                        }
                    };
                    if (hasFiles && window.FormData) {
                        $form.attr('enctype', 'multipart/form-data');
                        settings.data = new FormData($form[0]);
                        settings.contentType = false;
                        settings.processData = false;
                        settings.cache = false;
                    } else {
                        settings.data = $form.serialize();
                    }
                    setTimeout(function () {
                        if ($h.raise($el, 'editableSubmit', [$input.val(), $form])) {
                            $.ajax($.extend(true, settings, self.ajaxSettings));
                        }
                    }, self.validationDelay);
                }
            };
        },
        htmlEncode: function (data) {
            var self = this;
            if (!self.encodeOutput || !data) {
                return data;
            }
            if (typeof data === 'object') {
                $.each(data, function (key, value) {
                    data[key] = self.htmlEncode(value);
                });
                return data;
            }
            return data.replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&apos;');
        },
        toggle: function (show) {
            var self = this, $value = self.$value, $inline = self.$inline, delay = self.animationDelay,
                selectInput = function () {
                    self.$btnSubmit.focus();
                    if (self.selectAllOnEdit) {
                        self.$input.select();
                    }
                };
            if (show) {
                if (!self.asPopover) {
                    $value.fadeOut(delay, function () {
                        $inline.fadeIn(delay, function () {
                            selectInput();
                        });
                        if (self.target === '.kv-editable-button') {
                            $h.addCss(self.$target, 'kv-inline-open');
                        }
                    });
                    return;
                }
                selectInput();
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
        destroy: function () {
            var self = this;
            self.$form.off($h.NAMESPACE);
            self.$form.find('input, select').off($h.NAMESPACE);
            self.$close.off($h.NAMESPACE);
            self.$inline.off($h.NAMESPACE);
            self.$popover.off($h.NAMESPACE);
            self.$btnSubmit.off($h.NAMESPACE);
            self.$btnReset.off($h.NAMESPACE);
            self.$targetEl.off($h.NAMESPACE);
        },
        create: function () {
            var self = this, actions = self.actions, $form = self.$form, $inline = self.$inline;
            $h.handler($form, 'reset', $.proxy(actions.formReset, self));
            $h.handler($form, 'submit', $.proxy(actions.formSubmit, self));
            $h.handler($form.find('input, select'), 'change', $.proxy(actions.formChange, self));
            $h.handler($form, 'keyup', $.proxy(actions.formKeyup, self));
            if (self.asPopover) {
                $h.handler(self.$popover, 'focusout', $.proxy(actions.formBlur, self));
            } else {
                $h.handler($inline, 'keyup', $.proxy(actions.inlineKeyup, self));
                $h.handler($inline, 'focusout', $.proxy(actions.formBlur, self));
            }
            $h.handler(self.$btnReset, 'click', $.proxy(actions.resetClick, self));
            $h.handler(self.$btnSubmit, 'click', $.proxy(actions.submitClick, self));
            $h.handler(self.$close, 'click', $.proxy(actions.closeClick, self));
            $h.handler(self.$targetEl, 'click', $.proxy(actions.targetClick, self));
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
        selectAllOnEdit: true,
        asPopover: true,
        encodeOutput: true,
        closeOnBlur: true,
        validationDelay: 500,
        resetDelay: 200,
        animationDelay: 300
    };

    $.fn.editable.Constructor = Editable;

})(window.jQuery);
