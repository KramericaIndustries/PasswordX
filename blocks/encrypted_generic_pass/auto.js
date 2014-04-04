ccmValidateBlockForm = function() {

}

$(function(){
	
	/* Retuns a sane password */
	function sanePassword() {
		var nato = ["alfa","beta","charlie","delta","echo","foxtrot","golf","india","hotel","juliette","lima","mike","november","papa","oscar","romeo","sierra","tango","victor","uniform","zulu"];
	
		nato.sort( function() { return 0.5 - Math.random() } );
	
		return nato[0] + Math.floor((Math.random()*1000)+1000);
	}
	
	
	$("#sugest_pass").click(function(){
		$('#field_3_textbox_text').val( sanePassword() );	
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