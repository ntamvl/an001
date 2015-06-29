jQuery(document).ready(function($) {
	
		var nklist=new Array; 
		var oTable=null;
	
		$('.dataTable tr').click( function() {
		if ( $(this).hasClass('row_selected_nk') )
			$(this).removeClass('row_selected_nk');
		else
			$(this).addClass('row_selected_nk');
		} ); // .dataTable tr ends
	
			
		oTable = $('.dataTable').dataTable( {
				"sPaginationType": "full_numbers",
				
				"aoColumns": [ 
				    { "bSearchable": false,
				    "bVisible":    false },
					null,
					null,
					null,
					null
				] 
		} ); // oTable Ends
		
		$('.dataTable').on('click','tr',function (){
		var sData = oTable.fnGetData( this );
		
		if(sData === null)
		{
			return false;
		}
		
		
		var index= $.inArray(sData[0], nklist);
		if(index== -1)
		   {
		     nklist.push(sData[0]);
		   }
		else
		   {
		     nklist.splice(index,1);
		   }
		//alert (nklist);
		
		}); //.dataTable Ends
		
		 
		
		
		
		$('.draftThis').click(function(){
		
		
		if(!nklist.length)
		{
			$('#nk_result').html("Please Select Some Row ! Click on Row to Select !");
			 return false;
		}
		
		
		
		$( document ).ajaxStart(function() {
			$( ".draftThis" ).before( "<p class='loading' style='float:right;'><img src='"+nk_object.nk_plugin_url+"/oneclickpublish/img/icon_loading.gif'></p>" );
			$( ".draftThis" ).attr("disabled", "disabled");
		});
		 $(document).ajaxStop(function() {
			    $('.loading').remove();
			    $( ".draftThis" ).removeAttr("disabled", "disabled");
		});
		var data = {
		action: 'nk_action',
		_ajax_nonce :   nk_object.nk_author,
		id : nklist   
		};
		
		jQuery.post(nk_object.nk_ajax_url, data, function(response) {
			nklist.length = 0;		
			var anSelected = fnGetSelected( oTable );
	        if ( anSelected.length !== 0 ) {
	        	while(anSelected.length != 0)
	        	{
	        		oTable.fnDeleteRow( anSelected[0] );
	        		anSelected.splice(0,1);
	        		
	        	}
	            
	        }
	        $('#nk_result').html(response);
			
			
		});//end jQuery.post
		
		
		
		}); //end #buttonk
		
		function fnGetSelected( oTableLocal )
		{
		    return oTableLocal.$('tr.row_selected_nk');
		}
		
		$('.nav-tab-wrapper a:not(:first-child)').click(function(){
			$('.nav-tab').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active');
			
		});
		
		$('.feature-section').hide();
		$('#about_id').show();
		$('.nav-tab-wrapper a:not(:first-child)').click(function(){
			$('.feature-section').hide();
			$('#'+$(this).attr('rel')).show();
			
		});
		
		
	
});


  

 

/*$(document).ready(function(){

	$("input[type='submit']").click(function(event){
	  event.stopImmediatePropagation();
	});
});
*/