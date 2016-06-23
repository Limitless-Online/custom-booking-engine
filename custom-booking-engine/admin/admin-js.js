var $ = jQuery;

$(document).ready(function(){

	$('.btn-add').click(function(){
		var new_record = "<li><input type='text' class='txtemail' value='' /><button class='btn btn-remove'>Remove</button></li>";
		$('ul.admin-list').append(new_record);
	});

	$(document).on('click', '.btn-remove', function(){ 
	    $(this).parent('li').remove();
	});	

	$('.btn-save').click(function(){
		var records = [];
		var count = 0 ;
		
		$('ul.admin-list').children('li').each(function(){
			records[count] = $(this).children('input.txtemail').val();
			count++;
		});

		jQuery.ajax({
	         type : "post",
	         dataType : "text",
	         url : customAjax.ajaxurl,
	         data : {
	         	action  : "save_custom",
	         	records : records,
	         },
	         success: function(response) {
	            if (parseInt(response)==1){
	            	alert('Successfully Saved!');
	            }else{
	            	alert('Unable to Save!');
	            }
	         }
	    });
	});

});	