<?php
/**
 *
 * SmartAdapt plugin functions.
 *
 * Provides some  functions, which are used  to extend theme functionality.
 *
 * @package    WordPress
 * @subpackage SmartAdapt
 * @since      SmartAdaptPro 1.0
 */


/**
 * Adds new profile fields
 * @param $profile_fields
 *
 * @return array
 */
function smartadapt_contact_link($profile_fields) {

	// Add new fields
	$profile_fields['twitter'] = 'Twitter Username';
	$profile_fields['facebook'] = 'Facebook URL';
	$profile_fields['gplus'] = 'Google+ URL';
	$profile_fields['pinterest'] = 'Pinterest URL';
	$profile_fields['linkedin'] = 'LinkedIn URL';
	$profile_fields['youtube'] = 'YouTube URL';

	return $profile_fields;

}
add_filter('user_contactmethods', 'smartadapt_contact_link');

/**
 * Return user profile fields - Google headshot support
 * @return array
 */
function smartadapt_get_user_profile_fields()

{
	$field_width_values = array();
	$social_array = array(
		'twitter', 'facebook', 'gplus', 'pinterest', 'linkedin', 'youtube'
	);

	foreach($social_array as $row){
		$value = get_the_author_meta($row);
		$rel = '';
		if(!empty($value)){
			if($row=='gplus'){ //check author rel (google headshot)
			  $parse_array =  parse_url($value);

				if(isset($parse_array['query'])){
					 parse_str($parse_array['query'], $output);

					if(!isset($output['rel']))
					$rel = '?rel=author';
				}else{
					$rel = '?rel=author';
				}
			}
			$field_width_values[$row] = $value.$rel;
		}
	}
	return $field_width_values;
}

add_action( 'show_user_profile', 'smartadapt_image_profile_field' );
add_action( 'edit_user_profile', 'smartadapt_image_profile_field' );

/**
 * Display image user profile field
 *
 * @param $user
 */
function smartadapt_image_profile_field( $user ) {
	if(current_user_can('upload_files')){
	$user_image = get_the_author_meta( 'smartadapt_profile_image', $user->ID );
	?>

<h3><?php _e("User profile picture", 'smartadapt') ?></h3>

<table class="form-table">

	<tr>
		<th><label for="smartadapt_profile_image"><?php _e("Image", 'smartadapt') ?></label></th>

		<td>
			<div class="smartadapt-image smartadapt-user-image-container"><?php echo !empty($user_image)? '<img src="'.$user_image.'"  alt="User Image" />' :'<img src="#" style="width: 0;height: 0" alt="User Image" />'  ?></div>
			<input type="text" name="smartadapt_profile_image" id="smartadapt_profile_image" value="<?php echo $user_image; ?>" class="regular-text" /><br />
			<a href="#" class="button smartadapt-upload-user-photo-btn"><?php _e("Upload user photo", 'smartadapt') ?></a>
      <span class="description"><?php _e(" or You can paste external URL", 'smartadapt'); ?></span>
		</td>
	</tr>

</table>
<?php
}
}

add_action( 'personal_options_update', 'smartadapt_image_profile_save' );
add_action( 'edit_user_profile_update', 'smartadapt_image_profile_save' );

/**
 * Save user profile image
 * @param $user_id
 *
 * @return bool
 */
function smartadapt_image_profile_save( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;


	update_user_meta( $user_id, 'smartadapt_profile_image', $_POST['smartadapt_profile_image'] );
}

add_action( 'admin_enqueue_scripts', 'smartadapt_admin_area_enqueue_scripts'  );

/**
 * Enqueue admin script
 */
function smartadapt_admin_area_enqueue_scripts() {
	if(current_user_can('upload_files')){
	wp_enqueue_media(); //add uploader files


	//add common script
	wp_enqueue_script( 'smartadapt_admin_area_plugin', get_template_directory_uri() . '/admin/js/plugin-scripts.js', array( 'jquery' ), '1.0', false );
	}
}

add_action( 'admin_print_styles', 'smartadapt_admin_area_enqueue_styles'  );

function smartadapt_admin_area_enqueue_styles(){
	wp_enqueue_style( 'smartadapt_admin_area_enqueue_styles',get_template_directory_uri() . '/admin/css/css-admin-mod.css', array(), '1.0', false );
}


/**
 *
 * If no Avatar is found use smartadapt_profile_image
 *
 * @param $avatar
 * @param $id_or_email
 * @param $size
 * @param $default
 * @param $alt
 *
 * @return string
 */
function smartadapt_userphoto_filter($avatar, $id_or_email, $size, $default, $alt) {

	$user = false;

	if ( is_numeric( $id_or_email ) ) {

		$id = (int) $id_or_email;
		$user = get_user_by( 'id' , $id );

	} elseif ( is_object( $id_or_email ) ) {

		if ( ! empty( $id_or_email->user_id ) ) {
			$id = (int) $id_or_email->user_id;
			$user = get_user_by( 'id' , $id );
		}

	} else {
		$user = get_user_by( 'email', $id_or_email );
	}

	if ( $user && is_object( $user ) ) {

		if ($user->data->ID == '1') {
			$avatar_string = get_user_meta($user->data->ID, 'smartadapt_profile_image', true);
			if (strlen($avatar_string) != 0) {
				{
					$avatar = "<img alt='{$alt}' src='{$avatar_string}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";

				}

			}

		}
	}

	return $avatar;


}
add_filter('get_avatar', 'smartadapt_userphoto_filter', 1, 5);