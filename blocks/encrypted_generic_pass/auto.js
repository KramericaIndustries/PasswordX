ccmValidateBlockForm = function() {

}

$(function(){
	
	$("#sugest_pass").click(function(){
		$('#field_3_textbox_text').val( sanePassword() );
		$('#field_3_textbox_text').keyup();
		return false;
	});
	
	
	$("#clear_view").mousedown(function(){
		$("#pass_mirror").val( $('#field_3_textbox_text').val() );
		
		$('#field_3_textbox_text').css( 'display', 'none' );
		$("#pass_mirror").css( 'display', 'inline' );
		
		return false;
	});
	
	$("#clear_view").mouseup(function(){
	
		$('#field_3_textbox_text').css( 'display', 'inline' );
		$("#pass_mirror").css( 'display', 'none' );
		
		return false;
	});
	
	$("#clear_view").click(function(){ return false; });
	
	//return false;
});