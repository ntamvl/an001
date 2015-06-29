if(typeof(jQuery.fn.wpdkSwipe)==="undefined"){+function(a){a.fn.wpdkSwipe=function(){var b=(arguments.length>0)?arguments[0]:null;if(a(this).hasClass("wpdk-form-swipe")){if(null==b){return a(this).children('input[type="hidden"]').eq(0).val()}return this.each(function(){var e=a(this);var f=e.children("span").eq(0);var g=e.children('input[type="hidden"]').eq(0);var d=wpdk_is_bool(b)?"on":"off";var c=e.triggerHandler(WPDKUIComponentEvents.SWIPE_CHANGE,[e,d]);if(false===c){return g.val()}if(wpdk_is_bool(b)){g.val("on");f.animate({marginLeft:"23px"},100,function(){e.addClass("wpdk-form-swipe-on")});e.trigger(WPDKUIComponentEvents.SWIPE_CHANGED,[e,"on"])}else{g.val("off");f.animate({marginLeft:"0"},100,function(){e.removeClass("wpdk-form-swipe-on")});e.trigger(WPDKUIComponentEvents.SWIPE_CHANGED,[e,"off"])}g.trigger("change")})}}}(jQuery)}if(typeof(window.wpdkSwitches)==="undefined"){+function(a){window.wpdkSwitches=function(){return a('.wpdk-ui-switch input[type="checkbox"]')};a.fn.wpdkSwitches=function(){return a(this).find('.wpdk-ui-switch input[type="checkbox"]')}}(jQuery)}if(typeof(jQuery.fn.wpdkSwitch)==="undefined"){+function(a){a.fn.wpdkSwitch=function(){var f=(arguments.length>0)?arguments[0]:null;var c=(arguments.length>1)?arguments[1]:null;if(!a(this).hasClass("wpdk-ui-switch")&&!a(this).hasClass("wpdk-ui-switch-input")){return a(this)}var e,d;if(a(this).hasClass("wpdk-ui-switch-input")){d=a(this);e=d.parent("div.wpdk-ui-switch")}else{d=a(this).find('input[type="checkbox"]');e=a(this)}if(null==c&&"state"==f){return b()}function b(){var g=wpdk_apply_filters("wpdk_ui_switch_state",c,e);if(null===g){return d.is(":checked")}if(true===g&&!d.is(":checked")){d.attr("checked","checked").change();wpdk_do_action("wpdk_ui_switch_changed",c,e)}else{if(d.is(":checked")){d.removeAttr("checked").change();wpdk_do_action("wpdk_ui_switch_changed",c,e)}}return e}return this.each(function(){if(a(this).hasClass("wpdk-ui-switch-input")){d=a(this);e=d.parent("div.wpdk-ui-switch")}else{d=a(this).find('input[type="checkbox"]');e=a(this)}switch(f){case"state":return b();break;case"toggle":c=!d.is(":checked");return b();break}})}}(jQuery)}if(typeof(jQuery.fn.wpdkButtonToggle)==="undefined"){+function(a){a.fn.wpdkButtonToggle=function(){return this.each(function(){var d=a(this);var c=d.data("current_state")||"false";var b=("false"==c)?"true":"false";var f=d.html();var e=d.data("alternate_label");d.data("current_state",b);d.html(e);d.data("alternate_label",f);d.removeClass("wpdk-ui-button-toggle-false wpdk-ui-button-toggle-true").addClass("wpdk-ui-button-toggle-"+b);d.trigger(WPDKUIComponentEvents.TOGGLE_BUTTON,[d,b])})};a(document).on("click","button.wpdk-ui-button-toggle",function(b){b.preventDefault();a(this).wpdkButtonToggle();return false})}(jQuery)}if(typeof jQuery.fn.wpdkShiftSelectableCheckbox==="undefined"){+function(a){a.fn.wpdkShiftSelectableCheckbox=function(){var c,b=this;b.click(function(e){if(!c){c=this;return}if(e.shiftKey){var f=b.index(this),d=b.index(c);b.slice(Math.min(f,d),Math.max(f,d)+1).attr("checked",c.checked).trigger("change")}c=this})}}(jQuery)}if(typeof(window.WPDKControls)==="undefined"){jQuery(function(a){window.WPDKControls=(function(){var e={version:"1.1.4",init:l,preferencesForm:g};function l(){m();k();d();b();i();j();f();c();h();return e}function b(){var p=(window.navigator.userAgent.indexOf("Chrome")!=-1);var n=(window.navigator.userAgent.indexOf("Firefox")!=-1);var o=(window.navigator.userAgent.indexOf("Opera")!=-1);if(p||n||o){return}a(".wpdk-ui-color-picker").each(function(){var r=a(this);var q={defaultColor:r.data("defaultColor")||"#ffffff",hide:r.data("hide")||true,palettes:r.data("palettes")||true,change:function(s,t){r.trigger("input")},clear:function(){r.trigger("clear")}};r.wpColorPicker(q)})}function i(){var o,n;a(document).on("click",'.wpdk-ui-file-media button[type="reset"]',false,function(r){r.preventDefault();var p=a(this).parent(".wpdk-ui-file-media").find('input[type="hidden"]');var q=a(this).parent(".wpdk-ui-file-media").find('input[type="text"]');p.val("");q.val("");return false});a(document).on("click",'.wpdk-ui-file-media button[type="button"],.wpdk-ui-file-media input[type="text"]',false,function(s){s.preventDefault();if(o){o.open();return}var p=a(this).parent(".wpdk-ui-file-media").find('input[type="hidden"]');var r=a(this).parent(".wpdk-ui-file-media").find('input[type="text"]');var t=p.data("title");var q=p.data("button_text");o=wp.media.frames.file_frame=wp.media({title:t,button:{text:q},multiple:false});o.on("select",function(){n=o.state().get("selection").first().toJSON();p.val(n.url);r.val(n.url)});o.open()})}function d(){wpdkSwitches().each(function(){var o=a(this);var n=o.data("on_switch");if(typeof n!=="undefined"){a.post(wpdk_i18n.ajaxURL,{action:"wpdk_action_on_switch",on_switch:n,state:o.is(":checked")},function(q){var p=new WPDKAjaxResponse(q);if(empty(p.error)){wpdk_do_action("wpdk_on_switch-"+n,p,o)}else{wpdk_do_action("wpdk_on_switch_error-"+n,p,o)}})}})}function f(){a(document).off("click","fieldset.wpdk-form-fieldset.wpdk-fieldset-collapse legend");a(document).on("click","fieldset.wpdk-form-fieldset.wpdk-fieldset-collapse legend",function(q){var r=a(this).parents("fieldset");var o=r.hasClass("wpdk-fieldset-collapse-open")?"wpdk-fieldset-collapse-open":"wpdk-fieldset-collapse-close";var n=r.hasClass("wpdk-fieldset-collapse-open")?"wpdk-fieldset-collapse-close":"wpdk-fieldset-collapse-open";if(q.altKey){var p=a(this).parents('div[data-type="wpdk-view"]');p.find("fieldset.wpdk-form-fieldset.wpdk-fieldset-collapse").removeClass(o).addClass(n)}else{r.removeClass(o).addClass(n)}})}function m(){a(document).off("click","span.wpdk-form-clear-left i");a(document).off("click",".wpdk-disable-after-click");a(document).off("click",".wpdk-form-locked");a(document).on("click","span.wpdk-form-clear-left i",false,function(){var o=a(this).prev("input");var n=o.val();var p=wpdk_apply_filters("wpdk_ui_control_clear_value","",o);if(empty(n)){o.trigger(WPDKUIComponentEvents.CLEAR_INPUT)}else{o.val("").trigger(WPDKUIComponentEvents.CLEAR_INPUT).trigger("keyup")}wpdk_do_action(WPDKUIComponentEvents.CLEAR_INPUT,o,n)});a(document).on("click",".wpdk-disable-after-click",false,function(){a(this).addClass("disabled")});a(document).on("click",".wpdk-form-locked",false,function(){var n=a(this).data("confirm");if(confirm(empty(n)?wpdk_i18n.messageUnLockField:n)){a(this).attr("class","wpdk-form-unlocked").prev("input").removeAttr("readonly")}})}function k(){a(document).off("click","span.wpdk-form-swipe span");a(document).off(WPDKUIComponents.REFRESH_SWIPE);a(document).on("click","span.wpdk-form-swipe span",false,function(){var q=a(this).parent();var o=wpdk_is_bool(q.wpdkSwipe());var p=o?"off":"on";q.trigger(WPDKUIComponentEvents.SWIPE,[q,p]);q.wpdkSwipe(p);var n=q.data("on_swipe");if(typeof n!=="undefined"){a.post(wpdk_i18n.ajaxURL,{action:"wpdk_action_on_swipe",on_swipe:n,enabled:p},function(s){var r=new WPDKAjaxResponse(s);if(empty(r.error)){}else{alert(r.error)}})}});a(document).on(WPDKUIComponents.REFRESH_SWIPE,k)}function j(){a(document).off("click",".wpdk-form-scrollable img");a(document).on("click",".wpdk-form-scrollable img",false,function(){a(this).toggleClass("wpdk-selected")})}function c(){var n=a("i.wpdk-openclose-accordion");if(n.length){n.parent().next("div.wpdk-accordion").each(function(o,p){a(this).addClass("wpdk-accordion-open").data("height",a(this).height());if(o>0){a(this).removeClass("wpdk-accordion-open")}else{a(p).css("height",a(p).data("height")+"px")}});n.click(function(){var o=a(this).parents("form");o.find("fieldset").removeClass("wpdk-accordion-open");o.find("fieldset div.wpdk-accordion").css("height","0");a(this).parents("fieldset").addClass("wpdk-accordion-open");var p=a(this).parent().next("div.wpdk-accordion");p.css("height",p.data("height")+"px")})}}function h(){a("a.wpdk-guide").click(function(){var q,p,n;q=a(this).data("title");if(typeof a(this).data("content")!="undefined"&&a(this).data("content").length>0){p=a(this).data("content")}else{n=sprintf("https://developer.wpxtre.me/api/v1/articles/%s",a(this).attr("href"));p=sprintf('<iframe class="wpdk-iframe-guide" frameborder="0" height="520" width="530" src="%s"></iframe>',n)}var o=new WPDKTwitterBootstrapModal("wpdk-guide",q,p);o.height=512;o.display();return false})}function g(n){return a("form#wpdk_preferences_view_form-"+n)}return l()})()})};