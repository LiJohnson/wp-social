<?php
/*
Plugin Name: WP-SOCIAL
Version: 0.0.1
Plugin URI:https://github.com/LiJohnson/wp-social
Description: connect to social app
Author: LiJohnson
Author URI: http://lcs.io/
*/

define('SOCIAL_MENU_SLUG', 'WP-SOCIAL');
define('SOCIAL_KEY', 'social');

add_action('admin_menu', function(){
	add_options_page('social','social','Administrator',SOCIAL_MENU_SLUG,function(){
		include __DIR__ . '/option.php';
	});
});


add_action( 'publish_post', function($id ){
	$option = get_option(SOCIAL_KEY);

	if( !has_category( $option['wb_cat'] , $post ) )return;

	$post = strip_tags( get_post_field('post_content' , $id ));
	$images = get_attached_media('image',$id);

	$url = false;
	if( $images ){
		$url = array_shift($images);
		$url = $url->guid;
	}

	if( isset($images[0]) )$url = $images[0];
//	var_dump([$post,$url]);exit();

	require_once  __DIR__ . '/MyPHP/lib/saetv2.ex.class.php';

	$client = new SaeTClientV2( $option['wb_app_key']  , $option['wb_app_secret'] , $option['wb_token']['access_token']);

	if( !$url ){
		$client->update( $post );
	}else{
		$client->upload( $post , $url);
	}

});

