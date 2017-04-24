<?php

/**
 * Loading the parent theme css.
 */
function divi_child_load_parent_css() {

	wp_enqueue_style( 'divi-parent-style', get_template_directory_uri() . '/style.css', false, '' );

}
add_action( 'wp_enqueue_scripts', 'divi_child_load_parent_css' );

/**
* Divi Welcome Dashboard for all users
*/

add_filter('user_has_cap', 'se_119694_user_has_cap');
 function se_119694_user_has_cap($capabilities){
 global $pagenow;
      if ($pagenow == 'index.php')
           $capabilities['edit_theme_options'] = 1;
      return $capabilities;
 }
add_action( 'load-index.php', 'show_welcome_panel' );

function show_welcome_panel() {
    $user_id = get_current_user_id();

    if ( 1 != get_user_meta( $user_id, 'show_welcome_panel', true ) )
        update_user_meta( $user_id, 'show_welcome_panel', 1 );
}

$PrivateRole = get_role('author');
$PrivateRole -> add_cap('read_private_pages');

$PrivateRole = get_role('editor');
$PrivateRole -> add_cap('read_private_pages');

$PrivateRole = get_role('subscriber');
$PrivateRole -> add_cap('read_private_pages');

/**
* Hide Divi dashboard from page list
*/

function ts_exclude_pages_from_admin($query) {

  if ( ! is_admin() )
    return $query;

  global $pagenow, $post_type;

  if ( !current_user_can( 'administrator' ) && $pagenow == 'edit.php' && $post_type == 'page' )
    $query->query_vars['post__not_in'] = array( '609' ); // Enter your page IDs here

}
add_filter( 'parse_query', 'ts_exclude_pages_from_admin' );

/**
* Builder allways enabled
*/
add_action('et_builder_always_enabled',function() {
return true;
});

/*============================================
Loading the Custom Module into child theme
=============================================*/
function divi_child_theme_setup_image() {
   if ( class_exists('ET_Builder_Module')) {
      get_template_part( 'custom-modules/image_module' );
      $image_module = new WPC_ET_Builder_Module_Image();
      remove_shortcode( 'et_pb_image' );
      add_shortcode( 'et_pb_image', array($image_module, '_shortcode_callback') );
   }
}
add_action('wp', 'divi_child_theme_setup_image', 9999);

function wpc_get_image_id($image_url) {
  return attachment_url_to_postid($image_url);
}

function divi_child_theme_setup_blurb_module() {
   if ( class_exists('ET_Builder_Module_Fullwidth_Image')) {
      get_template_part( 'custom-modules/fullwidth_image_module' );
      $fullwidth_image_module = new WPC_ET_Builder_Module_Fullwidth_Image();
      remove_shortcode( 'et_pb_fullwidth_image' );
      add_shortcode( 'et_pb_fullwidth_image', array($fullwidth_image_module, '_shortcode_callback') );
   }
}
add_action('wp', 'divi_child_theme_setup_image_fullwidth', 9999);

function wpc_get_fullwidth_image_id($image_url) {
   return attachment_url_to_postid($image_url);
}

function divi_child_theme_setup_image_fullwidth() {
   if ( class_exists('custom_ET_Builder_Module_Blurb')) {
      get_template_part( 'custom-modules/blurb_module' );
      $blurb_module = new custom_ET_Builder_Module_Blurb();
      remove_shortcode( 'et_pb_blurb' );
      add_shortcode( 'et_pb_blurb', array($blurb_module, '_shortcode_callback') );
   }
}
add_action('wp', 'divi_child_theme_setup_blurb_module', 9999);

function wpc_get_blurb_module_image_id($image_url) {
   return attachment_url_to_postid($image_url);
}

//Change la position de la sidebar par défaut. 
// mettre ce code dans le fichier functions.php

if (!function_exists('et_single_settings_meta_box')) :

function et_single_settings_meta_box($post) {
$post_id = get_the_ID();

wp_nonce_field(basename(__FILE__), 'et_settings_nonce');

$page_layout = get_post_meta($post_id, '_et_pb_page_layout', true);

$side_nav = get_post_meta($post_id, '_et_pb_side_nav', true);

$project_nav = get_post_meta($post_id, '_et_pb_project_nav', true);

$post_hide_nav = get_post_meta($post_id, '_et_pb_post_hide_nav', true);
$post_hide_nav = $post_hide_nav && 'off' === $post_hide_nav ? 'default' : $post_hide_nav;

$show_title = get_post_meta($post_id, '_et_pb_show_title', true);

// Mettre en 1er la mise en page souhaitée.
$page_layouts = array(
'et_full_width_page' => esc_html__('Full Width', 'Divi'),
'et_right_sidebar' => esc_html__('Right Sidebar', 'Divi'),
'et_left_sidebar' => esc_html__('Left Sidebar', 'Divi'),
);

$layouts = array(
'light' => esc_html__('Light', 'Divi'),
'dark' => esc_html__('Dark', 'Divi'),
);
$post_bg_color = ( $bg_color = get_post_meta($post_id, '_et_post_bg_color', true) ) && '' !== $bg_color ? $bg_color : '#ffffff';
$post_use_bg_color = get_post_meta($post_id, '_et_post_use_bg_color', true) ? true : false;
$post_bg_layout = ( $layout = get_post_meta($post_id, '_et_post_bg_layout', true) ) && '' !== $layout ? $layout : 'light';
?>

<p class="et_pb_page_settings et_pb_page_layout_settings">
<label for="et_pb_page_layout" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e('Page Layout', 'Divi'); ?>: </label>

<select id="et_pb_page_layout" name="et_pb_page_layout">
<?php
foreach ($page_layouts as $layout_value => $layout_name) {
printf('<option value="%2$s"%3$s>%1$s</option>', esc_html($layout_name), esc_attr($layout_value), selected($layout_value, $page_layout, false)
);
}
?>
</select>
</p>
<p class="et_pb_page_settings et_pb_side_nav_settings" style="display: none;">
<label for="et_pb_side_nav" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e('Dot Navigation', 'Divi'); ?>: </label>

<select id="et_pb_side_nav" name="et_pb_side_nav">
<option value="off" <?php selected('off', $side_nav); ?>><?php esc_html_e('Off', 'Divi'); ?></option>
<option value="on" <?php selected('on', $side_nav); ?>><?php esc_html_e('On', 'Divi'); ?></option>
</select>
</p>
<p class="et_pb_page_settings">
<label for="et_pb_post_hide_nav" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e('Hide Nav Before Scroll', 'Divi'); ?>: </label>

<select id="et_pb_post_hide_nav" name="et_pb_post_hide_nav">
<option value="default" <?php selected('default', $post_hide_nav); ?>><?php esc_html_e('Default', 'Divi'); ?></option>
<option value="no" <?php selected('no', $post_hide_nav); ?>><?php esc_html_e('Off', 'Divi'); ?></option>
<option value="on" <?php selected('on', $post_hide_nav); ?>><?php esc_html_e('On', 'Divi'); ?></option>
</select>
</p>

<?php if ('post' === $post->post_type) : ?>
<p class="et_pb_page_settings et_pb_single_title" style="display: none;">
<label for="et_single_title" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e('Post Title', 'Divi'); ?>: </label>

<select id="et_single_title" name="et_single_title">
<option value="on" <?php selected('on', $show_title); ?>><?php esc_html_e('Show', 'Divi'); ?></option>
<option value="off" <?php selected('off', $show_title); ?>><?php esc_html_e('Hide', 'Divi'); ?></option>
</select>
</p>

<p class="et_divi_quote_settings et_divi_audio_settings et_divi_link_settings et_divi_format_setting et_pb_page_settings">
<label for="et_post_use_bg_color" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e('Use Background Color', 'Divi'); ?></label>
<input name="et_post_use_bg_color" type="checkbox" id="et_post_use_bg_color" <?php checked($post_use_bg_color); ?> />
</p>

<p class="et_post_bg_color_setting et_divi_format_setting et_pb_page_settings">
<input id="et_post_bg_color" name="et_post_bg_color" class="color-picker-hex" type="text" maxlength="7" placeholder="<?php esc_attr_e('Hex Value', 'Divi'); ?>" value="<?php echo esc_attr($post_bg_color); ?>" data-default-color="#ffffff" />
</p>

<p class="et_divi_quote_settings et_divi_audio_settings et_divi_link_settings et_divi_format_setting">
<label for="et_post_bg_layout" style="font-weight: bold; margin-bottom: 5px;"><?php esc_html_e('Text Color', 'Divi'); ?>: </label>
<select id="et_post_bg_layout" name="et_post_bg_layout">
<?php
foreach ($layouts as $layout_name => $layout_title)
printf('<option value="%s"%s>%s</option>', esc_attr($layout_name), selected($layout_name, $post_bg_layout, false), esc_html($layout_title)
);
?>
</select>
</p>
<?php endif;

if ('project' === $post->post_type) :
?>
<p class="et_pb_page_settings et_pb_project_nav" style="display: none;">
<label for="et_project_nav" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e('Project Navigation', 'Divi'); ?>: </label>

<select id="et_project_nav" name="et_project_nav">
<option value="off" <?php selected('off', $project_nav); ?>><?php esc_html_e('Hide', 'Divi'); ?></option>
<option value="on" <?php selected('on', $project_nav); ?>><?php esc_html_e('Show', 'Divi'); ?></option>
</select>
</p>
<?php
endif;
}


endif;
