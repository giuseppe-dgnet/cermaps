$(document).ready(function() {
    $('.button-op-code-small').attr('title', function() {
        op = $(this);
        $('.button-op-code').each(function(i) {
            if ($(this).html() == op.html()) {
                op.attr('title', $(this).attr('title'));
            }
        });
    });
    $('.lps-tab > ul.tab-menu > li > a').click(function() {
        switch_tabs($(this), '.tab-content-lps');
    });

    $('.lps-tab-sheet > ul.tab-menu > li > a').click(function() {
        switch_tabs($(this), '.tab-content-lps-sheet');
    });
    $(".nav, .navtip").tipTip({
        keepAlive: false,
        delay: 0
    });

});
