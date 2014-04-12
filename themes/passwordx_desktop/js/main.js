$(function(){

    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("active");
    });

	/* Manipulate the nav to dropdown */
	$("#sidebar-wrapper").on( "click", ".nav-dropdown > a", function(){
			$(this).siblings().toggle();
			
			$(this).children(".glyphicon").toggleClass("glyphicon-minus");
			$(this).children(".glyphicon").toggleClass("glyphicon-plus");			
	});
	
	/* Expand/contract all */
	$('body').on('click','.expandall',function() {
	 $('.sidebar-nav ul').show();
	 $('#sidebar-wrapper').find(".glyphicon").addClass("glyphicon-minus");
	 $('#sidebar-wrapper').find(".glyphicon").removeClass("glyphicon-plus");	 
	 $(this).toggleClass('expandall');
	 $(this).toggleClass('contractall');
	 $(this).find('.innertext').text('Contract all');
	 $(this).find('.glyphicon').removeClass('glyphicon-collapse-down');
	 $(this).find('.glyphicon').addClass('glyphicon-collapse-up');
	});
	$('body').on('click','.contractall',function() {
	 $('.sidebar-nav ul').hide();
	 $('#sidebar-wrapper .glyphicon').removeClass("glyphicon-minus");
	 $('#sidebar-wrapper .glyphicon').addClass("glyphicon-plus");
	 $(this).toggleClass('expandall');
	 $(this).toggleClass('contractall');
	 $(this).find('.innertext').text('Expand all');
	 $(this).find('.glyphicon').addClass('glyphicon-collapse-down');
	 $(this).find('.glyphicon').removeClass('glyphicon-collapse-up');
	});	
	
	//$("#search_input").keyup(function(){ alert("in");
	$('body').on('keyup', '#search_input', function(){
		var search_val = $(this).val().toLowerCase();
		
		$(".sidebar-nav ").find("ul.nav-dropdown").hide();
		$(".sidebar-nav li a").removeClass( "selectedSearch" );
		$(".sidebar-nav li a").find(".sign-icon").removeClass("glyphicon-minus");
		$(".sidebar-nav li a").find(".sign-icon").addClass("glyphicon-plus");
		
		if( search_val.length == 0 ) {
			return;
		}
		
		$(".sidebar-nav li a").each(function(index, element){
			if( $(element).html().toLowerCase().indexOf( search_val ) != -1 ) {
				$(element).addClass( "selectedSearch" );
				
				$parent = $(element).parents("ul.nav-dropdown")
				
				$parent.siblings("a").find(".sign-icon").removeClass("glyphicon-plus");
				$parent.siblings("a").find(".sign-icon").addClass("glyphicon-minus");
				$parent.show();
		
			}
		});
		
	});
	
	//Toggle the hash over password field
	$( ".password_super_block" ).hover(
	
		function() {
			$(this).children(".password_block_hash").hide();
			$(this).children(".password_block").show();
			$(this).find(".password_textbox").select();
			//$(this).children(".password_block").children(".password_textbox").select();
		},
		function() {
			$(this).children(".password_block_hash").show();
			$(this).children(".password_block").hide();		
		}
	);
	

	//Toggle hover selection on usernames
		$( ".pass-block-username" ).hover(
	
		function() {
			$(this).select();
			$(this).parent().find(".pass-block-username").select();
//			$(this).children(".password_block").children(".password_textbox").select();
		},
		function() {
			$(this).blur();
		}
	);
	
	/* Transform url in content into clickable link */
	$(".notes, .block-title").each(function(index, element){
		
		//console.debug($(element).html());
		if( $(element).html() ) {
		
			old_regex = /((https?\:\/\/)|(www\.))(\S+)(\w{2,4})(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/gi;
			regex = /(https?:\/\/[^\s]+)/g;
		
			linkified = $(element).html().replace(
				regex,
				function(url){
				
					//work around for new breaklines that screw up links
					nl = "";
					if( url.substring(url.length-4) == "<br>" ) {
						url = url.substring(0,url.length-4);
						nl = "<br>";
					}
					
					var full_url = url;
					if (!full_url.match('^https?:\/\/')) {
						full_url = 'http://' + full_url;
					}
					
					return '<a href="' + full_url + '">' + url + '</a>' + nl;
				}
			);
			
			$(element).html( linkified );
			
		}
		
	});	
	
	//Modal for adding new item to structure
	//$(".add-item").click(function(){
	$("#sidebar-wrapper").on( "click", ".add-item", function(){
		
		//Clean the garbage
		$("#new-name").val("");
		$(".select-picker").val("secret");
		
		//Deplot the modal
		$("#add-modal").modal();
		
		//And remember here it as deployed from
		$(".add-item").removeClass('selected-add');
		$(this).addClass('selected-add');
	});
	
	/* Focus field after modal shown */
	$("#add-modal").on('shown.bs.modal', function (e) {
	 $("#new-name").focus();
	});
	
	/* bind the enter key to the input field for autosubmit*/
	$("#new-name").keyup(function(event){
		if( event.keyCode == 13 ) {
			$("#save-modal-changes").click();
			event.preventDefault();
			return false;
		}
	});
	
	/* submit and create the new item */
	$("#save-modal-changes").click(function(){
		
		$("#new-name").parent().removeClass("has-error");
	
		var name = $("#new-name").val();
		
		if (!name) {
		 $("#new-name").parent().addClass("has-error");
		 $("#new-name").focus();
		 return false;
		}
		
		var cat = $(".select-picker").val();
		var parent_node = $('.selected-add').data('parentCid');
		
		$.ajax({
            url: '/ajax/addnewitem/' + name + '/' + cat + '/' + parent_node,
            dataType: 'json',
			type : 'GET'
        }).done(function( data ){
			if( data.status === "OK" ) {
				
				if(cat == "secret") { //redirect to the new page if the page required is a passpack
					
					window.location = "/index.php?cID=" + data.new_cID;
				
				} else { //manipulate the dom and do some crazy voodoo
				
					//Clone an tree struct
					$clone = $("#li-matrix").clone();
					$clone.find(".clone_text").html( name );
					$clone.find(".add-item").data('parentCid', data.new_cID );
					
					//slap it to the tree
					$clone.insertBefore( ".selected-add" );
					
					
					//and hide the modal
					$("#add-modal").modal('hide');
				}
				
			} else {
				//alert that something bad happend
				alert("The server experienced an internal error while processing your request.");
			}
        });
	});
});