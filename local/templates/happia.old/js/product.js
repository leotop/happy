/**
 * Return device width (equal CSS-media)
 */
var viewport = function() {
    var e = window, a = 'inner';
    if ( !( 'innerWidth' in window ) ) {
        a = 'client';
        e = document.documentElement || document.body;
    }
    return e[ a+'Width' ];
}


/**
 * Additional products carousel
 */
var init_AdditionalImages_VerticalCarousel = function(el) {
    var jcarousel_moreviews = $(el);
    jcarousel_moreviews
        .on('jcarousel:reload jcarousel:create', function () {
            var carousel = $(this),
                height = $(".product-image").height();
                height < 500 ? 500 : height;
            jcarousel_moreviews.css('height', Math.ceil(height) + 'px');
        })
        .jcarousel({
            wrap: 'circular',
            vertical: true
        });
}

// $.fn.inittooltip = function() {
//     $(".amconf-image-title.tooltip").tooltip({
//         position: {my: "center+10 top-100%", at: "center top-10"},
//         tooltipClass: "product-tooltip"
//     });
// }


// $.fn.hideAllPopovers = function() {
//    $('div.amconf-image-title').each(function() {
//         $(this).popover('hide');
//         console.log("hide");
//     });  
// };



$(function () {

    // $(document).on('click', function(e) {
    //     $(this).hideAllPopovers();
    // });
    
    // Simulate click on amconf-image (which is hidden)
    // $(this).simulate_Amconf_Click();

    // Close messi and continue shopping
    // $(this).continue_Shopping();

    // Plus-Munis buttons for quantity
    // $(".product-essential .product-plus").click(function () {
    //     var input = $(this).siblings(".qty");
    //     var currentVal = parseInt(input.val());
    //     if (!currentVal || currentVal == "" || currentVal == "NaN") currentVal = 1;
    //     input.val(currentVal + 1);
    // });

    // $(".product-essential .product-minus").click(function () {
    //     var input = $(this).siblings(".qty");
    //     var currentVal = parseInt(input.val());
    //     if (!currentVal || currentVal == "" || currentVal == "NaN") currentVal = 1;
    //     if (currentVal > 1) {
    //         input.val(currentVal - 1);
    //     }
    // });


    /**
     * Scroll to the tabs on click
     */
    // $(".rating-links a").click(function () {
    //     $('html, body').animate({
    //         scrollTop: $("#customer-reviews").offset().top - 100
    //     }, 1000);
    //     return false;
    // });


    // JCarousel for additional product images
    if (viewport() >= resolution().screen_sm_min) {
        $('[id^=jcarousel-more-views-]').each(function() {
            init_AdditionalImages_VerticalCarousel(this);
        });
    }
    else {
        init_jCarousel('.jcarousel-more-views');
    }

    // $('.sku-item').click(function() {
    //     $('[id^=jcarousel-more-views-]').jcarousel('reload');
    // });

    // JCarousel for related and upsell products
    init_jCarousel('.jcarousel-related');
    init_jCarousel('.jcarousel-upsell');

    // JCarousel controls init
    $('.jcarousel-control-prev').jcarouselControl({ target: '-=1' });
    $('.jcarousel-control-next').jcarouselControl({ target: '+=1' });
});


$(window).resize(function () {

    // JCarousel
    // if (viewport() < 1200) {
    //     initAdditionalImagesCarousel();
    // }
});
