/*!
 * @package   yii2-editable
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015
 * @version   1.7.4
 *
 * Editable Extension - PJAX processing script for popover-x
 *
 * Built for Yii Framework 2.0
 * Author: Kartik Visweswaran
 * Year: 2015
 * For more Yii related demos visit http://demos.krajee.com
 */
var initEditablePjax = function() {
};
(function ($) {
    "use strict";
    initEditablePjax = function (pjaxContainer, toggleButton, initPjaxVar) {
        $('#' + pjaxContainer).on("pjax:complete", function () {
            if (window[initPjaxVar] !== true) {
                $('#' + toggleButton).on('click', function (e) {
                    e.preventDefault();
                    var $btn = $(this), $dialog = $($btn.attr('data-target')),
                        option = $dialog.data('popover-x') && $dialog.hasClass('in') ? 'toggle' : $btn.data();
                    if (option !== 'toggle') {
                        option.$target = $btn;
                        $dialog
                            .popoverX(option)
                            .popoverX('show')
                            .on('hide', function () {
                                $btn.focus();
                            });
                    } else {
                        $dialog
                            .popoverX(option)
                            .on('hide', function () {
                                $btn.focus();
                            });
                    }
                });
                window[initPjaxVar] = true;
            }
        });
    };
})(window.jQuery);