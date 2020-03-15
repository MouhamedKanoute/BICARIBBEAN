<?php
/**
 * @package Devis 2s Media
 *
 * @author: Cheikh
 * @url: http://www.cg-numerics.com
 * @email: bayecheikhgueye2@gmail.com

 Template Name: Devis Detail

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

    global $wpdb;
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
		    $devise = $s->devise;		
		    $commentaire = $s->commentaire;
			$transitaire = $s->transitaire;
			$territoiresortie = $s->territoiresortie;
			$territoirearrivee = $s->territoirearrivee;
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
                            <?php echo $numdevis; ?>
                        </td>
                        <th class="ss-th-width">Date de création</th>
                        <td>
                          <?php echo $datecreation; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="ss-th-width th_numdevisinterne">Numéro de devis interne</th>
                        <td>
                            <?php echo $numdevisinterne; ?>
                        </td>
						<th class="ss-th-width th_transitaire">Transitaire</th>
                        <td >
                           <?php echo $transitaire; ?>
                        </td>
                    </tr>
					<tr>
                        <th class="ss-th-width th_terrsort">Territoire de sortie</th>
                        <td >
                            <?php echo $territoiresortie; ?>
                        </td>
						<th class="ss-th-width th_terrarriv">Territoire d'arrivée</th>
                        <td >
                            <?php echo $territoirearrivee; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="ss-th-width th_client">Client</th>
                        <td>
							<?php echo get_user_meta( $customer_id,'store_name', true); ?>
						   
                        </td>
                        <td colspan="2">
                        <div id="infoclient" class="infoclient">
						<?php 
						
                        $response = '
			            <strong>SOCIETE FOURNISSEUR : </strong>'.get_user_meta( $vendor_id,"store_name", true).'<br>
			            <strong>EMAIL : </strong>'.get_userdata($vendor_id)->user_email.'<br>
			            <strong>ADRESSE POSTALE : </strong>'.get_user_meta($vendor_id,"_wcfm_zip", true).'<br>                        
			            <strong>TERRITOIRE : </strong>'.get_user_meta($vendor_id,"_wcfm_state", true).'<br>	
                        <strong>N°IMMATRICULATION: </strong><br><br><br>';
						echo $response;
						?>
						</div> 
						<div class="ajax-loader">
                        <img src="<?php echo get_stylesheet_directory_uri();?>/loading.gif" class="img-responsive" />
                        </div>
                        						
                        </td>
                    </tr>
                    <tr>
                        <th class="ss-th-width th_devise">Devise du devis</th>
                        <td colspan="3">
							
							<?php if($devise == 'euro') echo 'EURO';?>
							<?php if($devise == 'dollar') echo 'DOLLAR';?> 
							
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
					<?php if(!empty($produits)){
					    for($i = 0; $i<count($produits); $i++) {?>
						
						
                        <tr id='addr<?php echo $i;?>'>
						
                            <td><?php echo $i;?></td>
                            <td class="td_designation">
							
								
                                 <?php
								 if(current_user_can('administrator'))
								 $args2 = array('post_type' => 'product' ,'posts_per_page' => -1);
							     else
                                 $args2 = array( 'author' => get_current_user_id(),'post_type' => 'product' ,'posts_per_page' => -1);
                                 $products2 = get_posts( $args2 );
								 
                                 foreach( $products2 as $product ) : 
                                     //$selectedp = ( $product->ID == $produits[$i]->id_produit ) ? ' selected="selected"' : '';
									 if($product->ID == $produits[$i]->id_produit)
									 echo $product->post_title;										 
                                 endforeach;
                                 ?>
                                
                            </td>
                            
                            <td class="td_descriptionp"> 
                                
								<div class="details"><?php echo $produits[$i]->detailproduit;?></div>
                            </td>
							<td class="td_qualite"> 
                                
								<div class="qualite"><?php echo $produits[$i]->qualite;?></div>
                            </td>
                          
							<td class="td_price">
                                <?php echo $produits[$i]->prixunitaire;?>
                            </td>
                            <td class="td_qty">
                                <?php echo $produits[$i]->quantite;?>
                            </td>
							<td class="td_poidunitaire">
                                <?php echo $produits[$i]->poidunitaire;?>
                            </td>
							<td class="td_poidtotal">
                                <?php echo $produits[$i]->poidtotal;?>
                            </td>
                            
							<td class="td_conditionnement">
                                <?php echo $produits[$i]->conditionnement;?>
                            </td>
                            <td class="td_total">
                                <?php echo $produits[$i]->total;?>
                            </td>
                        </tr>
					<?php }}?>
                        
					
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="row" style="margin-top:20px;">
            <div class="pull-right col-md-4">
                <table class="table table-bordered table-hover" id="tab_logic_total">
                    <tbody>
                        <tr>
                            <th class="th_totalproduit">TOTAL PRODUIT</th>
                            <td >
                                <?php echo $totalproduit; ?>
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
					    <?php if(!empty($taxefrais)){
					    for($i = 0; $i<count($taxefrais); $i++) {?>
						<tr id='TFaddr<?php echo $i;?>'>
                            
                            <td><?php echo $i;?></td>
                            
                            <td class="td_taxeoufrais">
                                <?php echo $taxefrais[$i]->taxeoufrais; ?>
                            </td>
                            <td class="td_type">
                                <?php echo $taxefrais[$i]->type; ?>
                            </td>
                            <td class="td_libelle">
                                <?php echo $taxefrais[$i]->libelle; ?>
                            </td>
                            <td class="td_taux">
                                <?php echo $taxefrais[$i]->taux; ?>
                            </td>
							<td class="td_montanttaxe">
                                <?php echo $taxefrais[$i]->montanttaxe; ?>
                            </td>
                            <td class="td_valeur">
                                <?php echo $taxefrais[$i]->valeur; ?>
                            </td>
                            
                            
                        </tr>
						<?php }}?>
                        
						
						
						
                        
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="row clearfix" style="margin-top:20px">
            <div class="pull-right col-md-4">
                <table class="table table-bordered table-hover" id="TFtab_logic_total">
                    <tbody>
                        <tr>
                            <th class="totaltaxeetfrais">TOTAL TAXES ET FRAIS DIVERS</th>
                            <td >
                             <?php echo $totaltaxe; ?>
                            </td>
                        </tr>
                        
                        <tr>
                            <th class="totalgeneral">TOTAL GENERAL</th>
                            <td >
                                <?php echo $totalgeneral; ?>
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
                                <?php echo $commentaire;?>
                            </td>
                        </tr>
						
                    </tbody>
                </table>

            </div>
        </div>
	
    </form>
	</div>
</main>
</div>
<?php 

get_footer();
