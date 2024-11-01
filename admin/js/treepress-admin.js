(function($) {
    'use strict';

    $('body').on('click', '.dtbody', function (){
        if (confirm('Are you sure to delete?') == true) {
              $(this).closest('tbody').remove();
        }
        return false;
    });

    $('body').on('click', '.unlink-member', function (){
        if (confirm('Are you sure to delete?') == true) {
                var family_id = $(this).data('family_id')
                var member_id = $(this).data('member_id')
                var post_id = $(this).data('post_id')
                var key = $(this).data('key')

                var data = {
                    'action': 'unlink_function',
                    'key': key,
                    'post_id': post_id,
                    'family_id': family_id,
                    'member_id': member_id,
                    'nonce': cTPadmin.nonce,
                };

                jQuery.post(cTPadmin.ajax_url, data, function(response) {

                   window.location.reload()
                });

        }
        return false;
    });

    $(document).ready(function() {
        $('.tp-select2').select2({
            width: 'resolve'
        });
    });

    $("#dialog-member-event").dialog({
        autoOpen: false,
        height: 400,
        width: 400,
        modal: true,
        buttons: {
            Add: function() {
                var val = $("#dialog-member-event").find('table tbody .event_type').val()
                if (val) {
                    $("#dialog-member-event").find('table tbody h4 b').append(val);
                    var data = $("#dialog-member-event").find('table tbody').clone();
                    $('#table-member-events').append(data)
                }
                $("#dialog-member-event").html(function(index, html) {
                    return html.replace(/([[1-9])\d+]/g, '[xxx]');
                });
                $("#dialog-member-event").find('table tbody h4 b').text('')
                $("#dialog-member-event").dialog("close");
            },
            Close: function() {
                $("#dialog-member-event").dialog("close");
            }
        },
        close: function() {}
    });
    $("#opener-member-event-add").on("click", function() {
        $("#dialog-member-event").html(function(index, html) {
            var unix = Math.round(+new Date() / 1000);
            return html.replace(/xxx/g, unix);
        });
        $("#dialog-member-event").dialog("open");
        return false;
    });
    $("#dialog-member-note").dialog({
        autoOpen: false,
        height: 400,
        width: 350,
        modal: true,
        buttons: {
            Add: function() {
                var val = $("#dialog-member-note").find('table tbody textarea').val()
                if (val) {
                    var data = $("#dialog-member-note").find('table tbody').clone();
                    $('#table-member-notes').append(data)
                    $("#dialog-member-note").find('table tbody textarea').val('')
                    $("#dialog-member-note").dialog("close");
                }
            },
            Close: function() {
                $("#dialog-member-note").dialog("close");
            }
        },
        close: function() {}
    });
    $("#opener-member-note-add").on("click", function() {
        $("#dialog-member-note").dialog("open");
        return false;
    });
    $("#dialog-family-event").dialog({
        autoOpen: false,
        height: 400,
        width: 400,
        modal: true,
        buttons: {
            Add: function() {
                var val = $("#dialog-family-event").find('table tbody .event_type').val()
                if (val) {
                    $("#dialog-family-event").find('table tbody h4 b').append(val);
                    var data = $("#dialog-family-event").find('table tbody').clone();
                    $('#table-family-events').append(data)
                }
                $("#dialog-family-event").html(function(index, html) {
                    return html.replace(/([[1-9])\d+]/g, '[xxx]');
                });
                $("#dialog-family-event").find('table tbody h4 b').text('')
                $("#dialog-family-event").dialog("close");
            },
            Close: function() {
                $("#dialog-family-event").dialog("close");
            }
        },
        close: function() {}
    });
    $("#opener-family-event-add").on("click", function() {
        $("#dialog-family-event").html(function(index, html) {
            var unix = Math.round(+new Date() / 1000);
            return html.replace(/xxx/g, unix);
        });
        $("#dialog-family-event").dialog("open");
        return false;
    });
    $("#dialog-family-note").dialog({
        autoOpen: false,
        height: 400,
        width: 350,
        modal: true,
        buttons: {
            Add: function() {
                var val = $("#dialog-family-note").find('table tbody textarea').val()
                if (val) {
                    var data = $("#dialog-family-note").find('table tbody').clone();
                    $('#table-family-notes').append(data)
                    $("#dialog-family-note").find('table tbody textarea').val('')
                    $("#dialog-family-note").dialog("close");
                }
            },
            Close: function() {
                $("#dialog-family-note").dialog("close");
            }
        },
        close: function() {}
    });
    $("#opener-family-note-add").on("click", function() {
        $("#dialog-family-note").dialog("open");
        return false;
    });
    $("#dialog-chil").dialog({
        autoOpen: false,
        height: 400,
        width: 600,
        modal: true,
        buttons: {
            Add: function() {
                var val = $("#dialog-chil").find('.select_children').val()
                if(val){
                    var name = $("#dialog-chil").find('.select_children option:selected').attr('data-name');
                    var url = $("#dialog-chil").find('.select_children option:selected').attr('data-url');
                    var html = '<input type="hidden" name="treepress[family][chil][]" value="' + val + '"><a href="' + url + '">' + name + '</a><br/>';
                    $('td.chils').append(html)
                    $("#dialog-chil").find('.select_children').val('')
                }
            },
            close: function() {
                $("#dialog-chil").dialog("close");
            }
        },
        close: function() {}
    });
    $("#opener-chil-add").on("click", function() {
        $("#dialog-chil").dialog("open");
        return false;
    });
})(jQuery);