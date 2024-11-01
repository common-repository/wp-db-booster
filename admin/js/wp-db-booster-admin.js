(function ($) {
    'use strict';

    $(function() {
        var total = 0;
        var types_main = ["autodraft", /*"transient_feed",*/
            "trash_post",
            //"draft",
            "orphan_comment_meta",
            "duplicated_comment_meta",
            "duplicated_post_meta",
            "orphan_user_meta",
            "duplicated_user_meta",
            "orphan_post_meta",
            "orphan_term_meta",
            "duplicated_term_meta",
            "orphan_term_relationships",
            "unused_tags",
            "revisions",
            "pingbacks",
            /*"transient_options",*/
            "trackbacks",
            "unaproved_comment",
            "spam_comment",
            "trash_comment",
            "oembed_post_meta"];
        var types_minnor = ["transient_options"];

        jQuery(".hovertip").tooltip_tip({placement: "right"});

        jQuery("#wpdbboo_select_all1").click(function () {
            if (jQuery("#wpdbboo_select_all1").prop("checked") == true) {
                jQuery("input:checkbox[name=\"wpdbboo_chk1[]\"]").attr("checked", "checked");
            }
            else {
                jQuery("input:checkbox[name=\"wpdbboo_chk1[]\"]").removeAttr("checked", "checked");
            }
        });

        jQuery("#wpdbboo_select_all2").click(function () {
            if (jQuery("#wpdbboo_select_all2").prop("checked") == true) {
                jQuery("input:checkbox[name=\"wpdbboo_chk2[]\"]").attr("checked", "checked");
            }
            else {
                jQuery("input:checkbox[name=\"wpdbboo_chk2[]\"]").removeAttr("checked", "checked");
            }
        });


        jQuery('.wbdbboo-btnclean').click(function (event) {

            var confirm_selection = confirm(ajax_call.confirm_text);
            if (confirm_selection == true) {
                total = 0;
                var type = jQuery(this).data('type');
                $('#loader_' + type).show();
                jQuery.ajax({
                    url: ajax_call.ajax_url,
                    type: 'post',
                    data: {
                        'action': 'cleanup',
                        'type': type      // We pass php values differently!
                    },
                    success: function (response) {
                        jQuery('#loader_' + type).hide();
                        jQuery('#issues_' + type).text(response);
                        if (response == 0) {
                            jQuery('#btnclean_' + type).addClass('disabled').removeClass('button-primary').addClass('button');
                            ohSnap('Operation successful.', {'duration':'3000', 'color':'blue'});
                        }
                        if (type == 'trash_comment' || type == 'spam_comment') {
                            wpdbboo_info('orphan_comment_meta');
                            wpdbboo_info('duplicated_comment_meta');
                        }
                        if (type == 'orphan_term_meta') wpdbboo_info('duplicated_term_meta');
                        if (type == 'duplicated_term_meta') wpdbboo_info('orphan_term_meta');

                        //jQuery.each(types_main, function (index, item) {
                        //   wpdbboo_info(item);
                        //})
                        all_info();
                    },
                    error: function (response) {
                        $('#loader_' + type).hide();
                    }
                });
            }
        });


        // Delete Log
        jQuery('#wpdbboo_btn_deletelog').click(function () {
            if (jQuery('#wpdbboo_action').val() == 0) {
                alert(ajax_call.confirm_alert);
                return;
            }
            var confirm_selection = confirm(ajax_call.confirm_text);
            if (confirm_selection == true) {
                jQuery.ajax({
                    url: ajax_call.ajax_url,
                    type: 'post',
                    data: {
                        'action': 'deletelog',
                        'type': jQuery('#wpdbboo_action').val()
                    },
                    success: function (response) {
                        location.reload();
                    }
                });
            }
        });

        function wpdbboo_info(type) {
            $('#loader_' + type).show();
            jQuery.ajax({
                url: ajax_call.ajax_url,
                type: 'post',
                data: {
                    'action': 'info',
                    'type': type      // We pass php values differently!
                },
                success: function (response) {
                    jQuery('#loader_' + type).hide();
                    jQuery('#issues_' + type).text(response);
                    if (response == 0) jQuery('#btnclean_' + type).addClass('disabled').removeClass('button-primary').addClass('button');
                    else   jQuery('#btnclean_' + type).removeClass('disabled').addClass('button-primary').removeClass('button');
                    //total += parseInt(response);
                    //jQuery('#sum_issues').text(total);

                },
                error: function (response) {
                    jQuery('#loader_' + type).hide();
                }
            });
        }

        function wpdbboo_cleanup(type, async) {
            async = async || true;
            jQuery.ajax({
                url: ajax_call.ajax_url,
                type: 'post',
                async: true,
                data: {
                    'action': 'cleanup',
                    'type': type      // We pass php values differently!
                },
                success: function (response) {
                    jQuery('#issues_' + type).text(response);
                    //total += parseInt(response);
                    //jQuery('#sum_issues').text(total);
                    jQuery('#wpdbboo_select_all1').removeAttr("checked", "checked");
                    jQuery('#wpdbboo_select_all2').removeAttr("checked", "checked");
                    jQuery('#wpdbboo_chk_' + type).removeAttr("checked", "checked");
                    if (response == 0) {
                        jQuery('#btnclean_' + type).addClass('disabled').removeClass('button-primary').addClass('button');
                    }
                    all_info();
                },
                error: function (response) {

                }
            });
        }

        // DELETE SELECTED MAIN
        jQuery('#wpdbboo_btn_action1').click(function (event) {
            if (jQuery('#wpdbboo_action1').val() == 0) {
                alert(ajax_call.confirm_alert);
                return;
            }
            var confirm_selection = confirm(ajax_call.confirm_text);
            if (confirm_selection == true) {
                var arr_types = [];
                jQuery.each(types_main, function (index, type) {
                    if (jQuery('#wpdbboo_chk_' + type).attr("checked")) {
                        arr_types.push(type);
                        //wpdbboo_cleanup(type,false);
                        //jQuery('#wpdbboo_chk_' + type).delay( 200 );
                    }
                });
                jQuery.ajax({
                    url: ajax_call.ajax_url,
                    type: 'post',
                    async: true,
                    data: {
                        'action': 'cleanup_all',
                        'type': arr_types
                    },
                    success: function (response) {
                        jQuery('#wpdbboo_select_all1').removeAttr("checked", "checked");
                        jQuery('#wpdbboo_select_all2').removeAttr("checked", "checked");
                        jQuery.each(response, function (type, count) {
                            jQuery('#wpdbboo_chk_' + type).removeAttr("checked", "checked");
                            jQuery('#issues_' + type).text(count);
                            if (count == 0) {
                                jQuery('#btnclean_' + type).addClass('disabled').removeClass('button-primary').addClass('button');
                            }
                        });
                        ohSnap('Operation successful.', {'duration':'3000', 'color':'blue'});
                        all_info();
                        /*jQuery('#issues_' + type).text(response);
                         //total += parseInt(response);
                         //jQuery('#sum_issues').text(total);
                         jQuery('#wpdbboo_select_all1').removeAttr("checked", "checked");
                         jQuery('#wpdbboo_select_all2').removeAttr("checked", "checked");
                         jQuery('#wpdbboo_chk_' + type).removeAttr("checked", "checked");
                         if (response == 0) {
                         jQuery('#btnclean_' + type).addClass('disabled').removeClass('button-primary').addClass('button');
                         }
                         ;
                         //wpdbboo_info(type);
                         all_info();
                         */

                    },
                    error: function (response) {

                    }
                });
            }
        });

        // DELETE SELECTED MINOR
        jQuery('#wpdbboo_btn_action2').click(function (event) {
            if (jQuery('#wpdbboo_action2').val() == 0) {
                alert(ajax_call.confirm_alert);
                return;
            }
            var confirm_selection = confirm(ajax_call.confirm_text);
            if (confirm_selection == true) {

                jQuery.each(types_minnor, function (index, type) {
                    if (jQuery('#wpdbboo_chk_' + type).attr("checked")) {
                        //console.log(type);
                        wpdbboo_cleanup(type);
                    }
                });
            }
        });

        // Update all dashboard main info
        function all_info() {
            $.post(ajax_call.ajax_url, {'action': 'all_info'}, function (response) {
                jQuery('#sum_issues').text(response['issues']);
                if (!response['issues']) jQuery('.issues-stat').removeClass('red').addClass('green');
                else  jQuery('.issues-stat').removeClass('green').addClass('red');
                jQuery('#sum_minnor').text(response['minnor']);
                jQuery('#issues_comment_meta').text(response['comment_meta']);
                jQuery('#issues_post_meta').text(response['post_meta']);
                jQuery('#issues_relationships').text(response['relationships']);
                jQuery('#issues_transient_options').text(response['transient_options']);
            });
        }


    });

})(jQuery);
