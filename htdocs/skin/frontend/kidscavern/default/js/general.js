$jq = jQuery.noConflict();

var SCROLL_LENGTH = 400;

$jq(document).ready(function(){
    $jq('ul#menu li.drop').hover(
        function(eventObj) {
            $jq(this)
                .addClass("over")
                .children("div")
                    .addClass("hover")
                    ;
            $jq('div.hover .menu-item').find('div').each(function(){
                $jq(this).css("height", $jq('div.hover').height());
            });
        },
        function(eventObj) {
            $jq(this)
                .removeClass("over")
                .children("div")
                    .removeClass("hover")
                    ;
        }
    );
	
    $jq(".products-grid li").equalHeights();
});

$jq(window).load(function(){
    $jq('.promo-banners ul li:last-child').addClass('last');
    $jq('.brand-logos ul li:last-child').addClass('last');

    /* Responsive Table class */
    $jq('table').wrap('<div class="responsive-table"></div>');

    $jq('.mobile-menu').on('click','span', function() {
        $jq('#menu').slideToggle('fast');
    });

    homeBlocks = $jq('#home-blocks').bxSlider({
        pager: false
    });

    resizeSettings();
    
    var width = 0;
    $jq('.brand-logos ul li').each(function(){
        width += $jq(this).outerWidth();
    });
    $jq('.brand-logos ul').width(width);

    
    $jq('.scroll-left').click(function() {
        var currentLeft = $jq('.brand-logos ul').css("margin-left");
        currentLeft = parseInt(currentLeft.substr(0, currentLeft.indexOf("px")));
        var newLeft = currentLeft - SCROLL_LENGTH;
        var lastLeft = $jq('.brand-logos ul li:last').position().left - SCROLL_LENGTH;
        var contWidth = $jq('.brand-logos').width();
        var ulWidth = $jq('.brand-logos ul').outerWidth();
        if (((newLeft * -1) + contWidth ) > ulWidth) {
            newLeft = (ulWidth - contWidth + $jq('.scroll-right').outerWidth()) * -1;
        }
        $jq('.brand-logos ul').animate( { marginLeft : newLeft + "px" }, 250);
    });
    
    $jq('.scroll-right').click(function() {
        var currentLeft = $jq('.brand-logos ul').css("margin-left");
        currentLeft = parseInt(currentLeft.substr(0, currentLeft.indexOf("px")));
        var newLeft = currentLeft + SCROLL_LENGTH;
        if (newLeft > 0) {
            newLeft = 0;
        }
        $jq('.brand-logos ul').animate( { marginLeft : newLeft + "px" }, 250);
    });
});

$jq(window).resize(resizeSettings);

/*
 * Things to set for mobile/desktop features/styles
 */
function resizeSettings() {
    //get menu values
    var menuHeight = $jq('h1').filter('.logo').find('a').height();
    var mobileMenu = $jq('.mobile-menu');
    var menuStatus = $jq('#menu').css('display');
    var windowWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
    var layeredNavStatus = $jq('.layered-mobile-nav').css('display');
    var mobileSearch = $jq('.form-search');
    var mobileSearchStatus = $jq('.mobile-search').css('display');

    //set height of mobile menu to that of h1 next to it
    mobileMenu.css('height',menuHeight+'px');

    if(menuStatus === 'none') {
        //homeBlocks.reloadSlider();
        $jq('.nav-container').css('width',windowWidth);
    } else {
        homeBlocks.destroySlider();
    }

    if(layeredNavStatus === 'block') {
        $jq('.layered-mobile-nav').on('click', function() {
            $jq('.block-layered-nav .block-content').slideToggle();
        });
    } else {
        $jq('.block-layered-nav .block-content').css('display','block');
        $jq('.nav-container').css('width',windowWidth);
    }

    if(mobileSearchStatus === 'block') {
        $jq('.mobile-search').on('click','span', function(el) {
            mobileSearch.toggleClass('animate');
        })
    }

};
