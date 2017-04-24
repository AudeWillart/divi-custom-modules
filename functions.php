<?php

/*==================================================================
Enregistre les modules personnalisés dans votre thème enfant
Ajoutez ce code dans le fichier function.php de votre thème enfant.
===================================================================*/
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

function divi_child_theme_setup_image_fullwidth() {
   if ( class_exists('ET_Builder_Module_Fullwidth_Image')) {
      get_template_part( 'custom-modules/fullwidth_image_module' );
      $fullwidth_image_module = new WPC_ET_Builder_Module_Fullwidth_Image();
      remove_shortcode( 'et_pb_fullwidth_image' );
      add_shortcode( 'et_pb_fullwidth_image', array($fullwidth_image_module, '_shortcode_callback') );
   }
}
add_action('wp', 'divi_child_theme_setup_image_fullwidth', 9999);

function divi_child_theme_setup_blurb_module() {
   if ( class_exists('ET_Builder_Module_Blurb')) {
      get_template_part( 'custom-modules/blurb_module' );
      $blurb_module = new custom_ET_Builder_Module_Blurb();
      remove_shortcode( 'et_pb_blurb' );
      add_shortcode( 'et_pb_blurb', array($blurb_module, '_shortcode_callback') );
   }
}
add_action('wp', 'divi_child_theme_setup_blurb_module', 9999);
