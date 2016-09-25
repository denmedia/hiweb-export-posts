/**
 * Created by hiweb on 20.09.2016.
 */
jQuery(document).ready(function ($) {

    $('select[data-change="post_type"]').change(function () {
        var tr = $(this).closest('tr[data-post-type]');
        var post_type_name = tr.attr('data-post-type');
        tr.find('select[data-reload="post_type"]').fadeOut();
        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: {action: 'hw_export_posts_html', post_type_name: tr.find(('select[data-change="post_type"]')).val()},
            success: function (data) {
                tr.find('select[data-reload="post_type"]').fadeIn().html(data);
            },
            error: function (data) {
                console.warn(data);
            },
            anyway: function () {
                tr.find('select[data-reload="post_type"]').fadeIn();
            }
        });
    });

    $('#import-process').on('click', function (e) {
        $('#import-settings-process').fadeIn();
        $('#import-settings-form').serializeArray();
    })
});