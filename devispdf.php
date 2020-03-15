<?php
/**
 * @package Devis 2s Media
 *
 * @author: Cheikh
 * @url: http://www.cg-numerics.com
 * @email: bayecheikhgueye2@gmail.com

 Template Name: Devis PDF

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
    if (isset($_GET['id'])) {
		global $wpdb;
		global $WCFM, $WCFMmp;
	$id_devis = $_GET["id"];
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
		    $devise = "$";		
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
    }
	
?>
<?php
	/*while (ob_get_level())
    ob_end_clean();
    header("Content-Encoding: None", true);
	ob_start();
    require('fpdf/fpdf.php');
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(40,10,'DEVIS '.$numdevis);
    $pdf->Output('I');
    ob_end_flush(); */
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

<table style="width:100%; margin-bottom:30px;">
                    <tr>
                        <td colspan="2" align="top">
                          <img style="width:200px; margin:30px;" src="'.$gravatar.'" alt="logo"/>
                        </td>
						<td colspan="2" >
                       						
                        <strong>SOCIETE FOURNISSEUR : </strong>'.get_user_meta( $vendor_id,"store_name", true).'<br>
			            <strong>EMAIL : </strong>'.get_userdata($vendor_id)->user_email.'<br>
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
						$html.='<tr>
                            
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
$mpdf->Output('devis.pdf', 'I');
ob_end_flush();
?>
<?php 

get_footer();
