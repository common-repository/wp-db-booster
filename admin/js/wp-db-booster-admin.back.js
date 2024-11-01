(function ($) {
    'use strict';

    $(window).load(function () {
        var total = 0;
        var types = ["autodraft", "transient_feed", "draft", "comment_meta",
            "post_meta", "relationships", "revisions", "pingbacks",
            "transient_options", "trackbacks", "spam_comment", "trash_comment"];

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
                        if (response == 0)
                            jQuery('#btnclean_' + type).addClass('disabled').removeClass('button-primary').addClass('button');
                        /*jQuery.each(types, function (index, item) {
                         wpdbboo_info(item);
                         });*/
                        $.post(ajax_call.ajax_url, {'action': 'all_info'}, function (response) {
                            jQuery('#sum_issues').text(response['issues']);
                            jQuery('#sum_minnor').text(response['minnor']);
                            jQuery('#issues_comment_meta').text(response['comment_meta']);
                            jQuery('#issues_post_meta').text(response['post_meta']);
                            jQuery('#issues_relationships').text(response['relationships']);
                            jQuery('#issues_transient_options').text(response['transient_options']);
                        });

                    },
                    error: function (response) {
                        $('#loader_' + type).hide();
                    }
                });
            }
        });

        jQuery('#wpdbboo_btn_deletelog').click(function () {
            if (jQuery('#wpdbboo_action').val() == 0) {
                alert('Please select action first');
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
                    total += parseInt(response);
                    jQuery('#sum_issues').text(total);

                },
                error: function (response) {
                    jQuery('#loader_' + type).hide();
                }
            });
        }

        function wpdbboo_cleanup(type,async) {
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
                    ;
                    //wpdbboo_info(type);
                    $.post(ajax_call.ajax_url, {'action': 'all_info'}, function (response) {
                        jQuery('#sum_issues').text(response['issues']);
                        jQuery('#sum_minnor').text(response['minnor']);
                        jQuery('#issues_comment_meta').text(response['comment_meta']);
                        jQuery('#issues_post_meta').text(response['post_meta']);
                        jQuery('#issues_relationships').text(response['relationships']);
                        jQuery('#issues_transient_options').text(response['transient_options']);
                    });

                },
                error: function (response) {

                }
            });
        }

        // DELETE SELECTED
        jQuery('#wpdbboo_btn_action1').click(function (event) {
            if (jQuery('#wpdbboo_action1').val() == 0) {
                alert('Please select action first');
                return;
            }
            var confirm_selection = confirm(ajax_call.confirm_text);
            if (confirm_selection == true) {
                var arr_types = [];
                jQuery.each(types, function (index, type) {
                    if (jQuery('#wpdbboo_chk_' + type).attr("checked")) {
                        //console.log(item);
                        wpdbboo_cleanup(type,false);
                        jQuery('#wpdbboo_chk_' + type).delay( 200 );
                    }
                });
            }
        });

        // DELETE SELECTED MINOR
        jQuery('#wpdbboo_btn_action2').click(function (event) {
            if (jQuery('#wpdbboo_action2').val() == 0) {
                alert('Please select action first');
                return;
            }
            var confirm_selection = confirm(ajax_call.confirm_text);
            if (confirm_selection == true) {

                jQuery.each(types, function (index, type) {
                    if (jQuery('#wpdbboo_chk_' + type).attr("checked")) {
                        console.log(type);
                        wpdbboo_cleanup(type);
                    }
                });
            }
        });

    });

})(jQuery);
