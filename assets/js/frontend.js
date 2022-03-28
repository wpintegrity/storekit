let jq = jQuery.noConflict();

jq( document ).ready( function(){

    /**
     * 
     * Enables FlexSlider
     * 
     */
    jq( '.flexslider' ).flexslider({
        directionNav: false,
        slideshow: false,
        controlNav: "thumbnails"
    });

    /**
     * 
     * Enables Magnific Popup
     * 
     */
    jq( 'ul.product-gallery-img' ).each(function() {
        jq(this).magnificPopup({
            delegate: 'a',
            type: 'image',
            gallery: {
              enabled:true
            }
        });
    });

    // let setHeight   = jq( 'body.single-product .product-gallery img:eq(1)' ).height();
    // let viewport    = jq( 'body.single-product .product-gallery' );
    // if( setHeight ){
    //     viewport.height( setHeight );
    // }

    /**
     * 
     * Adjust Video Gallery Image Thumbnail
     * 
     */
    let sliderHeight  = jq( 'body.single-product .product-gallery img:eq(1)' ).height();
    let videoframe  = jq( 'body.single-product .product-gallery li.youtube-popup img' );
    if ( sliderHeight ){
        videoframe.height( sliderHeight ).css( 'object-fit', 'cover' );
    }


    // jq( 'body.single-product .product-gallery' ).find( 'iframe' ).height( sliderHeight );

    /**
     * 
     * Manage YouTube Video URL
     * 
     */
    let get_yt_video_url                = jq( 'li.youtube-popup a' ).attr( 'href' ).match( /youtube.com\/watch\?v=(.{11})/ );
    let get_yt_video_id                 = get_yt_video_url.pop();
    let get_yt_video_featured_img_src   = 'https://img.youtube.com/vi/' + get_yt_video_id + '/hqdefault.jpg';
    let set_yt_video_featured_img       = jq( 'li.youtube-popup img' ).attr( 'src', get_yt_video_featured_img_src );
    
    jq( 'li.youtube-popup' ).attr( 'data-thumb', get_yt_video_featured_img_src );
    jq( 'ol.flex-control-nav.flex-control-thumbs img:eq(0)' ).attr( 'src', get_yt_video_featured_img_src );   

    /**
     * 
     * Adjust Slider Control Thumbnail for YouTube Video Image 
     * 
     */
    if( set_yt_video_featured_img ){

        setTimeout(function(){
            let sliderThumbHeight  = jq( 'ol.flex-control-nav.flex-control-thumbs img:eq(1)' ).height();

            jq( 'ol.flex-control-nav.flex-control-thumbs img:eq(0)' ).css({
                'height'    : sliderThumbHeight,
                'object-fit': 'cover',
            }); 
        }, 100)
          
    }

    /**
     * 
     * Added YouTube Video Player button 
     * 
     */
    let video_play_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>';

    jq( '.youtube-popup a img' ).before( video_play_svg);
    
} );