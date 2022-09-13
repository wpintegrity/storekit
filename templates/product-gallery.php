<?php

global $product;

$html = '<div id="storekit-product-gallery">';

$html .= '<div class="flexslider"><ul class="slides">';

$featured_video_url   = get_post_meta( $product->get_id(), 'storekit_video_url', true );

if( !empty( $featured_video_url ) ){
    $pattern    = '/(\.be\/|\.com\/watch\?v=|\/embed\/)(.{1,11})/';
    preg_match( $pattern, $featured_video_url, $matches, PREG_OFFSET_CAPTURE );
    $get_video_id = array_pop( $matches );
    $featured_video_thumb = 'https://i.ytimg.com/vi/' . $get_video_id[0] . '/mqdefault.jpg';
    
    $html .= '<li class="featured-video" data-thumb='. esc_attr( $featured_video_thumb ) .' data-video_id=' . esc_attr( $get_video_id[0] ) . '><div id="video-content"></div></li>';
}

$featured_image_id              = get_post_thumbnail_id( $product->get_id(), 'full' );
$featured_image_info            = wp_get_attachment_image_src( $featured_image_id, 'full' );
$featured_image_src             = get_the_post_thumbnail_url( $product->get_id(), 'full' );
$gallery_thumbnail              = wc_get_image_size( 'gallery_thumbnail' );
$thumbnail_size                 = array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] );
$featured_image_thumbnail_src   = get_the_post_thumbnail_url( $product->get_id(), $thumbnail_size );

if( has_post_thumbnail() ){
    $html .= sprintf( '<li data-thumb="%s"><a href="%s" data-size='. $featured_image_info[1] . 'x' .$featured_image_info[2] .'><img src="%s"/></a></li>', $featured_image_thumbnail_src, $featured_image_src, $featured_image_src );
}

$gallery_image_ids = $product->get_gallery_image_ids();

foreach( $gallery_image_ids as $gallery_image_id ){
    $gallery_thumbnail              = wc_get_image_size( 'gallery_thumbnail' );
    $thumbnail_size                 = array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] );
    $gallery_image                  = wp_get_attachment_image( $gallery_image_id , $thumbnail_size );
    $gallery_image_thumbnail_src    = wp_get_attachment_image_url( $gallery_image_id, $thumbnail_size );
    $gallery_image_src              = wp_get_attachment_image_url( $gallery_image_id, 'full' );
    $gallery_image_info            = wp_get_attachment_image_src( $gallery_image_id, 'full' );
    $html .= sprintf( '<li data-thumb="%s"><a href="%s" data-size="%dx%d"><img src="%s"/></a></li>', $gallery_image_thumbnail_src, $gallery_image_src, $gallery_image_info[1], $gallery_image_info[2], $gallery_image_src );
}

$html .= '</ul></div></div>';

echo $html;