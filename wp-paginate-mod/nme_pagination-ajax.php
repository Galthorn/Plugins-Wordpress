<?php
if ( !isset($wp_did_header) ) {
    $wp_did_header = true;
    require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
    wp();
    require_once( ABSPATH . WPINC . '/template-loader.php' );
} 
echo urldecode($_POST['info']);

//if( function_exists( 'wp_paginate_nme' ) ) wp_paginate_nme(); ?>
