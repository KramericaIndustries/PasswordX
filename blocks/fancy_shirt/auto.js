ccmValidateBlockForm = function() {
	
	if ($('#field_1_textbox_text').val() == '') {
		ccm_addError('Missing required text: a');
	}

	if ($('#field_3_textarea_text').val() == '') {
		ccm_addError('Missing required text: c');
	}


	return false;
}
