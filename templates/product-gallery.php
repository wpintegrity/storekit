<?php

    /**
     * 
     * Product gallery image template to show images and video on the single product page
     * 
     */

    global $product;

    $attachment_ids                         = $product->get_gallery_image_ids();
    $product_featured_image                 = get_the_post_thumbnail( $product->get_id(), 'full' );
    $product_featured_image_thumbnail_src   = get_the_post_thumbnail_url( $product->get_id(), 'thumbnail' );
    $product_featured_image_url             = get_the_post_thumbnail_url( $product->get_id(), 'full' );


    $yt_video_url = get_post_meta( $product->get_id(), '_wctk_product_video_url', true );

    echo '<div class="product-gallery"><div class="flexslider">';

    $htmlvideo = '';

    $htmlvideo = '<li class="youtube-popup"><a href='. $yt_video_url .' class="mfp-iframe"><img src=""></a></li>';

    $html = '<ul class="slides product-gallery-img">';

    if( !empty( $yt_video_url ) ){
        $html .= $htmlvideo;
    }

    if( has_post_thumbnail() ){
        $html .= sprintf( '<li data-thumb="%s" class="woocommerce-product-gallery__image"><a href="%s">%s</a></li>', $product_featured_image_thumbnail_src, $product_featured_image_url, $product_featured_image );
    }

    foreach( $attachment_ids as $attachment_id ){
        $product_gallery_image                  = wp_get_attachment_image( $attachment_id, 'full' );
        $product_gallery_image_src              = wp_get_attachment_image_url( $attachment_id, 'full' );
        $product_gallery_image_thumbnail_src    = wp_get_attachment_image_url( $attachment_id, 'thumbnail' );

        $html .= sprintf( '<li data-thumb="%s" class="woocommerce-product-gallery__image"><a href="%s">%s</a></li>', $product_gallery_image_thumbnail_src, $product_gallery_image_src, $product_gallery_image );
    }

    $html .= '</ul>';

    echo $html;

    do_action( 'woocommerce_product_thumbnails' );

    echo '</div></div>';