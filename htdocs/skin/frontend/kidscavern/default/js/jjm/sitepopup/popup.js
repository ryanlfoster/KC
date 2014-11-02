jQuery(document).ready(function($) {
    var popup = $('#site-popup');

    if(popup) {

        popup.colorbox({
            open: true,
            inline:true,
            href:"#site-popup",
            maxWidth: '80%',
            scalePhotos: true,
            onClosed: function() {
                popup.css('display','none')
            }
        })
    }
});
