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
 */var initEditablePjax=function(){};!function(o){"use strict";initEditablePjax=function(t,n,i){o("#"+t).on("pjax:complete",function(){window[i]!==!0&&(o("#"+n).on("click",function(t){t.preventDefault();var n=o(this),i=o(n.attr("data-target")),a=i.data("popover-x")&&i.hasClass("in")?"toggle":n.data();"toggle"!==a?(a.$target=n,i.popoverX(a).popoverX("show").on("hide",function(){n.focus()})):i.popoverX(a).on("hide",function(){n.focus()})}),window[i]=!0)})}}(window.jQuery);