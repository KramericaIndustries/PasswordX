/**
 * JS for Deisgner block
 */
$(document).ready(function() {
	
	//make the added field movable
	$("#designer-content-fields").sortable({ handle: ".icon-move" })
	
	//autocomplete the handle name
	$("#name").keyup(function(){
		
		handle_name = $(this).val();
		
		//to lowercase
		handle_name = handle_name.toLowerCase();
		
		//remove special chars and replace spaces with _
		handle_name = handle_name.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '_');
		
		
		$("#handle").val( "encrypted_"+handle_name );
	});
	
	update_addfield_links();
	
	//add new field
	$('a.add-field-type').live('click', function(){
		
		var type = $(this).attr('data-type');
		
		if (type.length > 0) {
			var data = {
				'id': new_field_row_id(),
				'type': type,
				'label': FIELDTYPE_LABELS[type]
			};
			$("#field-template").tmpl(data).appendTo("#designer-content-fields").effect("highlight", {}, 500);
			update_addfield_links();
		}
		
		return false;
	});
	
	//we will do a good old fashion promt for confirmation
	$('.designer-content-field-delete').live('click', function(){
		
		var confirm_action = confirm("Are you sure you want to delete this field?");

		if (confirm_action) {
			
			var id = $(this).attr('data-id');
			
			$('.designer-content-field[data-id='+id+']').slideUp('fast', function() {
				$(this).remove();
				update_addfield_links();
			});
			
		}
	
		return false;
	});
	
	
	//submit form
	$('#designer-content-form').submit(function() {
		
		$('#designer-content-submit').hide();
		$('#designer-content-submit-loading').show();
		
		var valid = validate_form(); //function will alert user to problems
		
		if( !valid ) {
			$('#designer-content-submit-loading').hide();
			$('#designer-content-submit').show();
		}
		
		return valid;
	});
	
});

function update_addfield_links() {
	$("#add-field-types").html($("#add-field-types-template").tmpl());
}

function new_field_row_id() {
	var max_id = 0;
	var cur_id = 0;
	$('.designer-content-field').each(function() {
		cur_id = parseInt($(this).attr('data-id'));
		max_id = (cur_id > max_id) ? cur_id : max_id;
	});
	return max_id + 1;
}

function validate_form() {
	//Name and handle are required
	//Handle must not already exist in the system (anywhere -- package, block, etc.)
	//Handle can only contain lowercase letters and underscores
	//must have at least 1 field
	//check that a label is provided for each field
	
	var errors = [];
	
	var name = $('#name').val();
	var handle = $('#handle').val();
	var fieldCount = $('.designer-content-field').length;
	var fieldLabels = $.map($('.designer-content-field-editorlabel'), function(element, index) { return $(element).val(); });

	if (handle.length == 0) {
		errors.push(ERROR_MESSAGES['handle_required']);
	} else if (handle.length > 32) {
	    errors.push(ERROR_MESSAGES['handle_length']);
	} else if (!/^[a-z_]+$/.test(handle)) {
		errors.push(ERROR_MESSAGES['handle_lowercase']);
	} else if (!validate_handle(handle)) {
		errors.push(ERROR_MESSAGES['handle_exists']);
	}

	if (name.length == 0) {
		errors.push(ERROR_MESSAGES['name_required']);
	}
	
	if (fieldCount < 1) {
		errors.push(ERROR_MESSAGES['fields_required']);
	}
	
	var missing_labels = false;
	$.each(fieldLabels, function(index, label) {
		if (label.length == 0) {
			missing_labels = true;
		}
	});
	
	if (missing_labels) {
		errors.push(ERROR_MESSAGES['labels_required']);
	}

	if (errors.length > 0) {
		alert(ERROR_MESSAGES['error_header'] + '\n * ' + errors.join('\n * '));
		return false;
	} else {
		return true;
	}
}

//ajax call to see if a handle is already taken
function validate_handle(handle) {
	var valid = false;
	$.ajax({
		'url': VALIDATE_HANDLE_URL,
		'method': 'get',
		'data': {'handle': handle},
		'async': false, //must be synchronous otherwise outer function returns before response is received from server
		success: function(response) {
			//dev note: call parseInt on the response because some servers are returning whitespace before/after the number
			if (parseInt(response) == 2) {
				valid = confirm(ERROR_MESSAGES['table_exists']);
			} else if (parseInt(response) == 1) {
				valid = true;
			}
		}
	});

	return valid;
}