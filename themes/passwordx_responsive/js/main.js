
	/**
	 * Returns a password that isn't too sucky to type out, but is relatively secure. Hope some boffins will work on this.
	 * Is in the global scope so any custom block can reuse this. Idea: Make this customizable from dash
	 */
	function sanePassword() {
		var nato = ["alfa","bravo","charlie","delta","echo","foxtrot","golf","hotel","india","juliett","kilo","lima","mike","november","oscar","papa","romeo","sierra","tango","uniform","victor","whiskey","xray","yankee","zulu","three","four","five","seven","eight","nine","zero"];
	
		nato.sort( function() { return 0.5 - Math.random() } );
	
		return nato[0] + nato[(Math.floor(Math.random() * nato.length))+1] + Math.floor((Math.random()*1000)+1000);
	}


$(function(){

    $("#menu-toggle").click(function(e) {
		e.stopPropagation();
        e.preventDefault();
		
		setTimeout( function() {
		 if ($("#wrapper").hasClass("active")) {
		  $("#wrapper").removeClass("active");
		 } else {		
			$("#wrapper").addClass("active");
		 }
		}, 25 );
        
    });
	
	$("#page-content-wrapper").click(function(e) {
	 if ($("#wrapper").hasClass("active")) {
	  $("#wrapper").removeClass("active");
	 }
	});

	/* Manipulate the nav to dropdown */
	$("#sidebar-wrapper").on( "click", ".container-toggle", function(){
			$(this).parent().next().toggle();
			
			$(this).toggleClass("glyphicon-minus");
			$(this).toggleClass("glyphicon-plus");			
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
	
	//Attach legacy C5 modal to our move/advanced link
	  $("#sidebar_move_advanced").dialog();

	
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
		
			//old_regex = /((https?\:\/\/)|(www\.))(\S+)(\w{2,4})(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/gi;
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
	$("body").on( "click", ".add-item", function(){
		
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
					
					window.location = "/index.php?cID=" + data.new_cID;
				
			} else {
				//alert that something bad happend
				alert("The server experienced an internal error while processing your request.");
			}
        });
	});
	
	
	//Modal for deleting item from page structure
	$("body").on( "click", ".delete-item", function(){
		
		//Clean old confirmation
		$("#confirm_delete").val("");
		
		var cID = $(this).data("cid");
		
		//Set data in modal
		$('#delete-cid').val(cID);		
		
		//Deploy the modal
		$("#delete-modal").modal();
		
		//Set display name
		var name = $(this).data("name");
		$('#page-name-delete').html(name);
		
		$(".delete-item").removeClass('selected-delete');
		$(this).addClass('selected-delete');
		
	});	
	
	/* Handle button click in modal */
	$('#delete_item').click(function() {
	
	 if ($("#confirm_delete").val().toLowerCase() !== "delete") {
	  alert("You must type the word DELETE to confirm deletion.");
	  return false;
	 }
	 
	 //Something to ajax deletion
	 var cID = $('#delete-cid').val();
	
		$.ajax({
            url: '/ajax/deleteitem/' + cID,
            dataType: 'json',
			type : 'GET'
        }).done(function( data ){
			if( data.status === "DELETED" ) {
				
				if ($('.selected-delete').parent().next().is("ul.nav-dropdown")) {
				 $('.selected-delete').parent().next().fadeOut();
				}
				$('.selected-delete').parent().fadeOut();
				
				$("#delete-modal").modal("hide");
				
				
			} else {
				//alert that something bad happend
				alert("The server experienced an internal error while processing your request.");
			}
        });	 
	 
	});
	
	/* Focus field after modal shown */
	$("#delete-modal").on('shown.bs.modal', function (e) {
	 $("#confirm_delete").focus();
	});	
	
	/* bind the enter key to the input field for autosubmit*/
	$("#confirm_delete").keyup(function(event){
		if( event.keyCode == 13 ) {
			$("#delete_item").click();
			event.preventDefault();
			return false;
		}
	});	
	
	//Modal for renaming items
	$("body").on( "click", ".rename-item", function(){
		
		var name = $(this).data("name");
		var cID = $(this).data("cid");
		
		$("#rename-name").parent().removeClass("has-error");
		
		//Set name
		$("#rename-name").val(name);
		
		//Set data in modal
		$('#rename-cid').val(cID);
		
		//Deploy the modal
		$("#rename-modal").modal();
		
	});	
	
		/* Focus field after modal shown */
	$("#rename-modal").on('shown.bs.modal', function (e) {
	 $("#rename-name").focus().select();
	});	
	
	/* Handle button click in modal */
	$('#rename_item').click(function() {
	
	 var newname = $("#rename-name").val();
	 
	 if (!newname) {
	  $("#rename-name").parent().addClass("has-error");
	  return false;
	 }
	 
	 var cID = $('#rename-cid').val();
	
		$.ajax({
            url: '/ajax/renameitem/' + newname + '/' + cID,
            dataType: 'json',
			type : 'GET'
        }).done(function( data ){
			if( data.status === "RENAMED" ) {
					
					//Reload page
					window.location = "/index.php?cID=" + data.cID;
				
			} else {
				//alert that something bad happend
				alert("The server experienced an internal error while processing your request.");
			}
        });
		
	});
	
	/* bind the enter key to the input field for autosubmit*/
	$("#rename-name").keyup(function(event){
		if( event.keyCode == 13 ) {
			$("#rename_item").click();
			event.preventDefault();
			return false;
		}
	});
	
	/* Permanent dismissal of the easteregg */
	$("#easter-egg-dismissal-btn").click(function(){
		$.get( "/tools/easteregg_dismissal", function( xhr ) {
			 //I dont think we should care about the response
			//if it was ok - great, if not - meh
		});
	});
});