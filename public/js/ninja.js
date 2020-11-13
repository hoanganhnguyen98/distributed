//sidebar active
$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
});


// close toast notification
$( "#colseToastButton" ).click(function() {
    $('.toast').toast('hide');
});
