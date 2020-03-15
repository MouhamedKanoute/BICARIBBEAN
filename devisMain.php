<?php
/**
 * @package Devis 2s Media
 *
 * @author: Cheikh
 * @url: http://www.cg-numerics.com
 * @email: bayecheikhgueye2@gmail.com
 
 Template Name: Devis Main Page
 
 */

  remove_action( 'electro_content_top', 'electro_breadcrumb', 10 );

do_action( 'electro_before_homepage_v3' );

$home_v3 		= electro_get_home_v3_meta();
$header_style 	= isset( $home_v3['header_style'] ) ? $home_v3['header_style'] : 'v3';

electro_get_header( $header_style );
get_devismenu();
?>

<div id="primary" class="container">
<main id="main" class="site-main">

    <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "devis";
		$vendor_id = get_current_user_id();
		
        if(current_user_can('administrator'))
        $rows = $wpdb->get_results("SELECT * from $table_name where entete='devis'");
	    else
		$rows = $wpdb->get_results("SELECT * from $table_name where vendor_id = $vendor_id AND entete='devis'");
        ?>
        <table class='devistable'>
            <tr>
                <th class="devistableTh">Numéro devis</th>
                <th class="devistableTh">Client</th>
                <th class="devistableTh">Devise</th>
                <th class="devistableTh">Montant HT(Hors transport)</th>
                <th class="devistableTh">Total taxe et frais</th>
                <th class="devistableTh">Montant TTC hors transport</th>
                <th class="devistableTh" colspan="6">Action</th>
                <th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
            </tr>
            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td class="devistableTd">
                        <?php echo $row->numdevis; ?>
                    </td>
                    <td class="devistableTd">
                        <?php echo get_user_meta( $row->customer_id,'store_name', true); ?>
                    </td>
                    <td class="devistableTd">
                        <?php echo $row->devise; ?>
                    </td>
                    <td class="devistableTd">
                        <?php echo $row->totalproduit; ?>
                    </td>
                    <td class="devistableTd">
                        <?php echo $row->totaltaxe; ?>
                    </td>
                    <td class="devistableTd">
                        <?php echo $row->totalgeneral; ?>
                    </td>
                    <td><a href="devis-details/<?php echo'?id=' . $row->id; ?>" target="_blank">details</a></td>
					<td><a href="devis-pdf/<?php echo'?id=' . $row->id; ?>" target="_blank">pdf</a></td>
					<td><a href="devis-update/<?php echo'?id=' . $row->id; ?>">modifier</a></td>
					<td><a href="devis-delete/<?php echo'?id=' . $row->id; ?>" onclick="return confirm(' Vous voulez supprimer?');">supprimer</a></td>
					<td><a href="devis-envoi-admin/<?php echo'?id=' . $row->id; ?>" target="_blank">envoi admin</a></td>
					<td><a href="devis-transform-en-bc/<?php echo'?id=' . $row->id; ?>">transformer en BC</a></td>
                </tr>
                <?php } ?>
        </table>
</main>

</div>
<?php 

get_footer();