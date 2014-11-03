jQuery(document).ready(function($) {
    var popup = $('#site-popup');

    if(popup) {

        $('#site-popup').colorbox({
            open: true,
            inline:true,
            href:"#site-popup",
            maxWidth: '80%',
            scalePhotos: true,
            onClosed: function() {
                popup.css('display','none')
            }
        })

        //prevent clicks on popup from triggering close function
        popup.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
        })

        // on click of link go to url in parent window
        popup.on('click', 'a', function(e) {
            e.preventDefault();
            var goTo = $(this).context.href;
            parent.window.document.location = goTo;
        })

        // allow newsletter form submissions
        popup.on('click', 'button', function(e) {
            $(this).closest('form').submit();
        })

    }
});

var resizeTimer;
function resizeColorBox()
{
    if (resizeTimer) clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
        if (jQuery('#cboxOverlay').is(':visible')) {
            jQuery.colorbox.resize({width:'90%', height:'90%'});
        }
    }, 300)
}

// Resize Colorbox when resizing window or changing mobile device orientation
jQuery(window).resize(resizeColorBox);
window.addEventListener("orientationchange", resizeColorBox, false);