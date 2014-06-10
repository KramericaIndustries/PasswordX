ccmValidateBlockForm = function() {

}

$(function(){
	
	$(".sugest_pass").click(function(){
		$('#' + $(this).data('target')).val( sanePassword() );	
		$('#' + $(this).data('target')).keyup();
		return false;
	});
	
	$(".clear_view").mousedown(function(){

		$("#miror_" + $(this).data('target')).val( $('#' + $(this).data('target')).val() );
		
		$( '#' + $(this).data('target') ).css( 'display', 'none' );
		$("#miror_" + $(this).data('target')).css( 'display', 'inline' );
		
		return false;
	});
	
	$(".clear_view").mouseup(function(){
	
		$('#' + $(this).data('target')).css( 'display', 'inline' );
		$("#miror_" + $(this).data('target')).css( 'display', 'none' );
		
		return false;
	});

	$(".clear_view").click(function(){ return false; });
	
});