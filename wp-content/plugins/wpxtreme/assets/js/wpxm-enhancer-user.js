jQuery(function(a){window.WPXtremeUsers=(function(){var b={version:"1.0.1",init:d};function d(){c();return b}function c(){a("table").on("change",'[name="wpxm-user-enabled"]',function(){var e=a(this);a.post(wpdk_i18n.ajaxURL,{action:"wpxtreme_action_user_set_status",user_id:e.data("user_id"),state:e.is(":checked")},function(g){var f=new WPDKAjaxResponse(g);if(empty(f.error)){e.parents("tr").replaceWith(f.data.row);wpdk_do_action(WPXtremeAdmin.REFRESH_TABLE_ACTIONS);wpdk_do_action(WPDKUIComponentEvents.REFRESH_TOOLTIP)}else{alert(f.error)}})})}return d()})()});