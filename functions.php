<?php
/**
 * Electro Child
 *
 * @package electro-child
 */

/**
 * Include all your custom code here
 */
add_filter ( 'woocommerce_account_menu_items', 'misha_inbox_link', 40 );
function misha_inbox_link( $menu_links ){
 
	$menu_links = array_slice( $menu_links, 0, 1, true ) 
	+ array( 'my-inbox' => 'Messages recus','my-outbox' =>'Messages envoyes','my-send' =>'REDIGER UN NOUVEAU MESSAGE' )
	+ array_slice( $menu_links, 1, NULL, true );
 
	return $menu_links;
 
}
/*
 *  Register Permalink Endpoint
 */
add_action( 'init', 'misha_add_endpointInbox' );
function misha_add_endpointInbox() {
 
	add_rewrite_endpoint( 'my-inbox', EP_PAGES );
 
}
add_action( 'init', 'misha_add_endpointOutbox' );
function misha_add_endpointOutbox() {
        add_rewrite_endpoint( 'my-outbox', EP_PAGES );
 
}
add_action( 'init', 'misha_add_endpointSend' );
function misha_add_endpointSend() {

        add_rewrite_endpoint( 'my-send', EP_PAGES );
 
}
/*
 * Contenu des 3 nouvelles pages dans My Account, woocommerce_account_{ENDPOINT NAME}_endpoint
 */
add_action( 'woocommerce_account_my-inbox_endpoint', 'misha_my_account_endpoint_contentInbox' );
add_action( 'woocommerce_account_my-outbox_endpoint', 'misha_my_account_endpoint_contentOutbox' );
add_action( 'woocommerce_account_my-send_endpoint', 'misha_my_account_endpoint_contentSend' );

function misha_my_account_endpoint_contentInbox() {
	/*Notification de nouveau message*/
	//rwpm_notify();
	/*fonction d'affichage des messages re�us / plugin pm*/
	rwpm_inbox();
}
function misha_my_account_endpoint_contentOutbox() {
	/*fonction d'affichage des messages envoy�s / plugin pm*/
    rwpm_outbox();
}
function misha_my_account_endpoint_contentSend() {
	/*fonction d'affichage du formulaire d'envoi de message / plugin pm*/
   rwpm_send();
}

/*action button to contact vendor from product details*/
add_action('woocommerce_single_product_summary','cmk_additional_button',200);
function cmk_additional_button() {
	$vendor_id = get_post_field( 'post_author', $product_id );
    $usermeta = get_user_meta( $vendor_id,'wcfmmp_profile_settings', true);
	$phone=$usermeta['phone'];
    $vendor = get_userdata( $vendor_id );
    $vendor = $vendor->display_name;
	//$phone = get_user_meta($vendor_id,'billing_phone',true);
	//billing_phone
	$link=wc_get_account_endpoint_url( "my-send/?page=rwpm_send&recipient=$vendor&id=&subject=&_wpnonce=");

    _e('<a class="buttoncontact" href='.$link.'>Contacter le vendeur</a>','pm');
	
	echo'<br><a class="buttonwhatsapp" href="https://api.whatsapp.com/send?phone='.urlencode($usermeta['phone']).'" method="get" target="_blank"> <i class="fab fa-whatsapp"></i></a>';
	//print_r($usermeta['phone']);
	
	
}

add_action( 'init', 'ec_child_rearrange_loop_product_header',1 );

function ec_child_rearrange_loop_product_header() {
    remove_action( 'woocommerce_before_shop_loop_item_title', 'electro_template_loop_categories', 20 );
    remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 25 );
    remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 30 );
    remove_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_product_thumbnail', 40 );
    remove_action( 'electro_product_carousel_alt_content',         'electro_template_loop_product_thumbnail',      20 );
    remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 45 );

    add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 10 );
    add_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_product_thumbnail', 15 );
    add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 20 );
    add_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_categories', 25 );
    add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 30 );
    add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 40 );
    add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 45 );
}

function electro_add_2_1_2_main_product_hooks() {
    remove_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_product_thumbnail', 40  );
    remove_action( 'woocommerce_after_shop_loop_item', 'electro_template_loop_hover', 140 );
    remove_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_product_thumbnail', 15 );
    
    add_action( 'woocommerce_before_shop_loop_item', 'electro_wrap_flex_div_open', 11 );
    add_action( 'woocommerce_after_shop_loop_item', 'electro_wrap_flex_div_close', 149 );
    add_action( 'woocommerce_after_shop_loop_item', 'electro_template_loop_hover', 149 );
    add_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_product_single_image', 46 );
}

function electro_remove_2_1_2_main_product_hooks() {
    remove_action( 'woocommerce_before_shop_loop_item', 'electro_wrap_flex_div_open', 11 );
    remove_action( 'woocommerce_after_shop_loop_item', 'electro_wrap_flex_div_close', 149 );
    remove_action( 'woocommerce_after_shop_loop_item', 'electro_template_loop_hover', 149 );
    remove_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_product_single_image', 46 );

    add_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_product_thumbnail', 15 );
    add_action( 'woocommerce_after_shop_loop_item', 'electro_template_loop_hover', 140 );
}

function electro_add_6_1_main_product_hooks() {
    remove_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_product_thumbnail', 40  );
    remove_action( 'woocommerce_after_shop_loop_item', 'electro_template_loop_hover', 140 );
    remove_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_product_thumbnail', 15 );
    
    add_action( 'woocommerce_before_shop_loop_item', 'electro_wrap_flex_div_open', 11 );
    add_action( 'woocommerce_after_shop_loop_item', 'electro_wrap_flex_div_close', 149 );
    add_action( 'woocommerce_after_shop_loop_item', 'electro_template_loop_hover', 149 );
    add_action( 'woocommerce_shop_loop_item_title', 'electro_show_wc_product_images', 46 );
}

function electro_remove_6_1_main_product_hooks() {
    remove_action( 'woocommerce_before_shop_loop_item', 'electro_wrap_flex_div_open', 11 );
    remove_action( 'woocommerce_after_shop_loop_item', 'electro_wrap_flex_div_close', 149 );
    remove_action( 'woocommerce_after_shop_loop_item', 'electro_template_loop_hover', 149 );
    remove_action( 'woocommerce_shop_loop_item_title', 'electro_show_wc_product_images', 46 );

    add_action( 'woocommerce_shop_loop_item_title', 'electro_template_loop_product_thumbnail', 15  );
    add_action( 'woocommerce_after_shop_loop_item', 'electro_template_loop_hover', 140 );
}

add_filter ( 'woocommerce_account_menu_items', 'misha_remove_my_account_links' );
function misha_remove_my_account_links( $menu_links ){
 
	unset( $menu_links['edit-address'] ); // Addresses
 
 
	//unset( $menu_links['dashboard'] ); // Remove Dashboard
	//unset( $menu_links['payment-methods'] ); // Remove Payment Methods
	unset( $menu_links['orders'] ); // Remove Orders
	unset( $menu_links['downloads'] ); // Disable Downloads
	//unset( $menu_links['edit-account'] ); // Remove Account details tab
	//unset( $menu_links['customer-logout'] ); // Remove Logout link
 
	return $menu_links;
 
}

add_filter ( 'woocommerce_account_menu_items', 'misha_one_more_link' );
function misha_one_more_link( $menu_links ){
 
	if(current_user_can('customer'))
	$new = array( 'carnetadresse123' => 'Carnet d\'adresses vendeur','newsletter123' => 'Newsletter','aidedocumentation123' => 'Aide / Documentation');
	else
	$new = array( 'carnetadresse123' => 'Carnet d\'adresses vendeur','newsletter123' => 'Newsletter',
	'aidedocumentation123' => 'Aide / Documentation','gererproduit123' => 'Gerer mes produits',
	'paramettreboutique123' => 'Parametres de ma boutique');
 
 
	// array_slice() is good when you want to add an element between the other ones
	$menu_links = array_slice( $menu_links, 0, 1, true ) 
	+ $new 
	+ array_slice( $menu_links, 1, NULL, true );
 
 
	return $menu_links;
 
 
}
 
add_filter( 'woocommerce_get_endpoint_url', 'misha_hook_endpoint', 10, 4 );
function misha_hook_endpoint( $url, $endpoint, $value, $permalink ){
 
	if( $endpoint === 'carnetadresse123' ) {
 
		// ok, here is the place for your custom URL, it could be external
		$url = site_url().'/boutique';
 
	}
	if( $endpoint === 'newsletter123' ) {
 
		// ok, here is the place for your custom URL, it could be external
		$url = site_url().'/newsletter';
 
	}
	if( $endpoint === 'aidedocumentation123' ) {
 
		// ok, here is the place for your custom URL, it could be external
		$url = site_url().'/vendre-acheter-sur-bi-caribbean';
 
	}
	if( $endpoint === 'gererproduit123' ) {
 
		// ok, here is the place for your custom URL, it could be external
		$url = site_url().'/store-manager/products/';
 
	}
	if( $endpoint === 'paramettreboutique123' ) {
 
		// ok, here is the place for your custom URL, it could be external
		$url = site_url().'/store-manager/settings';
 
	}
	return $url;
 
}


// Add DataTables Resources
  
function add_datatables_scripts() {
wp_register_script('datatables', 'https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js', array('jquery'), true);
wp_enqueue_script('datatables');
  
wp_register_script('datatables_bootstrap', 'https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js', array('jquery'), true);
wp_enqueue_script('datatables_bootstrap');
}
  
function add_datatables_style() {
wp_register_style('bootstrap_style', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
wp_enqueue_style('bootstrap_style');
  
wp_register_style('datatables_style', 'https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css');
wp_enqueue_style('datatables_style');
}
  
add_action('wp_enqueue_scripts', 'add_datatables_scripts');
add_action('wp_enqueue_scripts', 'add_datatables_style');

function my_scripts_method() {
    wp_enqueue_script(
        'custom-script',
        get_stylesheet_directory_uri() . '/custom.js',
        array( 'jquery' )
    );
	wp_localize_script(
	'custom-script', 
	'the_ajax_script', 
	array('ajaxurl' =>admin_url('admin-ajax.php')));
}
add_action( 'wp_enqueue_scripts', 'my_scripts_method' );

function info_client_ajax() {
  header("Content-Type: text/html");
  $idclient = (isset($_POST["idclient"])) ? $_POST["idclient"] : 1;
  $boutique = get_user_meta( $idclient,'store_name', true);
  $ville=get_user_meta( $idclient,'_wcfm_city', true);
  $codepostal = get_user_meta( $idclient,'_wcfm_zip', true);
  $adress=get_user_meta( $idclient,'_wcfm_street_1', true);
  $paye=get_user_meta( $idclient,'_wcfm_state', true);
  $response = '<strong>Boutique : </strong>'.$boutique.'<br>
			  <strong>Adresse : </strong>'.$adress.'<br>
			  <strong>Code postal : </strong>'.$codepostal.'<br>
              <strong>Ville : </strong>'.$ville.'<br>
			  <strong>Pays : </strong>'.$adress.'<br>';
  die($response); 
}
function info_produitajax() {
  header("Content-Type: text/html");
  $idproduit = (isset($_POST["idproduit"])) ? $_POST["idclient"] : 1;
  
  $response = '<p>Boutique : </strong>'.$idproduit.'</p>';			  
  die($response); 
}
  
add_action('wp_ajax_nopriv_info_client_ajax', 'info_client_ajax');
add_action('wp_ajax_info_client_ajax', 'info_client_ajax');
add_action('wp_ajax_nopriv_info_produit_ajax', 'info_client_ajax');
add_action('wp_ajax_info_produit_ajax', 'info_client_ajax');

//shortcode for search

add_shortcode('search2smedia', 'search2smedia_link_shortcode');
function search2smedia_link_shortcode(){
	electro_navbar_search();
}

//custom fields for prducts
add_action( 'woocommerce_after_shop_loop_item_title', 'bbloomer_woocommerce_product_excerpt', 35 );  
 
function bbloomer_woocommerce_product_excerpt() {
global $post;

if ( is_home() || is_shop() || is_product_category() || is_product_tag()|| is_page()|| is_product()|| is_filtered() ) {
   echo '<span>';
   echo '<strong>Origine: </strong>'.get_post_meta( $post->ID, 'origine', true ).'<br>';
   echo '<strong>Matiere: </strong>'.get_post_meta( $post->ID, 'matiere', true ).'<br>';
   echo '<strong>Processus: </strong>'.get_post_meta( $post->ID, 'processus', true ).'<br>';
   echo '<strong>Min Achat: </strong>'.get_post_meta( $post->ID, 'minachat', true ).'<br>';
   echo '<strong>Pays d\'expedition: </strong>'.get_post_meta( $post->ID, 'paysexpedition', true ).'<br>';
   
   echo '</span>';
}
}

//send message from store manager
add_action( 'admin_post_send_process_form', 'send_process_form' );
add_action( 'admin_post_nopriv_send_process_form', 'send_process_form' );
function send_process_form() {
rwpm_send();
        if(!current_user_can( 'customer' ))
		wp_redirect( '../store-manager/send/' ); 
}
//devis et bon de commande

function get_devismenu() {
echo'<div class="row" id="menudevis">
<div class="col-md-3 col-sm-12"><a href="devis-list" class="butondevis">Tous les devis</a></div>
<div class="col-md-3 col-sm-12"><a href="../devis-create" class="butondevis">Creer un devis</a></div>
<div class="col-md-3 col-sm-12"><a href="command-list" class="butondevis">Tous mes BC</a></div>
<div class="col-md-3 col-sm-12"><a href="store-manager" class="butonboutique">GERER MA BOUTIQUE</a></div>
</div>';
}

/*add_filter( 'wcfm_menus', 'devis_wcfm_menus', 20 );
function devis_wcfm_menus() {
	$devislink = get_site_url()."/devis-main";
	if( $website )
		echo '<p><i class="fa fa-globe" aria-hidden="true"></i><span><a target="_blank" href="'.$devislink.'">DEVIS ET BON DE COMMANDE</a></span></p>';
}*/

/*add_action( 'wcfmmp_store_after_address', function( $store_id ) {
	$website = get_user_meta( $store_id, 'website', true );
	if( $website )
		echo '<p><i class="fa fa-globe" aria-hidden="true"></i><span><a target="_blank" href="'.$website.'">' . $website . '</a></span></p>';
}, 50 );*/

