ccmValidateBlockForm = function() {
	
	if ($('#field_1_textbox_text').val() == '') {
		ccm_addError('Missing required text: T1');
	}

	if ($('#field_2_textbox_text').val() == '') {
		ccm_addError('Missing required text: Test 2');
	}

	if ($('#field_3_textbox_text').val() == '') {
		ccm_addError('Missing required text: teeest 3');
	}


	return false;
}
