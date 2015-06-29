jQuery(function(a){window.WPXtremeAdmin=(function(){var c={version:"1.0.4",displayAjaxLoader:e,init:g,REFRESH_TABLE_ACTIONS:"refresh.wpx.table.actions"};function g(){j();h();i();d();f();if(a("body.wpxm-body-display-ajax-loader").length){k()}wpdk_add_action(c.REFRESH_TABLE_ACTIONS,d);a(document).on(c.REFRESH_TABLE_ACTIONS,d);return c}function f(){if(!a("body").hasClass("wpxm-body-remove_revisions")){return}var l=a("div#revisionsdiv");if(l.length){var m='<button type="submit" name="wpxm-button-remove-all-revisions" class="button button-primary">'+WPXtremeStrings.remove_all+" "+WPDKGlyphIcons.html(WPDKGlyphIcons.TRASH)+"</button> ";l.find("h3.hndle").off("click");l.find("h3.hndle span").replaceWith(m);l.on("click","button[name=wpxm-button-remove-all-revisions]",false,function(){return confirm(WPXtremeStrings.warning_confirm_remove_revisions)})}}function d(){if(a("body").hasClass("wpxm-body-table_actions")){a(".row-actions").each(function(l,m){a(m).removeClass("visible");var n=a(m).parent();n.prepend(a(m).clone());a(m).remove()})}}function h(){a("table").on("change",'[name="wpdk-post-publish"]',function(){var m=a(this);var l=a('input[name="screen"]').val();a.post(wpdk_i18n.ajaxURL,{action:"wpxtreme_action_post_set_publish",post_id:m.data("post_id"),status:m.is(":checked")?"publish":"draft",screen:l},function(o){var n=new WPDKAjaxResponse(o);if(empty(n.error)){m.parents("tr").replaceWith(n.data.row);wpdk_do_action(WPXtremeAdmin.REFRESH_TABLE_ACTIONS);wpdk_do_action(WPDKUIComponentEvents.REFRESH_TOOLTIP)}else{alert(n.error)}})})}function i(){if(0==a("input#wpx-per-page").length){return}a("table.wp-list-table tbody").sortable({axis:"y",cursor:"n-resize",start:function(l,m){},stop:function(){},update:function(o,p){var l=a("table.wp-list-table tbody").sortable("serialize");var m=a("input#wpx-paged").val();var n=a("input#wpx-per-page").val();a.post(wpdk_i18n.ajaxURL,{action:"wpxtreme_action_sorting_post_page",sorted:l,paged:m,per_page:n},function(q){if(!empty(q.error)){alert(q.error)}})}})}function e(l){if(l){k()}else{b()}}function k(){a(document).ajaxStart(function(){WPDK.loading(true)});a(document).ajaxComplete(function(){WPDK.loading(false)})}function b(){a(document).unbind("ajaxStart");a(document).unbind("ajaxComplete")}function j(){var l=a("span#wpxm-issue-report-status-indicator").data("status");if("2"==l){a("#issue-report").wpdkModal("show")}a("a[href*=issue-report], a[href*=wpxm_menu_wpxtreme-submenu-5], button#issue-report").on("click",function(){a(".wpdk-modal").each(function(){a(this).wpdkModal("hide")});a("#issue-report").wpdkModal("show");return false});a(document).on("click","a#wpx-issue-report-start-recording",false,function(){var o=a(this);var n=a("#issue-report");var m=a("span#wpxm-issue-report-status-indicator");o.attr("disabled","disabled");a.post(wpdk_i18n.ajaxURL,{action:"wpxtreme_action_set_issue_report",mode:1},function(q){var p=a.parseJSON(q);if(typeof(p.message)!="undefined"){alert(p.message);o.removeAttr("disabled")}else{n.on("hidden",function(){n.replaceWith(p.content);m.replaceWith(p.footer)});n.on("hidden.wpdk.wpdkModal",function(){n.replaceWith(p.content);m.replaceWith(p.footer)});n.wpdkModal("hide")}});return false});a(document).on("click","a#wpx-issue-report-stop-recording",false,function(){var o=a(this);var n=a("#issue-report");var m=a("span#wpxm-issue-report-status-indicator");o.attr("disabled","disabled");a.post(wpdk_i18n.ajaxURL,{action:"wpxtreme_action_set_issue_report",mode:2},function(q){var p=a.parseJSON(q);if(typeof(p.message)!="undefined"){alert(p.message);o.removeAttr("disabled")}else{n.on("hidden",function(){n.replaceWith(p.content);m.replaceWith(p.footer);a("#issue-report").wpdkModal("show")});n.on("hidden.wpdk.wpdkModal",function(){n.replaceWith(p.content);m.replaceWith(p.footer);a("#issue-report").wpdkModal("show")});n.wpdkModal("hide")}});return false});a(document).on("click","#wpx-issue-report-clear",false,function(){var o=a(this);var n=a("#issue-report");var m=a("span#wpxm-issue-report-status-indicator");if(confirm("Are you sure you want to clear log file and cancel issue report sending?")){o.attr("disabled","disabled");a.post(wpdk_i18n.ajaxURL,{action:"wpxtreme_action_set_issue_report",mode:3},function(q){var p=a.parseJSON(q);if(typeof(p.message)!="undefined"){alert(p.message);o.removeAttr("disabled")}else{n.on("hidden",function(){n.replaceWith(p.content);m.replaceWith(p.footer)});n.on("hidden.wpdk.wpdkModal",function(){n.replaceWith(p.content);m.replaceWith(p.footer)});n.wpdkModal("hide")}})}return false});a(document).on("click","#wpx-issue-report-send",false,function(){var o=a(this);var n=a("#issue-report");var m=a("span#wpxm-issue-report-status-indicator");o.attr("disabled","disabled");a("#wpx-issue-report-clear").attr("disabled","disabled");a.post(wpdk_i18n.ajaxURL,{action:"wpxtreme_action_send_issue_report",name:a("input#issue-report-name").val(),email:a("input#issue-report-email").val(),title:a("input#issue-report-title").val(),description:a("textarea#issue-report-own-description").val(),report:a("textarea#issue-report-description").val()},function(q){var p=a.parseJSON(q);if(typeof(p.message)!="undefined"){alert(p.message);o.removeAttr("disabled");a("#wpx-issue-report-clear").removeAttr("disabled")}else{if(typeof(p.send_result)!="undefined"){alert(p.send_result)}n.on("hidden",function(){n.replaceWith(p.content);m.replaceWith(p.footer)});n.on("hidden.wpdk.wpdkModal",function(){n.replaceWith(p.content);m.replaceWith(p.footer)});n.wpdkModal("hide")}});return false})}return g()})()});