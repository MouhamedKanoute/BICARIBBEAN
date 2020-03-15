<?php
/**
 * @package Devis 2s Media
 *
 * @author: Cheikh
 * @url: http://www.cg-numerics.com
 * @email: bayecheikhgueye2@gmail.com

 Template Name: Devis Create

 */
 
remove_action( 'electro_content_top', 'electro_breadcrumb', 10 );

do_action( 'electro_before_homepage_v3' );

$home_v3 		= electro_get_home_v3_meta();
$header_style 	= isset( $home_v3['header_style'] ) ? $home_v3['header_style'] : 'v3';

electro_get_header( $header_style );
get_devismenu();
?>

<?php
/*if (!is_user_logged_in()) {
	redirect_to_login_url();
}*/

//get_header();
//get_devismenu();
//$id = $_POST["id"];

    //insert
    if (isset($_POST['insertdevis'])) {
		$entete ='devis';
      insert_devis($entete);
    }
	
	if (isset($_POST['insertbc'])) {
		$entete ='bc';
      insert_devis($entete);
    }
	if (isset($_POST['insertandsenddevis'])) {
		$entete ='devis';
      $id_devis = insert_devis($entete);
	  send_devis($id_devis); 
    }
	
	function insert_devis($entete){
		$numdevis = $_POST["numdevis"];
		$numdevisinterne = $_POST["numdevisinterne"];
		$statut = '';
		$datecreation = $_POST["datecreation"];
		$totalproduit = $_POST["totalproduit"];
		$totaltaxe = $_POST["totaltaxe"];
		$totalgeneral = $_POST["totalgeneral"];

		$vendor_id = get_current_user_id();
		$customer_id = $_POST["customer_id"];
	
		$devise = $_POST["devise"];
		
		$commentaire = $_POST["commentaire"];
		$transitaire = $_POST["transitaire"];
		$territoiresortie = $_POST["territoiresortie"];
		$territoirearrivee = $_POST["territoirearrivee"];
		
        global $wpdb;
        $table_devis = $wpdb->prefix."devis";
		$table_produits = $wpdb->prefix."devis_produits";
		$table_taxe_frais = $wpdb->prefix."devis_taxes_frais";
        $data_devis = array(
					'id'        => NULL,
					'numdevis'   => $numdevis,
					'numdevisinterne'    => $numdevisinterne,
					'datecreation'   => $datecreation,
					'vendor_id' => $vendor_id,
					'customer_id' => $customer_id,
					'totalproduit'   => $totalproduit,
					'totaltaxe'   => $totaltaxe,
					'totalgeneral'    => $totalgeneral,
					'devise' => $devise,
					'entete' => $entete,
					'commentaire'   => $commentaire,
					'statut' => $statut,
					'transitaire' => $transitaire,
					'territoiresortie' => $territoiresortie,
					'territoirearrivee' => $territoirearrivee
				);
        $wpdb->insert(
                $table_devis, //table
                $data_devis, //data
                array('%d', '%s','%s', '%s','%s','%s','%s', '%s','%s', '%s','%s', '%s','%s','%s','%s','%s') //data format			
        );
		
		$id_devis=$wpdb->insert_id;
		
		//INSERT LIST PRODUCTS
		for($i = 0; $i<count($_POST['id_produit']); $i++)  
        { 
	       $data_produits = array(
					'id'        => NULL,
					'id_devis'   => $id_devis,
					'id_produit'    => $_POST['id_produit'][$i],
					'detailproduit'    => $_POST['detailproduit'][$i],
                    'qualite'    => $_POST['qualite'][$i],
					'prixunitaire' => $_POST['prixunitaire'][$i],
					'quantite' => $_POST['qty'][$i],
					'poidunitaire'   => $_POST['poidunitaire'][$i],
					'poidtotal'   => $_POST['poidtotal'][$i],
					
					'conditionnement'   => $_POST['conditionnement'][$i],
					'total' => $_POST['total'][$i]
			);
			$wpdb->insert(
                $table_produits, //table
                $data_produits, //data
                array('%d', '%s','%s','%s','%s', '%s','%s','%s', '%s','%s', '%s') //data format			
            );
	    } 
		
		//INSERT LIST TAXES ET FRAIS
		for($i = 0; $i<count($_POST['taxeoufrais']); $i++)  
        { 
	       $data_taxe_frais = array(
					'id'        => NULL,
					'id_devis'   => $id_devis,
					'taxeoufrais'    => $_POST['taxeoufrais'][$i],
					'type'   => $_POST['type'][$i],
					'libelle' => $_POST['libelle'][$i],
					'taux' => $_POST['taux'][$i],
					'valeur'   => $_POST['valeur'][$i],
					'montanttaxe'   => $_POST['montanttaxe'][$i]
			);
			$wpdb->insert(
                $table_taxe_frais, //table
                $data_taxe_frais, //data
                array('%d', '%s','%s', '%s','%s','%s','%s', '%s') //data format			
            );
	    } 
		
		return $id_devis;
	}
	
	function send_devis($id_devis){
		
	global $wpdb;
	$table_devis = $wpdb->prefix."devis";
	$table_produits = $wpdb->prefix."devis_produits";
	$table_taxe_frais = $wpdb->prefix."devis_taxes_frais";
	$produits = $wpdb->get_results($wpdb->prepare("SELECT * from $table_produits where id_devis=%s", $id_devis));
	$taxefrais = $wpdb->get_results($wpdb->prepare("SELECT * from $table_taxe_frais where id_devis=%s", $id_devis));
    //insert
    	
        $devis = $wpdb->get_results($wpdb->prepare("SELECT * from $table_devis where id=%s", $id_devis));
		$produits = $wpdb->get_results($wpdb->prepare("SELECT * from $table_produits where id_devis=%s", $id_devis));
		$taxefrais = $wpdb->get_results($wpdb->prepare("SELECT * from $table_taxe_frais where id_devis=%s", $id_devis));
		
        foreach ($devis as $s) {
            $entete = $s->entete;
			$numdevis = $s->numdevis;
			$numdevisinterne = $s->numdevisinterne;
			$datecreation = $s->datecreation;
			$totalproduit = $s->totalproduit;
		    $totaltaxe = $s->totaltaxe;
		    $totalgeneral = $s->totalgeneral;

		    $vendor_id = $s->vendor_id;
		    $customer_id = $s->customer_id;	
		    if($s->devise=="euro")
		    $devise = "€";
            if($s->devise=="dollar")		
		    $commentaire = $s->commentaire;
			$transitaire = $s->transitaire;
			$territoiresortie = $s->territoiresortie;
			$territoirearrivee = $s->territoirearrivee;
			
			$store_user      = wcfmmp_get_store( $vendor_id );
			$store_info      = $store_user->get_shop_info();
$gravatar        = $store_user->get_avatar();
$banner_type     = $store_user->get_list_banner_type();
if( $banner_type == 'video' ) {
	$banner_video = $store_user->get_list_banner_video();
} else {
	 $banner = $store_user->get_list_banner();
	 if( !$banner )
	    {
			$banner = isset( $WCFMmp->wcfmmp_marketplace_options['store_list_default_banner'] ) ? $WCFMmp->wcfmmp_marketplace_options['store_list_default_banner'] : $WCFMmp->plugin_url . 'assets/images/default_banner.jpg';
			$banner = apply_filters( 'wcfmmp_list_store_default_bannar', $banner );
		}
}
$store_name      = isset( $store_info['store_name'] ) ? esc_html( $store_info['store_name'] ) : __( 'N/A', 'wc-multivendor-marketplace' );
$store_name      = apply_filters( 'wcfmmp_store_title', $store_name , $store_id );
$store_url       = wcfmmp_get_store_url( $store_id );
$store_address   = $store_user->get_address_string(); 
		}

    ob_end_clean();
header("Content-Encoding: None", true);
ob_start();	
include("mpdf/mpdf.php");
$mpdf=new mPDF();
$mpdf->setAutoBottomMargin = 'stretch'; 
$mpdf->SetHTMLFooter('<div style="text-align:center;"><i>Cette facture proforma est une estimation et ne peut être considérée comme contractuelle. Elle peut évoluée en fonctions des éléments constitutifs de cette dernière. Elle a été créée sur la plateforme bi-caribbean.shop.</i><br>
                      <hr><strong>SOCIETE FOURNISSEUR - Capital Social - N d\'immatriculation - Code APE</strong><br>
					  ADRESSE POSTALE - EMAIL - N TVA - Territoire</div>');
$html ='';
$html .='

<table style="width:100%; margin-bottom:30px;"><h1>
                    <tr>
                        <td colspan="2" align="top">
                          <img style="width:200px; margin:10px;" src="'.$gravatar.'" alt="logo"/>
                        </td>
						<td colspan="2" >
                       						
                        <strong>SOCIETE FOURNISSEUR : </strong>'.get_user_meta( $vendor_id,"store_name", true).'<br>
			            <strong>EMAIL : </strong>'. get_userdata($vendor_id)->user_email.'<br>
			            <strong>ADRESSE POSTALE : </strong>'.get_user_meta($vendor_id,"_wcfm_zip", true).'<br>                        
			            <strong>TERRITOIRE : </strong>'.get_user_meta($vendor_id,"_wcfm_state", true).'<br>	
                        <strong>N°IMMATRICULATION: </strong><br><br><br>						
                        </td>
                    </tr>
					<tr> 
                        <td colspan="2">
                        
                        </td>					
                        <td  colspan="2" >						
                        <strong>SOCIETE ACHETEUR : </strong>'.get_user_meta( $customer_id,"store_name", true).'<br>
						<strong>EMAIL : </strong>'.get_userdata($customer_id)->user_email.'<br>
			            <strong>ADRESSE POSTALE : </strong>'.get_user_meta($customer_id,"_wcfm_zip", true).'<br>
			            <strong>TERRITOIRE : </strong>'.get_user_meta($customer_id,"_wcfm_state", true).'<br>
                        <strong>N°IMMATRICULATION: </strong><br>						
                        </td>
                    </tr>
					<tr>
                       
                        <td colspan="2" align="left">
						<div style="color:blue;">N°interne à l\'entreprise:</div>
                           <div style=""> '.$numdevisinterne.'</div>
                        </td>
                        <td colspan="2" align="right">
						<span style="color:blue;">Transitaire : </span>
						<span>'.$transitaire.'</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                         <div style="color:blue;">Date du devis:</div>
						 '.$datecreation.'
                        </td>
                   
                        <td>
                         <div style="color:blue;">Vendeur:</div>
						 '.get_user_meta( $vendor_id,"store_name", true).'
                        </td>
                  
                        <td >
						   <div style="color:blue;"> Port sortie marchandise:</div>
                            '.$territoiresortie.'
                        </td>
						
                        <td >
						    <div style="color:blue;">Port d\'entrée marchandise:</div>
                            '.$territoirearrivee.'
                        </td>
                    </tr>
                </table>
				
				<table border="1" style="width:100%; margin-bottom:30px;">
                    <thead>
                     
                        <tr>
                            <th class="th_designation"> # </th>
                           
                            <th class="th_designation"> Désignation</th>
                            <th class="th_descriptionp"> Description</th>
                            <th class="th_qualite"> Qualité</th>
                            <th class="th_price"> Prix unitaire </th>
                            <th class="th_qty"> Qt </th>
							<th class="th_poidunitaire"> Poids unitaire(kg) </th>
                            <th class="th_poidtotaln"> Poids total(kg)</th>                           							
							<th class="th_conditionnement"> Conditionnement </th>
                            <th class="th_totalproduit"> Montant </th>
                        </tr>
                    </thead>
                    <tbody>';
					if(!empty($produits)){
					    for($i = 0; $i<count($produits); $i++) {
						
						
                        $html.='<tr>
						
                            <td>'.$i.'</td>
                            <td class="td_designation">';
							
								 if(current_user_can('administrator'))
								 $args2 = array('post_type' => 'product' ,'posts_per_page' => -1);
							     else
                                 $args2 = array( 'author' => get_current_user_id(),'post_type' => 'product' ,'posts_per_page' => -1);
                                 $products2 = get_posts( $args2 );
								 
                                 foreach( $products2 as $product ) : 
                                     //$selectedp = ( $product->ID == $produits[$i]->id_produit ) ? ' selected="selected"' : '';
									 if($product->ID == $produits[$i]->id_produit)
									 $html.= $product->post_title;										 
                                 endforeach;
                                 
                                
                            $html.='</td>
                            
                            <td class="td_descriptionp"> 
                                
								<div class="details">'.$produits[$i]->detailproduit.'</div>
                            </td>
							<td class="td_qualite"> 
                                
								<div class="qualite">'.$produits[$i]->qualite.'</div>
                            </td>
                          
							<td class="td_price">
                                '.$produits[$i]->prixunitaire.' '.$devise.'
                            </td>
                            <td class="td_qty">
                                '.$produits[$i]->quantite.'
                            </td>
							<td class="td_poidunitaire">
                                '.$produits[$i]->poidunitaire.'
                            </td>
							<td class="td_poidtotal">
                                '.$produits[$i]->poidtotal.'
                            </td>
                            
							<td class="td_conditionnement">
                                '.$produits[$i]->conditionnement.'
                            </td>
                            <td class="td_total">
                                '.$produits[$i]->total.' '.$devise.'
                            </td>
                        </tr>';
					}}
                        
					
                   $html.=' </tbody>
                </table>';
				
				$html.='<table border="1" style="width:100%; margin-bottom:30px;">
                    <thead style="background-color:#ffff;">
                        <tr>
                            <th  colspan="8"> LISTE DES FRAIS ET TAXES</th>
                        </tr>
                        <tr>
                            <th > # </th>
                            <th > Taxe ou frais </th>
                            <th > Type</th>
                            <th > Libellé</th>
                            <th > Taux</th>
							 <th > Montant taxé</th>
                            <th > Valeur taxe ou frais</th>
                           
                            
                        </tr>
                    </thead>
                    <tbody>';
					    if(!empty($taxefrais)){
					    for($i = 0; $i<count($taxefrais); $i++) {
						$html.='<tr  id=\'addr0\'>
                            
                            <td>'.$i.'</td>
                            
                            <td class="td_taxeoufrais">
                                '.$taxefrais[$i]->taxeoufrais.'
                            </td>
                            <td class="td_type">
                                '.$taxefrais[$i]->type.'
                            </td>
                            <td class="td_libelle">
                                '.$taxefrais[$i]->libelle.'
                            </td>
                            <td class="td_taux">
                                '.$taxefrais[$i]->taux.'
                            </td>
							<td class="td_montanttaxe">
                                '.$taxefrais[$i]->montanttaxe.' '.$devise.'
                            </td>
                            <td class="td_valeur">
                                '.$taxefrais[$i]->valeur.' '.$devise.'
                            </td>
                            
                            
                        </tr>';
						}}
                        
						
						
						
                        
                   $html.=' </tbody>
                </table>';
				
				$html.='<table border="1" style="width:100%; margin-bottom:30px;">
                    <tbody>
					    <tr>
                            <th class="totaltaxeetfrais">SOUS TOTAL</th>
                            <td >
                             '.$totalproduit.' '.$devise.'
                            </td>
                        </tr>
                        <tr>
                            <th class="totaltaxeetfrais">TAXES ET FRAIS</th>
                            <td >
                             '.$totaltaxe.' '.$devise.'
                            </td>
                        </tr>
                        
                        <tr>
                            <th class="totalgeneral">TOTAL</th>
                            <td >
                                '.$totalgeneral.' '.$devise.'
                            </td>
                        </tr>
                    </tbody>
                </table>';
				
				$html.='<table style="width:100%;">
                    <tbody>
					    <tr>
                            
                            <td >
							Commentaire:<br>
                             '.$commentaire.'
                            </td>
                        
                            <td style="border:solid 1px;">
                             Valeur marchandise:<br>
							 Poids:<br>
							 Volume:
                            </td>
                        </tr>
                    
                    </tbody>
                </table>';
$mpdf->WriteHTML($html);

// FOR EMAIL
$content = $mpdf->Output('devis.pdf','S'); // Saving pdf to attach to email 
$content = chunk_split(base64_encode($content));

// Email settings
$admin_email = get_bloginfo('admin_email');
$vendor_email = get_userdata($vendor_id)->user_email;
$customer_email = get_userdata($customer_id)->user_email;
$from_name = 'BI CARIBBEAN';
$from_mail = 'contact@bi-caribbean.shop';
$replyto = $vendor_email;
$uid = md5(uniqid(time())); 
$subject = 'Devis bi caribbean PDF';
$message = 'Download the attached pdf';
$filename = 'devis.pdf';

// header
$header = "From: ".$from_name." <".$from_mail.">\r\n";
$header .= "Reply-To: ".$replyto."\r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";

// message & attachment
$nmessage = "--".$uid."\r\n";
$nmessage .= "Content-type:text/plain; charset=iso-8859-1\r\n";
$nmessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$nmessage .= "--".$uid."\r\n";
$nmessage .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n";
$nmessage .= "Content-Transfer-Encoding: base64\r\n";
$nmessage .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
$nmessage .= $content."\r\n\r\n";
$nmessage .= "--".$uid."--";

$test_mail = wp_mail("bayecheikhgueye2@gmail.com", $subject, $nmessage, $header);
$mail1 = wp_mail($vendor_email, $subject, $nmessage, $header);
$mail2 = wp_mail($customer_email, $subject, $nmessage, $header);
$mail3 = wp_mail($admin_email, $subject, $nmessage, $header);

$mpdf->Output('devis.pdf', 'I');
ob_end_flush();		
	}
?>

<div id="primary" class="container">
<main id="main" class="site-main">
<div class="container-full-width">
    <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	  <div class="row">
	    <div class="col-md-12">
                <table class="table table-bordered table-hover infogenerale">
                    <thead style="background-color:#ffff;">
                        <tr>
                            <th  colspan="4" class="th_infogeneral"> INFORMATIONS GENERALES</th>
                        </tr>
                    </thead>
                    <tr>
                        <th class="ss-th-width">Numéro de devis</th>
                        <td>
                            <input id="numdevis" type="text" name="numdevis" value="<?php echo $numdevis; ?>" class="ss-field-width numdevis" readonly />
                        </td>
                        <th class="ss-th-width">Date de création</th>
                        <td>
                            <input type="text" name="datecreation" value="<?php echo date('d-m-Y H:i'); ?>" class="ss-field-width" readonly />
                        </td>
                    </tr>
                    <tr>
                        <th class="ss-th-width th_numdevisinterne">Numéro de devis interne</th>
                        <td >
                            <input type="text" name="numdevisinterne" value="<?php echo $statut; ?>" class="ss-field-width" />
                        </td>
						<th class="ss-th-width th_transitaire">Transitaire</th>
                        <td >
                            <input type="text" name="transitaire" value="<?php echo $transitaire; ?>" class="ss-field-width" />
                        </td>
                    </tr>
					<tr>
                        <th class="ss-th-width th_terrsort">Territoire de sortie</th>
                        <td >
                            <input type="text" name="territoiresortie" value="<?php echo $territoiresortie; ?>" class="ss-field-width" />
                        </td>
						<th class="ss-th-width th_terrarriv">Territoire d'arrivée</th>
                        <td >
                            <input type="text" name="territoirearrivee" value="<?php echo $territoirearrivee; ?>" class="ss-field-width" />
                        </td>
                    </tr>
                    <tr>
                        <th class="ss-th-width th_client">Client</th>
                        <td>
                
							<?php 
							// Get users vendors only for customers
						    /*if(current_user_can( 'customer' ))
						    $args = array(	
                            'role'   => 'wcfm_vendor',					
							'order'   => 'ASC',
							'orderby' => 'display_name' );
							else*/
							$args = array(						
							'order'   => 'ASC',
							'orderby' => 'display_name' );
						    $values = get_users( $args );
						    $values = apply_filters( 'rwpm_recipients', $values );
							?>
							<select name="customer_id" size="5" id="client" >
							<?php
							foreach ( $values as $value )
							{
								$selected = ( $value->display_name == $recipient ) ? ' selected="selected"' : '';
								echo "<option value='$value->id'$selected nicename='$value->user_nicename'>$value->user_nicename</option>";
							}
							?>
						   </select>	   
						   
                        </td>
                        <td colspan="2">
                        <div id="infoclient" class="infoclient"></div> 
						<div class="ajax-loader">
                        <img src="<?php echo get_stylesheet_directory_uri();?>/loading.gif" class="img-responsive" />
                        </div>
                        						
                        </td>
                    </tr>
                    <tr>
                        <th class="ss-th-width th_devise">Devise du devis</th>
                        <td colspan="3">
							<select name="devise" class="ss-field-width" id="devise">
							<option value="">Choisir une option</option>
							<option value="euro">EUROS</option>
							<option value="dollar">DOLLARS US</option>
							</select>
                        </td>
                    </tr>
                </table>
            </div>
	    </div>		
        <div class="row" style="overflow-x:scroll;">
            <div class="col-md-12">
                <table id="tab_logic" >
                    <thead>
                        <tr>
                            <th colspan="14" class="text-center th_list"> LISTE DES PRODUITS</th>
                        </tr>
                        <tr>
                            <th class="th_designation"> # </th>
                           
                            <th class="th_designation"> Désignation</th>
                            <th class="th_descriptionp"> Détails du produit</th>
                            <th class="th_qualite"> Qualité</th>
                            <th class="th_price"> Prix unitaire </th>
                            <th class="th_qty"> Qt </th>
							<th class="th_poidunitaire"> Poids unitaire(kg) </th>
                            <th class="th_poidtotaln"> Poids total(kg)</th>
                            
							<th class="th_conditionnement"> Conditionnement </th>
                            <th class="th_totalproduit"> Total </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id='addr0'>
                            <td>1</td>
                            <td class="td_designation" >
                                
								<select name='id_produit[]' class="designation">
                                 <?php
								 //if(current_user_can('administrator'))
								 $args2 = array('author' => get_current_user_id(),'post_type' => 'product' ,'posts_per_page' => -1);
							     //else
                                 //$args2 = array( 'author' => get_current_user_id(),'post_type' => 'product' ,'posts_per_page' => -1);
                                 $products2 = get_posts( $args2 );
                                 foreach( $products2 as $product ) : 
                                      echo '<option selected value="'.$product->ID.'" descriptionp="'.esc_html($product->post_excerpt).'">'.$product->post_title.'</option>';
                                 endforeach;
                                 ?>
                                </select>
                            </td>
                            
                            <td class="td_descriptionp">
                                <input type="hidden" name='detailproduit[]' placeholder='Details du produit' class="descriptionp" readonly />
								<div class="details"></div>
                            </td>
                           <td class="td_qualite">
                                <input type="text" name='qualite[]' placeholder='Qualité' class="qualite" size="1"/>
								
                            </td>
							<td class="td_price">
                                <input type="number" name='prixunitaire[]' placeholder='Prix unitaire' class="price" step="any" min="0" size="1"/>
                            </td>
                            <td class="td_qty">
                                <input type="number" name='qty[]' placeholder='Quantité' class="qty" step="any" min="0" size="1"/>
                            </td>
							<td class="td_poidunitaire">
                                <input type="number" name='poidunitaire[]' placeholder='Poids unitaire' class="poidunitaire" step="any" min="0" size="1"/>
                            </td>
							<td class="td_poidtotal">
                                <input type="number" name='poidtotal[]' placeholder='Poids total' class="poidtotal" readonly size="1"/>
                            </td>
							
                            
							<td class="td_conditionnement">
                                <input type="text" name='conditionnement[]' placeholder='Conditionnement' class="conditionnement"/>
                            </td>
                            <td class="td_total">
                                <input type="number" name='total[]' placeholder='0.00' class="total" readonly />
                            </td>
                        </tr>
                        <tr id='addr1'></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <a id="add_row" class="btn btn-default pull-left add_row">Ajouter un produit</a>
                <a id='delete_row' class="pull-right btn btn-default delet_row">Supprimer le produit</a>
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
            <div class="pull-right col-md-4">
                <table class="table table-bordered table-hover" id="tab_logic_total">
                    <tbody>
                        <tr>
                            <th class="th_totalproduit">TOTAL PRODUIT</th>
                            <td >
                                <input type="number" name='totalproduit' placeholder='0.00' class="" id="sub_total" readonly />
                            </td>
                        </tr>
                        
                    </tbody>
                </table>

            </div>
        </div>
		<div class="row clearfix" style="margin-top:20px; overflow-x:scroll;">
            <div class="col-md-12">
                <table class="table table-bordered table-hover" id="TFtab_logic">
                    <thead style="background-color:#ffff;">
                        <tr>
                            <th  colspan="8"> LISTE DES FRAIS ET TAXES</th>
                        </tr>
                        <tr>
                            <th > # </th>
                            <th > Taxe ou frais </th>
                            <th > Type</th>
                            <th > Libellé</th>
                            <th > Taux</th>
							<th > Montant taxé</th>
                            <th > Valeur taxe ou frais</th>
                            
                           
                        </tr>
                    </thead>
                    <tbody>
                        <tr id='TFaddr0'>
                            <td class="td_devis">1</td>
                            <td class="td_taxeoufrais">
                                <input type="text" name='taxeoufrais[]' placeholder='Taxe ou frais' class="" />
                            </td>
                            <td class="td_type">
                                <input type="text" name='type[]' placeholder='Type' class="" />
                            </td>
                            <td class="td_libelle">
                                <input type="text" name='libelle[]' placeholder='Libellé' class="" />
                            </td>
                            <td class="td_taux">
                                <input type="number" name='taux[]' placeholder='Taux' class="taux" step="any" min="0" />
                            </td>
							<td class="td_montanttaxe">
                                <input type="number" name='montanttaxe[]' placeholder='Montant taxé' class=" montanttaxe" step="any" min="0" />
                            </td>
                         
                            <td class="td_valeur">
                                <input type="number" name='valeur[]' placeholder='Valeur' class="valeur" readonly />
                            </td>
                            
                        </tr>
                        <tr id='TFaddr1'></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <a id="TFadd_row" class="btn btn-default pull-left TFadd_row">Ajouter un frais ou taxe</a>
                <a id='TFdelete_row' class="pull-right btn btn-default TFdelete_row">Supprimer la ligne</a>
            </div>
        </div>
        <div class="row clearfix" style="margin-top:20px">
            <div class="pull-right col-md-4">
                <table class="table table-bordered table-hover" id="TFtab_logic_total">
                    <tbody>
                        <tr>
                            <th class="totaltaxeetfrais">TOTAL TAXES ET FRAIS DIVERS</th>
                            <td >
                                <input type="number" name='totaltaxe' placeholder='0.00' class="TFsub_total" id="TFsub_total" readonly />
                            </td>
                        </tr>
                        
                        <tr>
                            <th class="totalgeneral">TOTAL GENERAL</th>
                            <td >
                                <input type="number" name='totalgeneral' id="TFtotal_amount" placeholder='0.00' class="TFsub_total" readonly />
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
		<div class="row clearfix" style="margin-top:20px">
            <div class="col-md-12">
                <table class="table table-bordered table-hover commentaire" style="width:100%;">
                    <tbody>                      
                        
                        <tr>
                            <th colspan="1" class="th_commentaire">COMMENTAIRE</th>
                            <td colspan="2" class="td_commentaire">
                                <textarea name="commentaire"></textarea>
                            </td>
                        </tr>
						
                    </tbody>
                </table>

            </div>
        </div>
        
        <div class="row" style="margin-top:20px; margin-bottom:20px;">
          <div class="col-md-4 col-sm-12 td_insertdevi">
            <input type='submit' name="insertdevis" value='Enregistrer le devis' class='button insertdevis' readonly>
          </div>
          <div class="col-md-4 col-sm-12 td_insertdevisendsend">
            <input type='submit' name="insertandsenddevis" value='Enregistrer et envoyer le devis' class='button insertandsenddevis' readonly>
          </div>
          <div class="col-md-4 col-sm-12 insertbc">
            <input type='submit' name="insertbc" value='Transformer le devis en bon de commande' class='button insertbc' readonly>
          </div>
        </div>
	
    </form>
</div>
</main>
</div>
<?php 

get_footer();
