<?php
	require_once __DIR__ . '/MyPHP/lib/saetv2.ex.class.php';

	if( isset($_POST[SOCIAL_KEY]) ){
		update_option( SOCIAL_KEY , $_POST);
	}
	$option = get_option(SOCIAL_KEY);

	$wbOauth = new SaeTOAuthV2 ( $option['wb_app_key'], $option['wb_app_secret'] );
	$wbOauthUrl =   menu_page_url(SOCIAL_MENU_SLUG,false) . '&wb_oauth=true' ;

	if( isset($_GET['wb_oauth']) && isset( $_GET['code'] ) ){
		$option['wb_token'] = $wbOauth->getAccessToken ( 'code', ['code' => $_GET['code'] , 'redirect_uri' => $wbOauthUrl ] );
		update_option(SOCIAL_KEY,$option);
		echo '<script>location.href="'. menu_page_url(SOCIAL_MENU_SLUG,false) .'";</script>';
		exit();
	}

?>

<form action="" method="post">
	<table class="form-table">
		<tr class="alternate" >
			<td rowspan="2">微博</td>
			<th><lable for="wb-app-key">app key</lable></th>
			<td><input id="wb-app-key" type="text"  class="regular-text" name="wb_app_key" value="<?=$option['wb_app_key']?>"></td>
			<td rowspan="2" > 
				<?php wp_dropdown_categories( ['name' => 'wb_cat' , 'selected' => $option['wb_cat']] ); ?> 
				<a href="<?=$wbOauth->getAuthorizeURL($wbOauthUrl)?>" >aouth</a>
				<?=$option['wb_token']['access_token']?>
			</td>
		</tr>
		<tr class="alternate" >
			<th>
				<lable for="wb-app-secret">app secret</lable>
			</th>
			<td><input type="text" id="wb-app-secret" class="regular-text" name="wb_app_secret" value="<?=$option['wb_app_secret']?>"></td>
		</tr>
		<tr>
			<td colspan="4"><input type="submit" name="social" value="update" class="button button-primary"></td>
		</tr>
	</table>
</form>