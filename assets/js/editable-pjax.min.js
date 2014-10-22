/*!
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014
 * @package yii2-editable
 * @version 1.4.0
 *
 * Editable Extension - PJAX processing script for popover-x
 *
 * Built for Yii Framework 2.0
 * Author: Kartik Visweswaran
 * Year: 2014
 * For more Yii related demos visit http://demos.krajee.com
 */var initEditablePjax=function(o,t,n){$("#"+o).on("pjax:complete",function(){window[n]!==!0&&($("#"+t).on("click",function(o){o.preventDefault();var t=$(this),n=$(t.attr("data-target")),a=n.data("popover-x")&&n.hasClass("in")?"toggle":t.data();"toggle"!==a?(a.$target=t,n.popoverX(a).popoverX("show").on("hide",function(){t.focus()})):n.popoverX(a).on("hide",function(){t.focus()})}),window[n]=!0)})};