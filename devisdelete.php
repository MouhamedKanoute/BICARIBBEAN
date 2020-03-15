<?php
/**
 * @package Devis 2s Media
 *
 * @author: Cheikh
 * @url: http://www.cg-numerics.com
 * @email: bayecheikhgueye2@gmail.com

 Template Name: Devis Delete

 */
 
 
remove_action( 'electro_content_top', 'electro_breadcrumb', 10 );

do_action( 'electro_before_homepage_v3' );

$home_v3 		= electro_get_home_v3_meta();
$header_style 	= isset( $home_v3['header_style'] ) ? $home_v3['header_style'] : 'v3';

electro_get_header( $header_style );
get_devismenu();
 ?>

<?php


global $wpdb;
$table_name = $wpdb->prefix . "devis";

//delete
   if (isset($_GET['id'])) {
	   $id = $_GET["id"];
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %s", $id));
    }
	wp_redirect( 'devis-list' );
?>

<?php 

get_footer();