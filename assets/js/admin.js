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
			
			$departmentSelect.html('<option value="0">' + (dreaunmaAdmin && dreaunmaAdmin.i18n ? dreaunmaAdmin.i18n.loading : 'Loading...') + '</option>');
			
			if (facultyId > 0 && typeof ajaxurl !== 'undefined') {
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'dreaunma_get_departments',
						faculty_id: facultyId,
						nonce: dreaunmaAdmin ? dreaunmaAdmin.nonce : ''
					},
					success: function(response) {
						if (response.success && response.data) {
							var selectText = dreaunmaAdmin && dreaunmaAdmin.i18n ? dreaunmaAdmin.i18n.selectDepartment : 'Select Department';
							var options = '<option value="0">' + selectText + '</option>';
							$.each(response.data, function(index, dept) {
								options += '<option value="' + dept.id + '">' + dept.department_code + ' - ' + dept.department_name + '</option>';
							});
							$departmentSelect.html(options);
						} else {
							var selectText = dreaunmaAdmin && dreaunmaAdmin.i18n ? dreaunmaAdmin.i18n.selectDepartment : 'Select Department';
							$departmentSelect.html('<option value="0">' + selectText + '</option>');
						}
					},
					error: function() {
						var selectText = dreaunmaAdmin && dreaunmaAdmin.i18n ? dreaunmaAdmin.i18n.selectDepartment : 'Select Department';
						$departmentSelect.html('<option value="0">' + selectText + '</option>');
					}
				});
			} else {
				var selectText = dreaunmaAdmin && dreaunmaAdmin.i18n ? dreaunmaAdmin.i18n.selectDepartment : 'Select Department';
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
		
		// Initialize DataTables for tables with export options
		if (typeof $.fn.DataTable !== 'undefined') {
			$('.wp-list-table').each(function() {
				var $table = $(this);
				
				// Skip if already a DataTable
				if ($.fn.DataTable.isDataTable(this)) {
					return;
				}
				
				// Only initialize if the table has data rows
				var $tbodyRows = $table.find('tbody tr');
				var hasData = false;
				
				if ($tbodyRows.length > 0) {
					// Check if it's just a "No students found" row (single td with colspan)
					var $firstRowTds = $tbodyRows.first().find('td');
					if (!($tbodyRows.length === 1 && $firstRowTds.length === 1 && $firstRowTds.attr('colspan'))) {
						hasData = true;
					}
				}
				
				if (hasData && !$table.find('tbody tr td.dataTables_empty').length) {
					// Check if table has more than 1 column to avoid initializing empty/message tables
					if ($table.find('thead th, thead td').length > 1) {
						$table.DataTable({
							dom: 'Bfrtip',
							buttons: [
								{
									extend: 'csv',
									text: 'Export CSV',
									className: 'button button-secondary',
									exportOptions: {
										// Exclude columns that typically contain action buttons
										columns: ':not(:last-child):not(.column-actions):not(.no-export)'
									}
								},
								{
									extend: 'pdf',
									text: 'Export PDF',
									className: 'button button-secondary',
									orientation: 'landscape',
									exportOptions: {
										// Exclude columns that typically contain action buttons
										columns: ':not(:last-child):not(.column-actions):not(.no-export)'
									}
								}
							],
							pageLength: 25,
							order: [], // Let WordPress default ordering stand
							language: {
								emptyTable: typeof dreaunmaAdmin !== 'undefined' && dreaunmaAdmin.i18n && dreaunmaAdmin.i18n.noData ? dreaunmaAdmin.i18n.noData : 'No data available in table'
							},
							// WordPress tables often have no-sort classes or action buttons we don't want to sort
							columnDefs: [
								{ targets: 'no-sort', orderable: false }
							]
						});
					}
				}
			});
		}
		
		console.log('Dream University Management admin scripts loaded');
	});
	
})(jQuery);

