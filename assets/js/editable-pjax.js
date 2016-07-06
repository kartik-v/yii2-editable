/*!
 * @package   yii2-editable
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2016
 * @version   1.7.5
 *
 * Editable Extension - PJAX processing script for popover-x
 *
 * Built for Yii Framework 2.0
 * Author: Kartik Visweswaran
 * Year: 2015
 * For more Yii related demos visit http://demos.krajee.com
 */
var initEditablePjax = function () {
}, initEditablePopover = function () {
};
(function ($) {
    "use strict";
    initEditablePjax = function (pjaxContainer, toggleButton, initPjaxVar) {
        $('#' + pjaxContainer).on("pjax:complete", function () {
            if (window[initPjaxVar] !== true) {
                initEditablePopover(toggleButton);
                window[initPjaxVar] = true;
            }
        });
    };
    initEditablePopover = function (toggleButton) {
        var $btn = $('#' + toggleButton), target = $btn.data('target'), $dialog;
        if (!target) {
            return;
        }
        $dialog = $(target);
        $btn.off('.editable').on('click.editable', function (e) {
            e.preventDefault();
            var option = $dialog.data('popover-x') && $dialog.hasClass('in') ? 'toggle' : $btn.data();
            if (option !== 'toggle') {
                //noinspection JSPrimitiveTypeWrapperUsage
                option.$target = $btn;
                $dialog.popoverX(option).popoverX('show').on('hide', function () {
                    $btn.focus();
                });
            } else {
                $dialog.popoverX(option).on('hide', function () {
                    $btn.focus();
                });
            }
        });
    };
})(window.jQuery);