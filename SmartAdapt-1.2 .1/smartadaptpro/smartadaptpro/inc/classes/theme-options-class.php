<?php
/**
 * Smartadapt Theme Customizer Class
 *
 * Contains methods for customizing the theme customization screen.
 *
 *
 * @package    WordPress
 * @subpackage SmartAdapt
 * @since      SmartAdapt 1.0
 */





class smartadapt_Customize {

	/**
	 * Identifier, namespace
	 */
	public static $theme_key = 'smartadapt';

	/**
	 * The option value in the database will be based on get_stylesheet()
	 * so child themes don't share the parent theme's option value.
	 */
	public static $option_key = 'smartadapt_theme_options';

	public static $design_class;

	public static $post_customized;//serialized data from post - $_POST['customized']

	public static $design_index = 'default';

	/**
	 * Array of default theme options
	 */

	public static $default_theme_options = array(
		'default'=>array(
							'link_color'                      => '#6491A1',
							'link_color_hover'                      => '#6491A0',
							'main_font_color' => '#444',
							'breadcrumb_separator'            => ' &raquo; ',
							'sidebar_color'                   => '#385A72',
							'header_color'                    => '#404040',
							'top_bar_outer_color'             => '#404040',
							'top_bar_menu_color'              => '#212121',
							'top_bar_menu_link_color'         => '#ffffff',
							'top_bar_menu_link_background'    => '#404040',
							'smartadapt_logo'                 => '',
							'smartadapt_pagination_posts'     => '1',
							'custom_code_header'              => '',
							'custom_code_footer'              => '',
							'social_button_facebook'          => '0',
							'social_button_gplus'             => '0',
							'social_button_twitter'           => '0',
							'social_button_pinterest'         => '0',
							'layout_options'                  => '1',
							'smartadapt_layout_width'         => '1280',
							'title_tagline_footer' =>'',
							'smartadapt_favicon' => '',
							'smartadapt_fonts'=> array('smartadapt_general_fonts'=> 'alegreya',
																				 'smartadapt_header_fonts'=> 'ptsans',
																				 'smartadapt_menu_fonts'=>'')
	),
		'flat'=> array(
						'link_color'                      => '#6491A1',
						'link_color_hover'                      => '#6491A0',
						'main_font_color' => '#444',
						'breadcrumb_separator'            => ' &raquo; ',
						'sidebar_color'                   => '#385A72',
						'header_color'                    => '#404040',
						'top_bar_outer_color'             => '#404040',
						'top_bar_menu_color'              => '#212121',
						'top_bar_menu_link_color'         => '#ffffff',
						'top_bar_menu_link_background'    => '#404040',
						'smartadapt_logo'                 => '',
						'smartadapt_pagination_posts'     => '1',
						'custom_code_header'              => '',
						'custom_code_footer'              => '',
						'social_button_facebook'          => '0',
						'social_button_gplus'             => '0',
						'social_button_twitter'           => '0',
						'social_button_pinterest'         => '0',
						'layout_options'                  => '1',
						'smartadapt_layout_width'         => '1280',
						'title_tagline_footer' =>'',
						'smartadapt_favicon' => '',
						'smartadapt_fonts'=> array('smartadapt_general_fonts'=> 'open-sans',
																				'smartadapt_header_fonts'=> 'dosis',
																				'smartadapt_menu_fonts'=>'')
		)

	);




	/**
	 * This will output the custom WordPress settings to the live theme's WP head.
	 *
	 */
	public static function header_output() {


		$design_index = self::get_smartadapt_option( 'smartadapt_design');

		if($design_index){
			self::$design_index = $design_index;
		}

		?>
	<!--Customizer CSS-->
<style type="text/css">
		<?php
self::get_header_output_fonts(self::$design_index);
self::smartadapt_design_modify();
?>

<?php self::generate_css( 'body, body p', 'color', 'main_font_color' );  ?>
<?php self::generate_css( 'a', 'color', 'link_color' );  ?>
<?php self::generate_css( 'a:hover, a:focus', 'color', 'link_color_hover' );  ?>
<?php self::generate_css( '#sidebar .widget-title', 'background-color', 'sidebar_color' );  ?>
<?php self::generate_css( '#top-bar', 'background-color', 'top_bar_outer_color' );  ?>
<?php self::generate_css( '#top-bar > .row', 'background-color', 'top_bar_menu_color' );  ?>
<?php self::generate_css( '#top-bar .top-menu  a', 'color', 'top_bar_menu_link_color' );  ?>
<?php self::generate_css( '#top-navigation li:hover a, #top-navigation .current_page_item a,#top-navigation li:hover ul', 'background-color', 'top_bar_menu_link_background' );  ?>
<?php self::generate_css( 'h1, h2 a, h2, h3, h4, h5, h6', 'color', 'header_color' ); ?>
<?php self::generate_layout_css();	?>
</style>
<?php
	}


	/**
	 * This will generate a line of CSS for use in header output. If the setting
	 * ($mod_name) has no defined value, the CSS will not be output.
	 *
	 * @uses  get_theme_mod()
	 *
	 * @param string $selector CSS selector
	 * @param string $style    The name of the CSS *property* to modify
	 * @param string $mod_name The name of the 'theme_mod' option to fetch
	 * @param string $prefix   Optional. Anything that needs to be output before the CSS property
	 * @param string $postfix  Optional. Anything that needs to be output after the CSS property
	 * @param bool   $echo     Optional. Whether to print directly to the page (default: true).
	 *
	 * @return string Returns a single line of CSS with selectors and a property.
	 * @since SmartAdapt 1.0
	 */
	public static function generate_css( $selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = true ) {
		$return = '';
		$mod    = get_option( self::$option_key );

		if ( ! empty( $mod[$mod_name] ) ) {
			$return = sprintf( '%s { %s:%s; }',
				$selector,
				$style,
					$prefix . $mod[$mod_name] . $postfix
			);
			if ( $echo ) {
				echo $return . "\n";
			}
		}
		return $return;
	}

/**
* Generate layout css.
 *
* @since SmartAdapt Pro 1.0
*/
	public static function generate_layout_css(){

		$width = self::get_smartadapt_option( 'smartadapt_layout_width' );
    $sidebar_width = self::get_smartadapt_option( 'smartadapt_sidebar_resize' );
    //layout resize
		$layout_width = ! empty($width)?$width:1280;
		echo '@media only screen and (min-width: '.($layout_width+25).'px){'."\n";
		if(! empty($width)){
			echo 'body{min-width:'.$layout_width.'px}'."\n";
			echo '.row, #wrapper{ width:'.$layout_width.'px }'."\n";
		}

		if(!empty($sidebar_width)){
			echo '#sidebar{ width:'.$sidebar_width.'px }'."\n";
		}
		//if sidebar exists change page size
		$layot_option = self::get_smartadapt_option( 'smartadapt_layout' );
		if (!empty($layot_option) && $layot_option != '4' )
			echo '#page { width:'.($layout_width - self::get_smartadapt_option( 'smartadapt_sidebar_resize' ) ) . 'px }'."\n";
		echo '}'."\n";
	}

	/*Get single smartadapt option*/

	public static function get_smartadapt_option( $option_name ) {
		$mod = get_option( self::$option_key );
		return isset( $mod[$option_name] ) ? $mod[$option_name] : 0;
	}

	/*Get header font styles*/

	public static function get_header_output_fonts($design_index) {


		$mod =  self::get_smartadapt_option( 'smartadapt_fonts' );


		$fonts         = self::get_smartadapt_available_fonts();
		$font_variants = array( 'smartadapt_general_fonts', 'smartadapt_header_fonts', 'smartadapt_menu_fonts' );
		$unique_fonts = (isset($mod)&&is_array($mod)) ? array_unique($mod): array();


		/*first: load fonts - lazy include*/

		echo "\n" .'/*CUSTOM FONTS*/'."\n";
		/*if options are not empty*/


		foreach($font_variants as $font_variant){
			if(isset($unique_fonts[$font_variant])&&strlen($unique_fonts[$font_variant])>0){
				if(isset($fonts[$unique_fonts[$font_variant]]['import']))

					echo "\n" . $fonts[$unique_fonts[$font_variant]]['import'];

			}else{
				if(strlen(self::$default_theme_options[self::$design_index]['smartadapt_fonts'][$font_variant])>0)
				echo "\n" . $fonts[self::$default_theme_options[self::$design_index]['smartadapt_fonts'][$font_variant]]['import'];
			}
		}


	}


	/**
	 * Implement theme options into Theme Customizer on Frontend
	 *
	 * @see   examples for different input fields https://gist.github.com/2968549
	 * @since 08/09/2012
	 *
	 * @param $wp_customize Theme Customizer object
	 *
	 * @return void
	 */
	public static function  register( $wp_customize ) {


		$design_index = self::get_smartadapt_option( 'smartadapt_design');

		if($design_index){
			self::$design_index = $design_index;
		}




		$defaults = self::$default_theme_options[self::$design_index];


		// defaults, import for live preview with js helper

		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';




		//add section: logo
		$wp_customize->add_section( 'smartadapt_logo', array(
			'title'    => __( 'Logo', 'smartadapt' ),
			'priority' => 20,
		) );
		//add section: breadcrumb
		$wp_customize->add_section( 'smartadapt_breadcrumb', array(
			'title'    => __( 'Breadcrumb', 'smartadapt' ),
			'priority' => 70,
		) );

		//add section: pagination
		$wp_customize->add_section( 'smartadapt_pagination_posts', array(
			'title'    => __( 'Pagination', 'smartadapt' ),
			'priority' => 90,
		) );
		//add section: social buttons
		$wp_customize->add_section( 'smartadapt_social_buttons', array(
			'title'    => __( 'Social buttons', 'smartadapt' ),
			'priority' => 120,
		) );

		//add section: custom code

		$wp_customize->add_section( 'smartadapt_custom_code', array(
			'title'    => __( 'Custom Code', 'smartadapt' ),
			'priority' => 80,
		) );


		//add footer text


		$wp_customize->add_setting( self::$option_key . '[title_tagline_footer]', array(
			'default'    => $defaults['title_tagline_footer'],
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( self::$option_key . '_title_tagline_footer', array(
			'label'      => __( 'Footer text', 'smartadapt' ),
			'section'    => 'title_tagline',
			'settings'   => self::$option_key . '[title_tagline_footer]',
			'type'       => 'text',

		) );

		//add setting pagination

		$wp_customize->add_setting( self::$option_key . '[smartadapt_pagination_posts]', array(
			'default'    => '1',
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );


		$wp_customize->add_control( self::$option_key . '_pagination_posts', array(
			'label'      => __( 'Pagination', 'smartadapt' ),
			'section'    => 'smartadapt_pagination_posts',
			'settings'   => self::$option_key . '[smartadapt_pagination_posts]',
			'type'       => 'radio',
			'choices'    => array(
				'1' => __( 'Older posts/Newer posts', 'smartadapt' ),
				'2' => __( 'Paginate links', 'smartadapt' )
			)

		) );

		//add setting breadcrumb_separator

		$wp_customize->add_setting( self::$option_key . '[breadcrumb_separator]', array(
			'default'    => $defaults['breadcrumb_separator'],
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( self::$option_key . '_breadcrumb_separator', array(
			'label'      => __( 'Separator', 'smartadapt' ),
			'section'    => 'smartadapt_breadcrumb',
			'settings'   => self::$option_key . '[breadcrumb_separator]',
			'type'       => 'text',

		) );


		$wp_customize->add_setting( self::$option_key . '[main_font_color]', array(

			'type'              => 'option',
			'sanitize_callback' => 'sanitize_hex_color',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, self::$option_key . '_main_font_color', array(
			'label'    => __( 'Main Font Color', 'smartadapt' ),
			'section'  => 'colors',
			'settings' => self::$option_key . '[main_font_color]',
		) ) );
		//add header color

		$wp_customize->add_setting( self::$option_key . '[header_color]', array(

			'type'              => 'option',
			'sanitize_callback' => 'sanitize_hex_color',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, self::$option_key . '_header_color', array(
			'label'    => __( 'Headers Text Color', 'smartadapt' ),
			'section'  => 'colors',
			'settings' => self::$option_key . '[header_color]',
		) ) );

		//sidebar color
		$wp_customize->add_setting( self::$option_key . '[sidebar_color]', array(

			'type'              => 'option',
			'sanitize_callback' => 'sanitize_hex_color',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, self::$option_key . '_sidebar_color', array(
			'label'    => __( 'Sidebar Color', 'smartadapt' ),
			'section'  => 'colors',
			'settings' => self::$option_key . '[sidebar_color]',
		) ) );

		// Link Color (added to Color Scheme section in Theme Customizer)
		$wp_customize->add_setting( self::$option_key . '[link_color]', array(

			'type'              => 'option',
			'sanitize_callback' => 'sanitize_hex_color',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, self::$option_key . '_link_color', array(
			'label'    => __( 'Link Color', 'smartadapt' ),
			'section'  => 'colors',
			'settings' => self::$option_key . '[link_color]',
		) ) );

		$wp_customize->add_setting( self::$option_key . '[link_color_hover]', array(

			'type'              => 'option',
			'sanitize_callback' => 'sanitize_hex_color',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, self::$option_key . '_link_color_hover', array(
			'label'    => __( 'Link Hover Color', 'smartadapt' ),
			'section'  => 'colors',
			'settings' => self::$option_key . '[link_color_hover]',
		) ) );

		/*extended colors*/

		// Top bar outer color

		$wp_customize->add_setting( self::$option_key . '[top_bar_outer_color]', array(

			'type'              => 'option',
			'sanitize_callback' => 'sanitize_hex_color',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, self::$option_key . '_top_bar_outer_color', array(
			'label'    => __( 'Top Bar Outer Color', 'smartadapt' ),
			'section'  => 'colors',
			'settings' => self::$option_key . '[top_bar_outer_color]',
		) ) );
// Top bar menu color
		$wp_customize->add_setting( self::$option_key . '[top_bar_menu_color]', array(

			'type'              => 'option',
			'sanitize_callback' => 'sanitize_hex_color',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, self::$option_key . '_top_bar_menu_color', array(
			'label'    => __( 'Top Bar Menu Color', 'smartadapt' ),
			'section'  => 'colors',
			'settings' => self::$option_key . '[top_bar_menu_color]',
		) ) );

		// Top bar menu links color
		$wp_customize->add_setting( self::$option_key . '[top_bar_menu_link_color]', array(

			'type'              => 'option',
			'sanitize_callback' => 'sanitize_hex_color',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, self::$option_key . '_top_bar_menu_link_color', array(
			'label'    => __( 'Top Bar Menu Link Color', 'smartadapt' ),
			'section'  => 'colors',
			'settings' => self::$option_key . '[top_bar_menu_link_color]',
		) ) );
		// Top bar menu links background
		$wp_customize->add_setting( self::$option_key . '[top_bar_menu_link_background]', array(

			'type'              => 'option',
			'sanitize_callback' => 'sanitize_hex_color',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, self::$option_key . '_top_bar_menu_link_background', array(
			'label'    => __( 'Top Bar Menu Link Background', 'smartadapt' ),
			'section'  => 'colors',
			'settings' => self::$option_key . '[top_bar_menu_link_background]',
		) ) );


		/*LOGO*/
		$wp_customize->add_setting( self::$option_key . '[smartadapt_logo]', array(
			'default'    => $defaults['smartadapt_logo'],
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );


		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, self::$option_key . '_logo', array(
			'label'    => __( 'Upload', 'smartadapt' ),
			'section'  => 'smartadapt_logo',
			'settings' => self::$option_key . '[smartadapt_logo]',
		) ) );

		/* Favicon */

		$wp_customize->add_setting( self::$option_key . '[smartadapt_favicon]', array(
			'default'    => $defaults['smartadapt_favicon'],
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );


		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, self::$option_key . '_favicon', array(
			'label'    => __( 'Upload favicon', 'smartadapt' ),
			'section'  => 'smartadapt_logo',
			'settings' => self::$option_key . '[smartadapt_favicon]',
		) ) );

		//add social buttons settings

		//Facebook
		$wp_customize->add_setting( 'smartadapt_theme_options[social_button_facebook]', array(
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		) );

		$wp_customize->add_control( self::$option_key . '_social_button_facebook', array(
			'settings' => self::$option_key . '[social_button_facebook]',
			'label'    => __( 'Facebook Like', 'smartadapt' ),
			'section'  => 'smartadapt_social_buttons',
			'type'     => 'checkbox',
		) );
		//Twitter
		$wp_customize->add_setting( self::$option_key . '[social_button_twitter]', array(
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		) );

		$wp_customize->add_control( self::$option_key . '_social_button_twitter', array(
			'settings' => self::$option_key . '[social_button_twitter]',
			'label'    => __( 'Twitter Button ', 'smartadapt' ),
			'section'  => 'smartadapt_social_buttons',
			'type'     => 'checkbox',
		) );

		//Google +1
		$wp_customize->add_setting( self::$option_key . '[social_button_gplus]', array(
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		) );

		$wp_customize->add_control( self::$option_key . '_social_button_gplus', array(
			'settings' => self::$option_key . '[social_button_gplus]',
			'label'    => __( 'Google +1', 'smartadapt' ),
			'section'  => 'smartadapt_social_buttons',
			'type'     => 'checkbox',
		) );

		//Pinterest
		$wp_customize->add_setting( self::$option_key . '[social_button_pinterest]', array(
			'capability' => 'edit_theme_options',
			'type'       => 'option',
		) );

		$wp_customize->add_control( self::$option_key . '_social_button_pinterest', array(
			'settings' => self::$option_key . '[social_button_pinterest]',
			'label'    => __( 'Pinterest', 'smartadapt' ),
			'section'  => 'smartadapt_social_buttons',
			'type'     => 'checkbox',
		) );

		//add costom code setting

		$wp_customize->add_setting( self::$option_key . '[custom_code_header]', array(
			'default'    => '',
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_setting( self::$option_key . '[custom_code_footer]', array(
			'default'    => '',
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( new smartadapt_Customize_Textarea_Control( $wp_customize, self::$option_key . '_custom_code_header', array(
			'label'      => __( 'Custom Scripts for Header [header.php]', 'smartadapt' ),
			'section'    => 'smartadapt_custom_code',
			'capability' => 'edit_theme_options',
			'settings'   => self::$option_key . '[custom_code_header]'

		) ) );

		$wp_customize->add_control( new smartadapt_Customize_Textarea_Control( $wp_customize, self::$option_key . '_custom_code_footer', array(
			'label'      => __( 'Custom Scripts for Footer [footer.php]', 'smartadapt' ),
			'section'    => 'smartadapt_custom_code',
			'capability' => 'edit_theme_options',
			'settings'   => self::$option_key . '[custom_code_footer]'

		) ) );

		/*ADD PREMIUM SECTIONS*/
		//add section: layout
		$wp_customize->add_section( 'smartadapt_layout', array(
			'title'    => __( 'Layout', 'smartadapt' ),
			'priority' => 40,
		) );


		$wp_customize->add_setting( self::$option_key . '[smartadapt_layout]', array(
			'default'    => 1,
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( self::$option_key . '_smartadapt_layout', array(
			'settings'   => self::$option_key . '[smartadapt_layout]',
			'label'      => __( 'Layout variants:', 'smartadapt' ),
			'section'    => 'smartadapt_layout',
			'type'       => 'radio',
			'choices'    => array(
				'1' => __( 'Left menu & right sidebar', 'smartadapt' ),
				'2' => __( 'Left sidebar & right menu', 'smartadapt' ),
				'3' => __( 'Right sidebar without menu', 'smartadapt' ),
				'4' => __( 'Left menu without sidebar', 'smartadapt' )
			)

		) );

		//add section design

		$wp_customize->add_section( 'smartadapt_design', array(
			'title'    => __( 'Design', 'smartadapt' ),
			'priority' => 40,
		) );


		$wp_customize->add_setting( self::$option_key . '[smartadapt_design]', array(
			'default'    => self::$design_index,
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( self::$option_key . '_smartadapt_design', array(
			'settings'   => self::$option_key . '[smartadapt_design]',
			'label'      => __( 'Design variants:', 'smartadapt' ),
			'section'    => 'smartadapt_design',
			'type'       => 'select',
			'choices'    => array(
				'default' => __( 'Default Design', 'smartadapt' ),
				'flat' => __( 'Flat Design', 'smartadapt' )

			)

		) );

		//fixed top bar option

		$wp_customize->add_setting( self::$option_key . '[smartadapt_fixed_topbar]', array(
			'default'    => 1,
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( self::$option_key . '_smartadapt_fixed_topbar', array(
			'label'      => __( 'Fixed Top Bar', 'smartadapt' ),
			'section'    => 'smartadapt_layout',
			'settings'   => self::$option_key . '[smartadapt_fixed_topbar]',
			'type'       => 'checkbox',


		) );

		//add section sidebar
		$wp_customize->add_section( 'smartadapt_sidebar_resize', array(
			'title'    => __( 'Resize components', 'smartadapt' ),
			'priority' => 60,
		) );

		$wp_customize->add_setting( self::$option_key . '[smartadapt_layout_width]', array(
			'default'    => '1280',
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_setting( self::$option_key . '[smartadapt_sidebar_resize]', array(
			'default'    => 320,
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );


		$wp_customize->add_control( new smartadapt_Customize_Range_Control( $wp_customize, self::$option_key . '_smartadapt_layout_width', array(
			'settings' => self::$option_key . '[smartadapt_layout_width]',
			'label'    => __( 'Layout Width ', 'smartadapt' ),
			'section'  => 'smartadapt_sidebar_resize',
			'type'     => 'text',

		) ) );

		$wp_customize->add_control( new smartadapt_Customize_Range_Control( $wp_customize, self::$option_key . '_smartadapt_sidebar_resize', array(
			'label'      => __( 'Sidebar Width', 'smartadapt' ),
			'section'    => 'smartadapt_sidebar_resize',
			'settings'   => self::$option_key . '[smartadapt_sidebar_resize]',
			'type'       => 'text',

		)) );

		//add font section
		$wp_customize->add_section( 'smartadapt_fonts', array(
			'title'    => __( 'Typography options', 'smartadapt' ),
			'priority' => 90,
		) );

		$wp_customize->add_setting( self::$option_key . '[smartadapt_fonts][smartadapt_general_fonts]', array(

			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( self::$option_key . '_smartadapt_general_fonts', array(
			'label'      => __( 'Primary font', 'smartadapt' ),
			'section'    => 'smartadapt_fonts',
			'settings'   => self::$option_key . '[smartadapt_fonts][smartadapt_general_fonts]',
			'type'       => 'select',
			'choices'    => self::get_smartadapt_choices_fonts()

		) );

		$wp_customize->add_setting( self::$option_key . '[smartadapt_fonts][smartadapt_header_fonts]', array(

			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( self::$option_key . '_smartadapt_header_fonts', array(
			'label'      => __( 'Top headers font', 'smartadapt' ),
			'section'    => 'smartadapt_fonts',
			'settings'   => self::$option_key . '[smartadapt_fonts][smartadapt_header_fonts]',
			'type'       => 'select',
			'choices'    => self::get_smartadapt_choices_fonts()

		) );

		$wp_customize->add_setting( self::$option_key . '[smartadapt_fonts][smartadapt_menu_fonts]', array(

			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( self::$option_key . '_smartadapt_menu_fonts', array(
			'label'      => __( 'Top menu font', 'smartadapt' ),
			'section'    => 'smartadapt_fonts',
			'settings'   => self::$option_key . '[smartadapt_fonts][smartadapt_menu_fonts]',
			'type'       => 'select',
			'choices'    => self::get_smartadapt_choices_fonts()

		) );

		//Fixed vertical menu settings
		$wp_customize->add_setting( self::$option_key . '[smartadapt_menu_fixed]', array(

			'type'       => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( self::$option_key . '_smartadapt_menu_fixed', array(
			'label'      => __( 'Fixed vertical menu', 'smartadapt' ),
			'section'    => 'nav',
			'settings'   => self::$option_key . '[smartadapt_menu_fixed]',
			'type'       => 'checkbox',


		) );


	}

	/**
	 * Live preview javascript
	 *
	 * @since  SmartAdapt 1.0
	 * @return void
	 */
	public function customize_preview_js() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';

		wp_register_script(
			self::$theme_key . '-customizer',
				get_template_directory_uri() . '/js/theme-customizer' . $suffix . '.js',
			array( 'customize-preview' ),
			FALSE,
			TRUE
		);

		wp_enqueue_script( self::$theme_key . '-customizer' );
	}

	/**
	 * Get available fonts
	 *
	 * @since  SmartAdapt 1.1
	 * @return array
	 */
	public static function get_smartadapt_available_fonts() {
		$fonts = array(
			'arial'             => array(
				'name'   => 'Arial',
				'import' => '',
				'css'    => "font-family: Arial, sans-serif;"
			),
			'cantarell'         => array(
				'name'   => 'Cantarell',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Cantarell);',
				'css'    => "font-family: 'Cantarell', sans-serif;"
			),
			'droid'             => array(
				'name'   => 'Droid Sans',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Droid+Sans);',
				'css'    => "font-family: 'Droid Sans', sans-serif;"
			),
			'lato'              => array(
				'name'   => 'Lato',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Lato);',
				'css'    => "font-family: 'Lato', sans-serif;"
			),
			'merriweather-sans' => array(
				'name'   => 'Merriweather Sans',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Merriweather+Sans:400,700&amp;subset=latin,latin-ext);',
				'css'    => "font-family: 'Merriweather Sans', sans-serif;"
			),
			'open-sans'         => array(
				'name'   => 'Open Sans',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Open+Sans);',
				'css'    => "font-family: 'Open Sans', sans-serif;"
			),
			'open-sans-condesed'=> array(
				'name'   => 'Open Sans Condensed',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700&amp;subset=latin,latin-ext);',
				'css'    => "font-family: 'Open Sans Condensed', sans-serif;"
			),
			'roboto'            => array(
				'name'   => 'Roboto',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Roboto&subset=latin,latin-ext);',
				'css'    => "font-family: 'Roboto', sans-serif;"
			),
			'dosis'   => array(
				'name'   => 'Dosis',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Dosis:500&subset=latin,latin-ext);',
				'css'    => "font-family: 'Dosis', sans-serif;"
			),
			'source-sans-pro'   => array(
				'name'   => 'Source Sans Pro',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Source+Sans+Pro&subset=latin,latin-ext);',
				'css'    => "font-family: 'Source Sans Pro', sans-serif;"
			),
			'Tahoma'            => array(
				'name'   => 'Tahoma',
				'import' => '',
				'css'    => "font-family: Tahoma, sans-serif;"
			),
			'vollkorn'          => array(
				'name'   => 'Vollkorn',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Vollkorn);',
				'css'    => "font-family: 'Vollkorn', serif;"
			),

            'alegreya'          => array(
				'name'   => 'Alegreya',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Alegreya&subset=latin,latin-ext);',
				'css'    => "font-family: 'Alegreya', serif;"
			),
            'alegreyasans'          => array(
				'name'   => 'Alegreya Sans',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Alegreya+Sans&subset=latin,latin-ext);',
				'css'    => "font-family: 'Alegreya Sans', sans-serif;"
			),
            'cantataone'          => array(
				'name'   => 'Cantata One',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Cantata+One&subset=latin,latin-ext);',
				'css'    => "font-family: 'Cantata One', serif;"
			),
             'courgette'          => array(
				'name'   => 'Courgette',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Courgette&subset=latin,latin-ext);',
				'css'    => "font-family: 'Courgette', cursive;"
			),
             'domine'          => array(
				'name'   => 'Domine',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Domine&subset=latin,latin-ext);',
				'css'    => "font-family: 'Domine', serif;"
			),
            'ptsans'          => array(
				'name'   => 'PT Sans',
				'import' => '@import url(http://fonts.googleapis.com/css?family=PT+Sans&subset=latin,latin-ext);',
				'css'    => "font-family: 'PT Sans', sans-serif;"
			),
            'robotocondensed'          => array(
				'name'   => 'Roboto Condensed',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Roboto+Condensed&subset=latin,latin-ext);',
				'css'    => "font-family: 'Roboto Condensed', sans-serif;"
			),
             'scada'          => array(
				'name'   => 'Scada',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Scada&subset=latin,latin-ext);',
				'css'    => "font-family: 'Scada', sans-serif;"
			),

		);

		return apply_filters( 'smartadapt_available_fonts', $fonts );
	}

	/**
	 * Get array of fonts -> wp_customize control select
	 *
	 * @since  SmartAdapt 1.1
	 * @return array
	 */
	public static function get_smartadapt_choices_fonts() {
		$font_array   = self::get_smartadapt_available_fonts();
		$font_choices = array();

		foreach ( $font_array as $key=> $row ) {
			$font_choices[$key] = $row['name'];
		}
		return $font_choices;
	}

	public static function smartadapt_design_modify(){
		global $classes;
		switch(self::$design_index)
		{
			case 'flat':
				?>

@import url("<?php echo get_stylesheet_directory_uri() . '/css/flat-css.css' ?>");
				<?php

				self::$design_class = 'smartadaptm';

				/*add infield label*/

				wp_enqueue_style( 'smartadapt-infieldlabel', get_template_directory_uri() . '/css/jquery.infieldlabel.css' );
				wp_enqueue_script( 'smartadapt-infieldlabel', get_template_directory_uri() . '/js/jquery.infieldlabel.min.js', array( 'jquery' ), '1.0', true );
				wp_enqueue_script( 'smartadapt-flat-design', get_template_directory_uri() . '/js/design-flat.js', array( 'jquery' ), '1.0', true );
				add_filter('body_class', array(__CLASS__,'smartadapt_body_class') );

			break;
			default:
		}
	}

	public static function smartadapt_body_class($classes){

		$classes[] = self::$design_class;
		return $classes;
	}
}

/**
 * Customize for textarea, extend the WP customizer
 *
 * @package    WordPress
 * @subpackage SmartAdapt
 * @since      SmartAdapt 1.0
 */
if (class_exists('WP_Customize_Control'))
{
class smartadapt_Customize_Textarea_Control extends WP_Customize_Control {
	public $type = 'textarea';

	public function render_content() {
		?>
	<label>
		<?php echo esc_html( $this->label ); ?></label>
	<textarea rows="5"
						style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>

	<?php
	}
}
}
/**
 * Customize for input range field, extend the WP customizer
 *
 * @package    WordPress
 * @subpackage SmartAdapt
 * @since      SmartAdapt 1.0
 */
if (class_exists('WP_Customize_Control'))
{
class smartadapt_Customize_Range_Control extends WP_Customize_Control {
	public $type = 'text';

	public function render_content() {
		?>
	<fieldset class="range-fieldset">
		<label for="<?php echo $this->id ?>">
			<?php echo esc_html( $this->label ); ?></label>
		<input type="text" class="slider-range-input" readonly="readonly" id="<?php echo $this->id ?>" class="range-customize-input" <?php $this->link(); ?> value="<?php echo esc_textarea( $this->value() ); ?>" /><span>px</span>

		<div class="noUiSlider <?php echo $this->id ?>" rel="<?php echo $this->id ?>"></div>
	</fieldset>
	<?php
	}
}
}
//Setup the Theme Customizer settings and controls
add_action( 'customize_register', array( 'smartadapt_Customize', 'register' ) );

//Output custom CSS to live site
add_action( 'wp_head', array( 'smartadapt_Customize', 'header_output' ) );

function customize_preview_js() {

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';

	wp_register_script(
		'smartadapt-customizer',
		get_template_directory_uri() . '/js/theme-customizer' . $suffix . '.js',
		array( 'customize-preview' ),
		FALSE,
		TRUE
	);

	wp_enqueue_script('smartadapt-customizer' );
}
//Enqueue live preview javascript in Theme Customizer admin screen
add_action( 'customize_preview_init',  'customize_preview_js' );







