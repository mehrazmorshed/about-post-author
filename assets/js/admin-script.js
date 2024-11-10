jQuery(document).ready(function($) {
    // Tab navigation
    $('.about-post-author-tabs ul li a').on('click', function(e) {
        e.preventDefault();
        var tab_id = $(this).attr('href');

        $('.about-post-author-tabs ul li').removeClass('active');
        $(this).parent().addClass('active');

        $('.about-post-author-tab-content').hide();
        $(tab_id).show();
    });

    // Live preview (optional functionality)
    $('#about-post-author-settings-form input, #about-post-author-settings-form select').on('change', function() {
        var bgColor = $('#about-post-author-background-color').val();
        var textColor = $('#about-post-author-text-color').val();
        var fontSize = $('#about-post-author-font-size').val();
        var fontFamily = $('#about-post-author-font-family').val();
        var padding = $('#about-post-author-padding').val();

        $('#about-post-author-preview-box').css({
            'background-color': bgColor,
            'color': textColor,
            'font-size': fontSize + 'px',
            'font-family': fontFamily,
            'padding': padding + 'px'
        });
    });
});
