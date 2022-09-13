var jq = jQuery.noConflict();

jq( document ).ready( function() {

    /* Initializing the flexslider plugin. */
    jq('.flexslider').flexslider({
        animation: "slide",
        controlNav: "thumbnails",
        animationLoop: false,
        slideshow: false,
        directionNav: false
    });

    jq('.flex-prev').empty();
    jq('.flex-next').empty();
    
    /* jQuery event listener that listens for a click on the reset variations button. When
    the button is clicked, it triggers a custom event on the product gallery. */
    jq( '.reset_variations' ).on( 'click', () => {
        var form            = jq('.variations_form.cart'),
            product        = form.closest( '.product' ),
            product_gallery = product.find( '#storekit-product-gallery' );
            
        product_gallery.trigger( 'woocommerce_gallery_reset_slide_position' );
    })

    /* jQuery event listener that listens for a custom event on the product gallery. When the
    custom event is triggered, it resets the flexslider to the first slide. */
    jq( '#storekit-product-gallery' ).on( 'woocommerce_gallery_reset_slide_position', () => {
        var target = jq( '.flexslider' );
        target.flexslider(0);
    } )

    /* Checking if the product has a featured video. If it does, it sets the height
    of the video to match the height of the first image in the gallery. */
    var hasFeaturedVideo = jq( '#storekit-product-gallery' ).find( '.featured-video' );

    if( hasFeaturedVideo.length > 0 ){

        setTimeout( () => {

            var previousThumbWidth  = jq('.flex-control-nav li:eq(1) img').width(),
                previousThumbHeight = jq('.flex-control-nav li:eq(1) img').height(),
                previousImgHeight   = jq( '.flexslider' ).find('ul.slides li:eq(1) img').height();

            jq('.flex-control-nav li:eq(0) img').css({
                'width'     : previousThumbWidth + 'px',
                'height'    : previousThumbHeight + 'px',
                'object-fit': 'cover'
            });

            jq( '#video-content' ).css( 'height', previousImgHeight );
            jq( '.featured-video' ).css( 'height', previousImgHeight );

        }, 200 )

    }

    // Loads the IFrame Player API code asynchronously.
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    var player,
        getVideoId = jq( '.featured-video' ).data( 'video_id' );
    window.onYouTubeIframeAPIReady = function () {
        player = new YT.Player( 'video-content', {
            videoId: getVideoId,
            playerVars: {
                'playsinline': 1,
                'rel': 0,
            },
            events: {
                'onReady': onPlayerReady
            }
        });
    }

    // The API will call this function when the video player is ready.
    function onPlayerReady(event) {
        event.target.stopVideo();
    }

    jq( '#storekit-product-gallery' ).prepend( '<a href="#" class="storekit-gallery_trigger">🔍</a>' );
    var gallery = jq( '#storekit-product-gallery .flexslider' ),
        /* Function for getting the items that are in the gallery. It is then returning
        those items. */
        getItems = function(){

            var items = [];

            gallery.find( 'a' ).each( function() {
                var href    = jq( this ).attr('href'),
                    size    = jq( this ).data('size').split('x'),
                    width  = size[0],
                    height = size[1];    

                var item = {
                    src: href,
                    w: width,
                    h: height
                }

                items.push(item);

            } )
                            
            var videoWrapper = gallery.find( '.featured-video' );
            
            if( videoWrapper.length > 0 ){
                var item = {
                    html: '<div class="storekit-video-wrapper"><iframe width="640" height="360" src="https://www.youtube.com/embed/'+ getVideoId +'?playsinline=1&amp;rel=0&amp;enablejsapi=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>'
                }

                items.unshift(item);
            }

            return items;

        }

    /* jQuery event listener that is listening for a click on the gallery. When the gallery
    is clicked, it is preventing the default action. */
    gallery.on( 'click', 'a', function(e){
        e.preventDefault();
    } )

    /* Function for getting the items that are in the gallery. It is then returning those
    items. */
    var galleryItems = getItems();

    /* Initializing the PhotoSwipe lightbox. */
    var pswp = jq('.pswp')[0];
    jq( '#storekit-product-gallery' ).on( 'click', 'a.storekit-gallery_trigger', function( e ) {
        e.preventDefault();
        
        var $index = jq( this ).index();
        var options = {
            index: $index,
            bgOpacity: 0.7,
            showHideOpacity: true
        }
        
        // Initialize PhotoSwipe
        var lightBox = new PhotoSwipe( pswp, PhotoSwipeUI_Default, galleryItems, options );
        
        lightBox.init();

        /* A listener that is listening for the close event on the lightbox. When the lightbox
        is closed, it is resetting the iframe src attribute. */
        lightBox.listen( 'close', function() {
            jq('iframe').each(function() {
                jq(this).attr('src', jq(this).attr('src'));
            });
        });
        
        /* A listener that is listening for the beforeChange event on the lightbox. When the
        lightbox is changed, it is resetting the iframe src attribute. */
        lightBox.listen( 'beforeChange', function() {
            jq('iframe').each(function() {
                jq(this).attr('src', jq(this).attr('src'));
            });
        });
    });

    /* jQuery function that is looping through each anchor tag in the gallery. It is then
    getting the href attribute of the anchor tag and setting it as the url for the zoom function. */
    jq( '#storekit-product-gallery a' ).each( function(){
        var hrefAttr = jq(this).attr('href');
        jq(this).zoom({
            url: hrefAttr
        })
    } )

} );
