/**
 * Admin scripts for Dream University Management
 */

(function($) {
	'use strict';
	
	$(document).ready(function() {
		// Faculty change handler for dynamic department loading
		$(document).on('change', '#faculty_id', function() {
			var facultyId = $(this).val();
			var $departmentSelect = $('#department_id');
			
			if (! $departmentSelect.length) {
				return;
			}
			
			$departmentSelect.html('<option value="0">' + (dumAdmin && dumAdmin.i18n ? dumAdmin.i18n.loading : 'Loading...') + '</option>');
			
			if (facultyId > 0 && typeof ajaxurl !== 'undefined') {
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'dum_get_departments',
						faculty_id: facultyId,
						nonce: dumAdmin ? dumAdmin.nonce : ''
					},
					success: function(response) {
						if (response.success && response.data) {
							var selectText = dumAdmin && dumAdmin.i18n ? dumAdmin.i18n.selectDepartment : 'Select Department';
							var options = '<option value="0">' + selectText + '</option>';
							$.each(response.data, function(index, dept) {
								options += '<option value="' + dept.id + '">' + dept.department_code + ' - ' + dept.department_name + '</option>';
							});
							$departmentSelect.html(options);
						} else {
							var selectText = dumAdmin && dumAdmin.i18n ? dumAdmin.i18n.selectDepartment : 'Select Department';
							$departmentSelect.html('<option value="0">' + selectText + '</option>');
						}
					},
					error: function() {
						var selectText = dumAdmin && dumAdmin.i18n ? dumAdmin.i18n.selectDepartment : 'Select Department';
						$departmentSelect.html('<option value="0">' + selectText + '</option>');
					}
				});
			} else {
				var selectText = dumAdmin && dumAdmin.i18n ? dumAdmin.i18n.selectDepartment : 'Select Department';
				$departmentSelect.html('<option value="0">' + selectText + '</option>');
			}
		});
		
		// WordPress Media Library uploader
		var mediaUploader;
		
		// Handle image upload button click
		$(document).on('click', '.dum-upload-image-button', function(e) {
			e.preventDefault();
			
			var $button = $(this);
			var targetInput = $button.data('target');
			var previewImg = $button.data('preview');
			var $targetInput = $('#' + targetInput);
			var $previewImg = $('#' + previewImg);
			var $removeButton = $button.siblings('.dum-remove-image-button');
			
			// If the uploader object has already been created, reopen it
			if (mediaUploader) {
				mediaUploader.open();
				return;
			}
			
			// Create the media uploader
			mediaUploader = wp.media({
				title: 'Select Image',
				button: {
					text: 'Use this image'
				},
				multiple: false,
				library: {
					type: 'image'
				}
			});
			
			// When an image is selected, run a callback
			mediaUploader.on('select', function() {
				var attachment = mediaUploader.state().get('selection').first().toJSON();
				
				// Set the hidden input value
				$targetInput.val(attachment.id);
				
				// Update preview image
				$previewImg.attr('src', attachment.url).show();
				
				// Show remove button
				$removeButton.show();
			});
			
			// Open the uploader
			mediaUploader.open();
		});
		
		// Handle remove image button click
		$(document).on('click', '.dum-remove-image-button', function(e) {
			e.preventDefault();
			
			var $button = $(this);
			var targetInput = $button.data('target');
			var previewImg = $button.data('preview');
			var $targetInput = $('#' + targetInput);
			var $previewImg = $('#' + previewImg);
			
			// Clear the input
			$targetInput.val('');
			
			// Hide preview image
			$previewImg.attr('src', '').hide();
			
			// Hide remove button
			$button.hide();
		});
		
		console.log('Dream University Management admin scripts loaded');
	});
	
})(jQuery);

