$ = jQuery;
$(document).ready(function() {
	
$('#datatableinbox').dataTable({
"processing": false,
"serverSide": false,
"pageLength": 5
} );
$('#datatableoutbox').dataTable({
"processing": false,
"serverSide": false,
"pageLength": 5
} );

  //DEVIS ET COMMANDE SCRIPT -------------ADD PRODUCT
  

  //var i=1;
  var i = $('table#tab_logic tr:last').index();
    $("#add_row").click(function(){
		b=i-1;
      	$('#addr'+i).html($('#addr'+b).html()).find('td:first-child').html(i+1);
      	$('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
      	i++; 
		//calc();
  	});
    $("#delete_row").click(function(){
    	if(i>1){
		$("#addr"+(i-1)).html('');
		i--;
		}
		calc();
	});
	
	$('#tab_logic tbody').on('change',function(){
		calc();
	});
	/*$('#tax').on('change',function(){
		calc_total();
	});*/

	
  //DEVIS ET COMMANDE SCRIPT -------------ADD FRAIS ET TAXES
  var j = $('table#TFtab_logic tr:last').index();
    $("#TFadd_row").click(function(){
		c=j-1;
      	$('#TFaddr'+j).html($('#TFaddr'+c).html()).find('td:first-child').html(j+1);
      	$('#TFtab_logic').append('<tr id="TFaddr'+(j+1)+'"></tr>');
      	j++; 
  	});
    $("#TFdelete_row").click(function(){
    	if(j>1){
		$("#TFaddr"+(j-1)).html('');
		j--;
		}
		calcFT();
	});
	
	$('#TFtab_logic tbody').on('change',function(){
		calcFT();
	});
	$('#devise').on('change',function(){
		calc();
	});
	
	
	//DEVIS ET COMMANDE SCRIPT -------------AJAX CLIENT
	$("#client option").click(function () {
   
	   var idclient=$(this).attr("value");
	   var nicename=$(this).attr("nicename");
	   var numerodevis = (nicename.substr(0, 4)+'-'+Date.now().toString(36) +'-'+ Math.random().toString(36).substr(2, 5)).toUpperCase();
       $('#numdevis').val(numerodevis);
             var str = '&idclient=' + idclient + '&action=info_client_ajax';
			 
        $.ajax({
            type: "POST",
            dataType: "html",
            url: the_ajax_script.ajaxurl,
            data: str,
			beforeSend: function(){
              $('.ajax-loader').css("visibility", "visible");
            },
            complete: function(){
            $('.ajax-loader').css("visibility", "hidden");
            },
            success: function (data) {
              
				$('#infoclient').html(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $loader.html(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
  
        });
 
    });	
	
	//DEVIS ET COMMANDE SCRIPT -------------AJAX PRODUCT INFO
	/*$(".refproduit").change(function () {
   
	   var idproduit=$(this).val();
             var str = '&idproduit=' + idproduit + '&action=info_produit_ajax';
        $.ajax({
            type: "POST",
            dataType: "html",
            url: the_ajax_script.ajaxurl,
            data: str,
            success: function (data) {
				
              alert("ajax sucess");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $loader.html(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
  
        });
 
    });	*/

} );

function calc()
{
	$('#tab_logic tbody tr').each(function(i, element) {
		var html = $(this).html();
		if(html!='')
		{
			var qty = $(this).find('.qty').val();
			var price = $(this).find('.price').val();
			var poidunitaire = $(this).find('.poidunitaire').val();
			//var designation = $(this).find('.designation').children("option:selected").text();			
			var descriptionp = $(this).find('.designation').children("option:selected").attr('descriptionp').trim();	
            //alert(descriptionp);	
			//$(this).find('.designation').val(refprod);
			$(this).find('.descriptionp').val(descriptionp);

			$(this).find('.details').html(descriptionp);

			
			$(this).find('.total').val(qty*price);
			$(this).find('.poidtotal').val(qty*poidunitaire);
				
		
			calc_total();
			
		}
    });
}

function calc_total()
{
	total=0;
	$('.total').each(function() {
        total += parseFloat($(this).val());
    });
	$('#sub_total').val(total.toFixed(2));

	calc_totalFT();
}






function calcFT()
{
	$('#TFtab_logic tbody tr').each(function(i, element) {
		var html = $(this).html();
		var taux=0;
		var montanttaxe=0;
		if(html!='')
		{
			if($(this).find('.taux').val()!='')
			taux = parseFloat($(this).find('.taux').val());
            if($(this).find('.montanttaxe').val()!='')	
				
			montanttaxe = parseFloat($(this).find('.montanttaxe').val());
			var valeur = taux/100*montanttaxe;
	
			$(this).find('.valeur').val(valeur.toFixed(2));

			
			calc_totalFT();
		}
    });
}

function calc_totalFT()
{
	totalTF=0;
	var totalgeneral=0;
	
	$('.valeur').each(function() {
		if($(this).val()!='')
        totalTF += parseFloat($(this).val());
    });
	
	$('#TFsub_total').val(totalTF.toFixed(2));
	
	if($('#sub_total').val()!='')
	totalgeneral=parseFloat($('#sub_total').val());

	$('#TFtotal_amount').val((totalgeneral+totalTF).toFixed(2));
}

//////////////------------------------AJAX INFO CLIENT

/* $('#client').click(function(e){  
alert("alert");
            /*var idclient=1;
             var str = '&idclient=' + idclient + '&action=info_client_ajax';
        $.ajax({
            type: "POST",
            dataType: "html",
            url: the_ajax_script.ajaxurl,
            data: str,
            success: function (data) {
                var $data = jQuery(data);
                alert("success");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $loader.html(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
  
        });*/
		
 //});
