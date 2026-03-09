jQuery(document).ready(function($){

    /************** FlexSlider Plugin *********************/
    $('.flexslider').flexslider({
        animation : 'fade',
        controlNav : false,
        nextText : '',
        prevText : '',
    });

    $('.flex-caption').addClass('animated bounceInDown');

    $('.flex-direction-nav a').on('click', function() {
        $('.flex-caption').removeClass('animated bounceInDown');
        $('.flex-caption').fadeIn(0).addClass('animated bounceInDown');
    });

    /************** LightBox *********************/
    $('[data-rel="lightbox"]').lightbox();

    /************** Go Top *********************/
    $('#go-top').click(function(event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, 800);
        return false;
    });

    /************** Responsive Navigation *********************/
    $('.toggle-menu').click(function(){
        $('.menu').stop(true,true).toggle();
        return false;
    });
    $(".responsive-menu .menu a").click(function(){
        $('.responsive-menu .menu').hide();
    });

    /************** Scrollable Schools List *********************/
    const $scrollContainer = $('.schools-scroll');
    const $scrollLeftBtn = $('.scroll-left');
    const $scrollRightBtn = $('.scroll-right');
    const scrollAmount = 250; // pixels per click

    $scrollLeftBtn.on('click', function(){
        $scrollContainer.animate({ scrollLeft: '-=' + scrollAmount }, 400);
    });

    $scrollRightBtn.on('click', function(){
        $scrollContainer.animate({ scrollLeft: '+=' + scrollAmount }, 400);
    });

});