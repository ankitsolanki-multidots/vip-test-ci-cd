// Styles
import '../scss/theme-option.scss';

jQuery(document).ready(function ($) {
	//upload logo button
	$(document).on('click', '.test_ci_cd_img_upload', function (e) {
		e.preventDefault();
		const currentParent = $(this);
		const customUploader = wp
			.media({
				title: 'Select Image',
				button: {
					text: 'Use This Image',
				},
				multiple: false, // Set this to true to allow multiple files to be selected
			})
			.on('select', function () {
				const attachment = customUploader
					.state()
					.get('selection')
					.first()
					.toJSON();
				currentParent
					.siblings('.test_ci_cd_img')
					.attr('src', attachment.url);
				currentParent.siblings('.test_ci_cd_img').attr('width', '250');
				currentParent.siblings('.test_ci_cd_img').attr('height', '140');
				currentParent.siblings('.test_ci_cd_img_url').val(attachment.url);
			})
			.open();
	});

	//remove logo button
	$(document).on('click', '.test_ci_cd_img_remove', function (e) {
		e.preventDefault();
		const currentParent = $(this);
		currentParent.siblings('.test_ci_cd_img').removeAttr('src');
		currentParent.siblings('.test_ci_cd_img').removeAttr('width');
		currentParent.siblings('.test_ci_cd_img').removeAttr('height');
		currentParent.siblings('.test_ci_cd_img_url').removeAttr('value');
	});

	//color picker custom js.
	$('[class="color-picker"]').wpColorPicker({
		hide: false,
	});
});
