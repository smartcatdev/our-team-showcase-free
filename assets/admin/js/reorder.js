jQuery(document).ready(function ($) {

    var members = $('#ots-team-member-order').sortable({
        update: update_order
    });


    function update_order() {
        $('[name="members_order"]').val(members.sortable('serialize'));
    }

    update_order();

});