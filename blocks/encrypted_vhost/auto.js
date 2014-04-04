ccmValidateBlockForm = function() {

}

$(function(){
	
	/* Retuns a sane password */
	function sanePassword() {
		var nato = ["alfa","beta","charlie","delta","echo","foxtrot","golf","india","hotel","juliette","lima","mike","november","papa","oscar","romeo","sierra","tango","victor","uniform","zulu"];
	
		nato.sort( function() { return 0.5 - Math.random() } );
	
		return nato[0] + Math.floor((Math.random()*1000)+1000);
	}
	
	$(".sugest_pass").click(function(){
		$('#' + $(this).data('target')).val( sanePassword() );	
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