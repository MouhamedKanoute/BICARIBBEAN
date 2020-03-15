<?php
/**
 * @package Devis 2s Media
 *
 * @author: Cheikh
 * @url: http://www.cg-numerics.com
 * @email: bayecheikhgueye2@gmail.com

 Template Name: Devis Update

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
    if (isset($_POST['updatedevis'])) {
		$entete ='devis';
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
		
		
        
       
        $data_devis = array(
					
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
        $wpdb->update(
                $table_devis, //table
                $data_devis, //data
				array('ID' => $id_devis),
                array('%s','%s', '%s','%s','%s','%s', '%s','%s', '%s','%s', '%s','%s','%s', '%s','%s'), //data format	
                array('%s')				
        );
		
	
		
		//INSERT LIST PRODUCTS
		for($i = 0; $i<count($_POST['id_produit']); $i++)  
        { 
	       $id_produit=$_POST['id_produit'][$i];
	       $data_produits = array(
					'ID' => $_POST['id_produit1'][$i],
					'id_devis'   => $id_devis,
					'id_produit'   => $_POST['id_produit'][$i],
					'detailproduit'   => $_POST['detailproduit'][$i],
				    'qualite'   => $_POST['qualite'][$i],
					'prixunitaire' => $_POST['prixunitaire'][$i],
					'quantite' => $_POST['qty'][$i],
					'poidunitaire'   => $_POST['poidunitaire'][$i],
					'poidtotal'   => $_POST['poidtotal'][$i],
					
					'conditionnement'   => $_POST['conditionnement'][$i],
					'total' => $_POST['total'][$i]
			);
			$wpdb->replace(
                $table_produits, //table
                $data_produits, //data
			
                array('%d', '%s','%s','%s','%s','%s','%s', '%s','%s', '%s','%s') //data format	
               				
            );
	    } 
		
		//INSERT LIST TAXES ET FRAIS
		for($i = 0; $i<count($_POST['taxeoufrais']); $i++)  
        { 
	       $data_taxe_frais = array(
		            'ID' => $_POST['id_taxeoufrais1'][$i],
					'id_devis'   => $id_devis,
					'taxeoufrais'    => $_POST['taxeoufrais'][$i],
					'type'   => $_POST['type'][$i],
					'libelle' => $_POST['libelle'][$i],
					'taux' => $_POST['taux'][$i],
					'valeur'   => $_POST['valeur'][$i],
					'montanttaxe'   => $_POST['montanttaxe'][$i]
					
			);
			$wpdb->replace(
                $table_taxe_frais, //table
                $data_taxe_frais, //data
                array('%d','%s','%s', '%s','%s','%s','%s', '%s') //data format		
               			
            );
	    } 
		wp_redirect( 'devis-list' );
    }
	else {//selecting value to update	
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

		    $vendor_id = get_current_user_id();
		    $customer_id = $s->customer_id;	
		    $devise = $s->devise;		
		    $commentaire = $s->commentaire;
			$transitaire = $s->transitaire;
			$territoiresortie = $s->territoiresortie;
			$territoirearrivee = $s->territoirearrivee;
        }
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
                            <input type="text" name="numdevisinterne" value="<?php echo $numdevisinterne; ?>" class="ss-field-width" />
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
                
							<input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" class="ss-field-width" readonly />
							<?php echo get_user_meta( $customer_id,'store_name', true); ?>
						   
                        </td>
                        <td colspan="2">
                        <div id="infoclient" class="infoclient">
						<?php 
						$idclient = $customer_id;
                        $boutique = get_user_meta( $idclient,'store_name', true);
                        $ville=get_user_meta( $idclient,'_wcfm_city', true);
                        $codepostal = get_user_meta( $idclient,'_wcfm_zip', true);
                        $adress=get_user_meta( $idclient,'_wcfm_street_1', true);
                        $pays=get_user_meta( $idclient,'_wcfm_state', true);
                        $response = '<strong>Boutique : </strong>'.$boutique.'<br>
			            <strong>Adresse : </strong>'.$adress.'<br>
			            <strong>Code postal : </strong>'.$codepostal.'<br>
                        <strong>Ville : </strong>'.$ville.'<br>
			            <strong>Pays : </strong>'.$adress.'<br>';
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
							<select name="devise" class="ss-field-width" id="devise">
							<option value="">Choisir une option</option>
							<option value="euro" <?php if($devise == 'euro') echo 'selected="selected"';?> >EUROS</option>
							<option value="dollar" <?php if($devise == 'dollar') echo 'selected="selected"';?> ">DOLLARS US</option>
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
					<?php if(!empty($produits)){
					    for($i = 0; $i<count($produits); $i++) {?>
						
						
                        <tr id='addr<?php echo $i;?>'>
						<input type="hidden" value="<?php echo $produits[$i]->id; ?>" name='id_produit1[]' placeholder='' class="" readonly size="1"/>
                            <td><?php echo $i;?></td>
                            <td class="td_designation">
							
								<select name='id_produit[]' class="designation">
                                 <?php
								 if(current_user_can('administrator'))
								 $args2 = array('post_type' => 'product' ,'posts_per_page' => -1);
							     else
                                 $args2 = array( 'author' => get_current_user_id(),'post_type' => 'product' ,'posts_per_page' => -1);
                                 $products2 = get_posts( $args2 );
								 
                                 foreach( $products2 as $product ) : 
                                     //$selectedp = ( $product->ID == $produits[$i]->id_produit ) ? ' selected="selected"' : '';
									 if($product->ID == $produits[$i]->id_produit)
									 echo '<option value="'.$product->ID.'" descriptionp="'.esc_html($product->post_excerpt).'"  selected="selected">'.$product->post_title.'</option>';
                                     else
                                     echo '<option value="'.$product->ID.'" descriptionp="'.esc_html($product->post_excerpt).'">'.$product->post_title.'</option>';										 
                                 endforeach;
                                 ?>
                                </select>
                            </td>
                            
                            <td class="td_descriptionp"> 
                                <input type="hidden" value="<?php echo $produits[$i]->detailproduit;?>" name='detailproduit[]' placeholder='Details du produit' class="descriptionp" readonly />
								<div class="details"><?php echo $produits[$i]->detailproduit;?></div>
                            </td>
							<td class="td_qualite"> 
                                <input type="text" value="<?php echo $produits[$i]->qualite;?>" name='qualite[]' placeholder='qualite' class="qualite" size="1"/>
								
                            </td>
                            
							<td class="td_price">
                                <input type="number" value="<?php echo $produits[$i]->prixunitaire;?>" name='prixunitaire[]' placeholder='Prix unitaire' class="price" step="0.00" min="0" size="1"/>
                            </td>
                            <td class="td_qty">
                                <input type="number" value="<?php echo $produits[$i]->quantite;?>" name='qty[]' placeholder='Quantité' class="qty" step="0" min="0" size="1"/>
                            </td>
							<td class="td_poidunitaire">
                                <input type="number" value="<?php echo $produits[$i]->poidunitaire;?>" name='poidunitaire[]' placeholder='Poids unitaire' class="poidunitaire" step="0" min="0" size="1"/>
                            </td>
							<td class="td_poidtotal">
                                <input type="number" value="<?php echo $produits[$i]->poidtotal;?>" name='poidtotal[]' placeholder='Poids total' class="poidtotal" readonly size="1"/>
                            </td>
							
                            
							<td class="td_conditionnement">
                                <input type="text" value="<?php echo $produits[$i]->conditionnement;?>" name='conditionnement[]' placeholder='Conditionnement' class="conditionnement"/>
                            </td>
                            <td class="td_total">
                                <input type="number" value="<?php echo $produits[$i]->total;?>" name='total[]' placeholder='0.00' class="total" readonly />
                            </td>
                        </tr>
					    <?php }?>
                        <tr id='addr<?php echo count($produits);?>'></tr>
						<?php }else{?>
						<tr id='addr0'>
                            <td>1</td>
                            <td class="td_designation">
                                
								<select name='id_produit[]' class="designation">
                                 <?php
								 if(current_user_can('administrator'))
								 $args2 = array('post_type' => 'product' ,'posts_per_page' => -1);
							     else
                                 $args2 = array( 'author' => get_current_user_id(),'post_type' => 'product' ,'posts_per_page' => -1);
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
                                <input type="text"  name='qualite[]' placeholder='qualite' class="qualite" readonly />
								
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
						<?php }?>
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
                                <input type="number" name='totalproduit' value="<?php echo $totalproduit; ?>" placeholder='0.00' class="" id="sub_total" readonly />
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
                            <th > Valeur taxe ou frais </th>
                            
                           
                        </tr>
                    </thead>
                    <tbody>
					    <?php if(!empty($taxefrais)){
					    for($i = 0; $i<count($taxefrais); $i++) {?>
						<tr id='TFaddr<?php echo $i;?>'>
                            <input type="hidden" value="<?php echo $taxefrais[$i]->id; ?>" name='id_taxeoufrais1[]' placeholder='' class="" readonly size="1"/>
                            <td><?php echo $i;?></td>
                            
                            <td class="td_taxeoufrais">
                                <input type="text" value="<?php echo $taxefrais[$i]->taxeoufrais; ?>" name='taxeoufrais[]' placeholder='Taxe ou frais' class="" />
                            </td>
                            <td class="td_type">
                                <input type="text" value="<?php echo $taxefrais[$i]->type; ?>" name='type[]' placeholder='Type' class="" />
                            </td>
                            <td class="td_libelle">
                                <input type="text" value="<?php echo $taxefrais[$i]->libelle; ?>" name='libelle[]' placeholder='Libellé' class="" />
                            </td>
                            <td class="td_taux">
                                <input type="number" value="<?php echo $taxefrais[$i]->taux; ?>" name='taux[]' placeholder='Taux' class="taux" step="any" min="0" />
                            </td>
							<td class="td_montanttaxe">
                                <input type="number" value="<?php echo $taxefrais[$i]->montanttaxe; ?>" name='montanttaxe[]' placeholder='Montant taxé' class=" montanttaxe" step="any" min="0" />
                            </td>
                            <td class="td_valeur">
                                <input type="number" value="<?php echo $taxefrais[$i]->valeur; ?>" name='valeur[]' placeholder='Valeur' class="valeur" readonly />
                            </td>
                            
                           
                        </tr>
						<?php }?>
                        <tr id='TFaddr<?php echo count($taxefrais);?>'></tr>
						<?php }else{?>
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
                                <input type="number" name='taux[]' placeholder='Taux' class="taux" step="0.00" min="0" />
                            </td>
							<td class="td_montanttaxe">
                                <input type="number" name='montanttaxe[]' placeholder='Montant taxé' class=" montanttaxe" step="0" min="0" />
                            </td>
                            <td class="td_valeur">
                                <input type="number" name='valeur[]' placeholder='Valeur' class="valeur" readonly />
                            </td>
                            
                            
                        </tr>
						<tr id='TFaddr1'></tr>
						<?php }?>
                        
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
                                <input type="number" name='totaltaxe' value="<?php echo $totaltaxe; ?>" placeholder='0.00' class="TFsub_total" id="TFsub_total" readonly />
                            </td>
                        </tr>
                        
                        <tr>
                            <th class="totalgeneral">TOTAL GENERAL</th>
                            <td >
                                <input type="number" name='totalgeneral' id="TFtotal_amount" value="<?php echo $totalgeneral; ?>"placeholder='0.00' class="TFsub_total" readonly />
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
                                <textarea name="commentaire"><?php echo $commentaire;?></textarea>
                            </td>
                        </tr>
						
                    </tbody>
                </table>

            </div>
        </div>
        
        <div class="row" style="margin-top:20px; margin-bottom:20px;">
          <div class="col-md-4 col-sm-12 td_insertdevi">
            <input type='submit' name="updatedevis" value='Mettre à jour le devis' class='button insertdevis' >
          </div>
          
        </div>
	
    </form>
	</div>
</main>
</div>
<?php 

get_footer();
