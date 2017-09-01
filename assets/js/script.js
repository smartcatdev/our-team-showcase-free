jQuery(document).ready(function ($) {

    do_resize();

    $(window).resize(function () {
        do_resize();

    });

    function do_resize() {

        $('.ots-team-view').each(function (index, el) {

            var members = $(el).find('#sc_our_team.grid .sc_team_member, ' +
                '#sc_our_team.grid_circles .sc_team_member,' +
                '#sc_our_team.grid_circles2 .sc_team_member');

            var member_height = members.width();

            members.each(function (index, el) {
                $(el).find('.sc_team_member_inner').css({height: member_height});
            });


            members.mouseenter(function () {

                $(this).find('.sc_team_member_overlay').stop(true, false).fadeIn(440);
                $(this).find('.wp-post-image').addClass('zoomIn');
                $(this).find('.sc_team_more').addClass('show');

            }).mouseleave(function () {

                $(this).find('.sc_team_member_overlay').stop(true, false).fadeOut(440);
                $(this).find('.wp-post-image').removeClass('zoomIn');
                $(this).find('.sc_team_more').removeClass('show');

            });

        });


    }

});
