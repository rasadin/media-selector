<?php
////media selector sub menu under settings
// add_action( 'admin_menu', 'register_media_selector_settings_page' );

// function register_media_selector_settings_page() {
// 	add_submenu_page( 'options-general.php', 'Media Selector', 'Media Selector', 'manage_options', 'media-selector', 'media_selector_settings_page_callback' );
// }






////media selector Admin menu 

add_action( 'admin_menu', 'register_media_selector_settings' );

if ( ! function_exists( 'register_media_selector_settings' ) ) {
	/**
	 * Creates Menu for Plugin in the dashboard.
	 */
	function register_media_selector_settings() {
		// Create new top-level menu.
		add_menu_page( 'options-general.php', 'Media Selector', 'administrator', __FILE__, 'media_selector_settings_page_callback', 'dashicons-instagram' );
		// Call register settings function.
		//add_action( 'admin_init', 'register_media_selector_settings' );
	}
}



function media_selector_settings_page_callback() {

	// Save attachment ID
	if ( isset( $_POST['submit_image_selector'] ) && isset( $_POST['image_attachment_id'] ) ) :
		update_option( 'media_selector_attachment_id', absint( $_POST['image_attachment_id'] ) );
	endif;

	wp_enqueue_media();

	?><form method='post'>
		<div class='image-preview-wrapper'>
			<img id='image-preview' src='<?php echo wp_get_attachment_url( get_option( 'media_selector_attachment_id' ) ); ?>' height='100'>
		</div>
		<input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" />


        <input id="image_link_button" type="button" class="button" value="<?php echo wp_get_attachment_url( get_option( 'media_selector_attachment_id' ) ); ?>" />
		<input type='hidden' name='image_attachment_id' id='image_attachment_id' value='<?php echo get_option( 'media_selector_attachment_id' ); ?>'>
		<input type="submit" name="submit_image_selector" value="Save" class="button-primary">
	</form><?php

}

add_action( 'admin_footer', 'media_selector_print_scripts' );

function media_selector_print_scripts() {

	$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );

	?>
	<script type='text/javascript'>
		jQuery( document ).ready( function( $ ) {

			// Uploading files
			var file_frame;
			var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
			var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this

			jQuery('#upload_image_button').on('click', function( event ){

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					// Set the post ID to what we want
					file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					// Open frame
					file_frame.open();
					return;
				} else {
					// Set the wp.media post id so the uploader grabs the ID we want when initialised
					wp.media.model.settings.post.id = set_to_post_id;
				}

				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a image to upload',
					button: {
						text: 'Use this image',
					},
					multiple: false	// Set to true to allow multiple files to be selected
				});

				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					attachment = file_frame.state().get('selection').first().toJSON();

					// Do something with attachment.id and/or attachment.url here
					$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
					$( '#image_attachment_id' ).val( attachment.id );

					// Restore the main post ID
					wp.media.model.settings.post.id = wp_media_post_id;
				});

					// Finally, open the modal
					file_frame.open();
			});

			// Restore the main ID when the add media button is pressed
			jQuery( 'a.add_media' ).on( 'click', function() {
				wp.media.model.settings.post.id = wp_media_post_id;
			});
		});
	</script><?php

}
