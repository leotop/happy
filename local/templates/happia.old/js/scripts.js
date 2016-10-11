function isHandheldDevices() {
	var check = false;
	(function(a){if(/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
	return check;
}
var IS_HANDHELD = isHandheldDevices();
var eventType = (IS_HANDHELD) ? 'touchstart' : 'click';

var resolution = function() {
	return {
		screen_xs_min: 480,
		screen_xs_max: 767,
		screen_sm_min: 768,
		screen_sm_max: 991,
		screen_md_min: 992,
		screen_md_max: 1199,
		screen_lg_min: 1200
	}
}

/**
 * Help-function for responsive JCarousel
 */
var jCarousel_Size = function(width, sizes) {
    for(var i = 0; i < sizes.length; i++) {
        // console.log(width + " " + sizes[i][0] + " " + sizes[i][1]);
        if(width >= sizes[i][0]) {
            width = width / sizes[i][1];
            break;
        }
    }
    return width;
}

/**
 * JCarousel standard initialisation
 */
var init_jCarousel = function(el) {
    var jcarousel_related = $(el);
    console.log('jcarosuel');
    jcarousel_related
        .on('jcarousel:reload jcarousel:create', function () {
            var carousel = $(this),
                carouselWidth = carousel.innerWidth();
            var itemWidth = jCarousel_Size(carouselWidth, [[1200, 6], [1000, 5], [900, 4], [800, 3], [600, 3], [400, 3], [100, 3]]);
            carousel.jcarousel('items').css('width', Math.ceil(itemWidth) + 'px');
        })
        .jcarousel({
            wrap: 'circular'
        });
};


// Replacement of the function CatalogHorizontal.prototype.changeSectionPicure from
// happia/components/bitrix/menu/catalog_horizontal/script.js
// to remove tons of code from HTML and make it more clean
// and add video functionality
var changeMenuPicure = function(element, itemId) {
	var curLi = BX.findParent(element, {className: "bx-nav-1-lvl"}); // /bitrix/js/main/core/core.js
	if (!curLi)
		return;

	if(document.getElementById(itemId) == null)
		return;

	var imgDescObj = curLi.querySelector("[data-role='desc-img-block']");
	if (!imgDescObj)
		return;
	
	var imgObj = BX.findChild(imgDescObj, {tagName: "img"}, true, false); // /bitrix/js/main/core/core.js
	if (imgObj)
		imgObj.src = document.getElementById(itemId).getAttribute("data-picture");
	
	var linkObj = BX.findChild(imgDescObj, {tagName: "a"}, true, false);
	if (linkObj)
		linkObj.href = element.href;

	// Hide picture if there´s an iframe in description
	if(document.getElementById(itemId).getAttribute("data-video") === 'true') {
		$(imgObj).addClass("hide");
	}
	else {
		$(imgObj).removeClass("hide");
	}

	var descObj = BX.findChild(imgDescObj, {tagName: "p"}, true, false);
	if (descObj)
		descObj.innerHTML = document.getElementById(itemId).getAttribute("data-desc");

	// Call youtubeImage function
	if(document.getElementById(itemId).getAttribute("data-video") === 'true') {
		youtubeImage('.bx-top-nav .youtube');
	}
};

/**
** Youtube image instead of iframe
*/
var youtubeImage = function(el) {
	// http://www.sitepoint.com/faster-youtube-embeds-javascript/
    // Based on the YouTube ID, we can easily find the thumbnail image
    //$(this).css('background-image', 'url(http://i.ytimg.com/vi/' + this.id + '/sddefault.jpg)');
    /* add thumbnail as an img tag to container instead of a background */
    $this = $(el);
    $this.empty();

    $this.append($('<img/>', {'src': 'http://i.ytimg.com/vi/' + $this.attr('id') + '/mqdefault.jpg'}));

    // Overlay the Play icon to make it look like a video player
    $this.append($('<div/>', {'class': 'play'}));

    $(document).delegate('#'+$this.attr('id'), 'click', function() {
        // Create an iFrame with autoplay set to true
        var iframe_url = "https://www.youtube.com/embed/" + $this.attr('id') + "?autoplay=1&autohide=1";
        if ($this.data('params')) iframe_url+='&'+$this.data('params');

        // The height and width of the iFrame should be the same as parent
        //var iframe = $('<iframe/>', {'frameborder': '0', 'src': iframe_url, 'width': $this.width(), 'height': $this.height() })
        /* create iframe without sizing constraints */
        var iframe = $('<iframe/>', {'frameborder': '0', 'src': iframe_url, 'height': $this.height(), 'class': 'embed-responsive-item' });

        // Replace the YouTube thumbnail with YouTube HTML5 Player
        //$this.replaceWith(iframe);
        /* append youtube's iframe in container and add loaded class */
        $this.html(iframe).addClass('loaded');	         
    });
}


/**
** Social share buttons
*/
var Share = {
    /**
     * http://habrahabr.ru/post/156185/
     * Показать пользователю дилог шаринга в сооветствии с опциями
     * Метод для использования в inline-js в ссылках
     * При блокировке всплывающего окна подставит нужный адрес и ползволит браузеру перейти по нему
     *
     * @example <a href="" onclick="return share.go(this)">like+</a>
     *
     * @param Object _element - элемент DOM, для которого
     * @param Object _options - опции, все необязательны
     */
    go: function(_element, _options) {
        var
            self = Share,
            link = null,
            options = $.extend(
                {
                    type:       'vk',    		// тип соцсети
                    url:        location.href,  // какую ссылку шарим
                    count_url:  location.href,  // для какой ссылки крутим счётчик
                    title:      $(_element).data('title') || document.title, // заголовок шаринга
                    image:      $(_element).data('image') ||  '',      // картинка шаринга
                    text:       $(_element).data('text') || '', // текст шаринга
                },
                $(_element).data(), // Если параметры заданы в data, то читаем их
                _options            // Параметры из вызова метода имеют наивысший приоритет
            );


        if (self.popup(link = self[options.type](options)) === null) {
            // Если не удалось открыть попап
            if ( $(_element).is('a') ) {
                // Если это <a>, то подставляем адрес и просим браузер продолжить переход по ссылке
                $(_element).prop('href', link);
                return true;
            }
            else {
                // Если это не <a>, то пытаемся перейти по адресу
                location.href = link;
                return false;
            }
        }
        else {
            // Попап успешно открыт, просим браузер не продолжать обработку
            return false;
        }
    },

    // ВКонтакте
    vk: function(_options) {
        var options = $.extend({
                url:    location.href,
                title:  '',
                image:  '',
                text:   '',
            }, _options);

        return 'http://vkontakte.ru/share.php?'
            + 'url='          + encodeURIComponent(options.url)
            + '&title='       + encodeURIComponent(options.title)
            + '&description=' + encodeURIComponent(options.text)
            + '&image='       + encodeURIComponent(options.image)
            + '&noparse=false';
    },

    // Одноклассники
    ok: function(_options) {
        var options = $.extend({
                url:    location.href,
                text:   '',
            }, _options);

        return 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1'
            + '&st.comments=' + encodeURIComponent(options.text)
            + '&st._surl='    + encodeURIComponent(options.url);
    },

    // Facebook
    fb: function(_options) {
        var options = $.extend({
                url:    location.href,
                title:  document.title,
                image:  '',
                text:   '',
            }, _options);

        return 'http://www.facebook.com/sharer.php?s=100'
            + '&p[title]='     + encodeURIComponent(options.title)
            + '&p[summary]='   + encodeURIComponent(options.text)
            + '&p[url]='       + encodeURIComponent(options.url)
            + '&p[images][0]=' + encodeURIComponent(options.image);
    },

    // Твиттер
    tw: function(_options) {
        var options = $.extend({
                url:        location.href,
                count_url:  location.href,
                title:      document.title,
            }, _options);

        return 'http://twitter.com/share?'
            + 'text='      + encodeURIComponent(options.title)
            + '&url='      + encodeURIComponent(options.url)
            + '&counturl=' + encodeURIComponent(options.count_url);
    },

    // Mail.Ru
    mr: function(_options) {
        var options = $.extend({
                url:    location.href,
                title:  document.title,
                image:  '',
                text:   '',
            }, _options);

        return 'http://connect.mail.ru/share?'
            + 'url='          + encodeURIComponent(options.url)
            + '&title='       + encodeURIComponent(options.title)
            + '&description=' + encodeURIComponent(options.text)
            + '&imageurl='    + encodeURIComponent(options.image);
    },

    // Открыть окно шаринга
    popup: function(url) {
        return window.open(url,'','toolbar=0,status=0,scrollbars=1,width=626,height=436');
    }
}

/**
** Update content of mini-cart in header
*/
var updateBasket = function() {
    $.ajax({
        url: "/bitrix/templates/happia/ajax.handler.php",
        type: "POST",
        dataType: "html",
        data: "PAGE=BASKET",
        success: function(data){
		$('.mini-cart').html(data)
        }
    });
	
}

$(function () {

	$(document).on('click', '.social-share', function(){
	    Share.go(this);
	});
	
	// Fancybox
	if($(".fancybox").length > 0) {
		$(".fancybox").fancybox();
	}
	if($(".fancybox-various").length > 0) {
		$(".fancybox-various").fancybox({
			maxWidth	: 800,
			maxHeight	: 600,
			fitToView	: false,
			width		: '70%',
			height		: '70%',
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none'
		});		
	}

	// Back to top
	BX.ready(function(){
		var upButton = document.querySelector('[data-role="eshopUpButton"]');
		BX.bind(upButton, "click", function(){
			var windowScroll = BX.GetWindowScrollPos();
			(new BX.easing({
				duration : 500,
				start : { scroll : windowScroll.scrollTop },
				finish : { scroll : 0 },
				transition : BX.easing.makeEaseOut(BX.easing.transitions.quart),
				step : function(state){
					window.scrollTo(0, state.scroll);
				},
				complete: function() {
				}
			})).animate();
		})
	});


	$(".false-link").click(function() {
		return false;
	});


	/**
	** Dropdown cart in header
	*/
    var top_timer;
    $('body').on('mouseenter', '.quick-cart, .cart-details', function() {
        if (top_timer) {
            clearTimeout(top_timer);
            top_timer = null;
        }
        top_timer = setTimeout(function () {
            $('.cart-details').slideDown();
        }, 100);
    });
    $('body').on('mouseleave', '.quick-cart, .cart-details', function() {
        if (top_timer) {
            clearTimeout(top_timer);
            top_timer = null;
        }
        top_timer = setTimeout(function () {
            $('.cart-details').slideUp();
        }, 100);    
    });
    $('.quick-cart, .cart-details').on('touchstart', function () {
        $('.cart-details').slideToggle();
    });
    // update mini-cart on "Buy"-click
    // $("[id*=_buy_link]").click(updateBasket);



	/**
	** Dropdown Search
	*/
	$(".search-button .btn, .toggle-search").click(function() {
		$(this).toggleClass("active");
		$(".search-field").slideToggle();
        $(".search-field input[type=text]").focus();
		$(".close-search").toggleClass('hide');
	});
	$(".close-search").click(function() {
		$(".search-button .btn, .toggle-search").removeClass("active");
		$(".search-field").slideUp();
		$(this).addClass('hide');
		return false;
	});


	/**
	** Dropdown map
	*/
	$(".address.msk, .address.msk a").click(function() {
		$(".address-open.msk").slideToggle();
		$(".address-open.spb").hide();
		youtubeImage('.address-open .youtube');
		return false;
	});
	$(".address.spb, .address.spb a").click(function() {
		$(".address-open.spb").slideToggle();
		$(".address-open.msk").hide();
		return false;
	});
	$(".toggle-address").click(function() {
		$(".address-both").slideToggle();
		return false;
	});
	$(".address-close").click(function() {
		$(this).parent(".address-open").slideUp();
		return false;
	});


	// Mobile menu
	$(document).on('click touchend', '.canvas-slid #sns_mainnav .parent > a', function () {
        $(this).parent().toggleClass("open");
        $(this).next('.megamenu-col').toggleClass('open');
        return false;
    });
    $(document).on('click touchend', '.canvas-slid #sns_mainnav .have-children > .mega-title > a', function () {
    	if($(this).closest(".have-spetitle").find('.megamenu-col').length != 0) {
    		$(this).parent().toggleClass("open");
            $(this).parent().siblings('.megamenu-col').toggle();
            return false;
    	}
    });
    $("#sns_mainnav .have-spetitle").each(function() {
        if($(this).find('.megamenu-col').length != 0) {
    		$(this).addClass("have-children");
    	} 
    	if($(this).find('iframe').length != 0) {
    		$(this).addClass("have-video");
    	}      	
    });


    // Typography
    $('.help').popover({
        "placement": "top",
        "trigger": "hover",
        "html": true
    });



    // Homepage video
    // youtubeImage($('.front-video .youtube'));



	// if (Modernizr.touch) {
	//     // console.log('Touch Screen');
	//     // var myScroll = new IScroll('#sns_mainnav > div', { mouseWheel: true });

	//     // open dropdown menu on first click and open url on second click
	//     $('html').click(function() {
	//         $('#main-menu > li > a').data("clicks", true);
	//     });

	//     $('#main-menu').click(function(e) {
	//         e.stopPropagation();
	//     });

	//     $('#main-menu > li > a').data("clicks", true);

	//     $('#main-menu > li > a').click(function(e) {
	//         var link = $(this);
	//         var clicks = link.data('clicks');
	//         if (clicks) {
	//             e.preventDefault();
	//         }
	//         $('#main-menu > li > a').not(link).data("clicks", true);
	//         link.data("clicks", !clicks);
	//     });

	// } else {
	//     // console.log('No Touch Screen');
	// }





	/**
	** On scroll
	*/
	$(window).scroll(function () {
		if($(this).scrollTop() > 300) {
			$('#back-to-top').fadeIn();    
		} else {
			$('#back-to-top').fadeOut();
		}
    });

});
