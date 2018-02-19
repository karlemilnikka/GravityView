<?php
/**
 * The entry loop for the list output.
 *
 * @global \GV\Template_Context $gravityview
 */

/**
 * @action `gravityview_list_body_before` Tap in before the entry loop has been displayed
 * @since 2.0 Updated second parameter to pass \GV\Template_Context instead of \GravityView_View
 * @param \GV\Template_Context $gravityview Current $gravityview state
 */
do_action( 'gravityview_list_body_before', $gravityview );

// There are no entries.
if ( ! $gravityview->entries->count() ) {
	?>
	<div class="gv-list-view gv-no-results">
		<div class="gv-list-view-title">
			<h3><?php echo gv_no_results(); ?></h3>
		</div>
	</div>
	<?php
} else {
	// There are entries. Loop through them.
	foreach ( $gravityview->entries->all() as $entry ) {

		$entry_slug = GravityView_API::get_entry_slug( $entry->ID, $entry->as_entry() );

		/**
         * @var bool $has_title
         * @var bool $has_subtitle
         * @var \GV\Field_Collection $title
         * @var \GV\Field_Collection $subtitle
         */
		extract( $gravityview->template->extract_zone_vars( array( 'title', 'subtitle' ) ) );

		/**
		 * @filter `gravityview_entry_class` Modify the class applied to the entry row
         * @since 2.0 Updated third parameter to pass \GV\Template_Context instead of \GravityView_View
		 * @param string $entry_class Existing class. Default: `gv-list-view`
		 * @param array $entry Current entry being displayed
		 * @param \GV\Template_Context $gravityview Current $gravityview state
		 */
		$entry_class = apply_filters( 'gravityview_entry_class', 'gv-list-view', $entry->as_entry(), $gravityview );
	?>
        <div id="gv_list_<?php echo esc_attr( $entry_slug ); ?>" class="<?php echo gravityview_sanitize_html_class( $entry_class ); ?>">

		<?php

		/**
		 * @action `gravityview_entry_before` Tap in before the the entry is displayed, inside the entry container
		 * @param array $entry Gravity Forms Entry array
		 * @param \GV\Template_Context $gravityview Current $gravityview state
		 */
		do_action( 'gravityview_entry_before', $entry, $gravityview );

        if ( $has_title || $has_subtitle ) {

	        /**
	         * @action `gravityview_entry_title_before` Tap in before the the entry title is displayed
             * @since 2.0 Updated second parameter to pass \GV\Template_Context instead of \GravityView_View
	         * @param array $entry Gravity Forms Entry array
	         * @param \GV\Template_Context $gravityview Current $gravityview state
	         */
	        do_action( 'gravityview_entry_title_before', $entry, $gravityview );

            ?>

			<div class="gv-list-view-title">
				<?php
					$did_main = 0;
					foreach ( $title->all() as $i => $field ) {
						// The first field in the title zone is the main
						if ( $did_main == 0 ) {
							$extras = array();
							$wrap = array( 'h3' => $gravityview->template->the_field_attributes( $field, array( 'id' => '' ) ) );
						} else {
							$wrap = array( 'div' => $gravityview->template->the_field_attributes( $field ) );
							$extras = array( 'wpautop' => true );
						}

						if ( $output = $gravityview->template->the_field( $field, $entry, $extras ) ) {
							$did_main = 1;
							echo $gravityview->template->wrap( $output, $wrap );
						}
					}

					if ( $has_subtitle ) {
						?><div class="gv-list-view-subtitle"><?php
							$did_main = 0;
							foreach ( $subtitle->all() as $i => $field ) {
								// The first field in the subtitle zone is the main
								if ( $did_main == 0 ) {
									$wrap = array( 'h4' => $gravityview->template->the_field_attributes( $field ) );
								} else {
									$wrap = array( 'p' => $gravityview->template->the_field_attributes( $field ) );
								}

								if ( $output = $gravityview->template->the_field( $field, $entry, $wrap, $extras ) ) {
									$did_main = 1;
									echo $gravityview->template->wrap( $output, $wrap );
								}
							}
						?></div><?php
					}
				?>
			</div>
		<?php

            /**
             * @action `gravityview_entry_title_after` Tap in after the title block
             * @since 2.0 Updated second parameter to pass \GV\Template_Context instead of \GravityView_View
             * @param array $entry Gravity Forms Entry array
             * @param \GV\Template_Context $gravityview Current $gravityview state
             */
            do_action( 'gravityview_entry_title_after', $entry, $gravityview );

        }

		/**
		 * @var bool $has_image
		 * @var bool $has_description
         * @var bool $has_content_attributes
		 * @var \GV\Field_Collection $image
		 * @var \GV\Field_Collection $description
         * @var \GV\Field_Collection $attributes
		 */
		extract( $gravityview->template->extract_zone_vars( array( 'image', 'description', 'content-attributes' ) ) );

		$has_content_before_action = has_action( 'gravityview_entry_content_before' );
		$has_content_after_action = has_action( 'gravityview_entry_content_after' );

		if ( $has_image || $has_description || $has_content_attributes || $has_content_before_action || $has_content_after_action ) {
			?>
            <div class="gv-grid gv-list-view-content">

				<?php

                    /**
                     * @action `gravityview_entry_content_before` Tap in inside the View Content wrapper <div>
                     * @since 2.0 Updated second parameter to pass \GV\Template_Context instead of \GravityView_View
                     * @param array $entry Gravity Forms Entry array
                     * @param \GV\Template_Context $gravityview Current $gravityview state
                     */
                    do_action( 'gravityview_entry_content_before', $entry, $gravityview );

					if ( $has_image ) {
						?><div class="gv-grid-col-1-3 gv-list-view-content-image"><?php
						foreach ( $image->all() as $i => $field ) {
							if ( $output = $gravityview->template->the_field( $field, $entry ) ) {
								echo $gravityview->template->wrap( $output, array( 'div' => $gravityview->template->the_field_attributes( $field ) ) );
							}
						}
						?></div><?php
					}

					if ( $has_description ) {
						?><div class="gv-grid-col-2-3 gv-list-view-content-description"><?php
						$extras = array( 'label_tag' => 'h4', 'wpautop' => true );
						foreach ( $description->all() as $i => $field ) {
							if ( $output = $gravityview->template->the_field( $field, $entry, $extras ) ) {
								echo $gravityview->template->wrap( $output, array( 'div' => $gravityview->template->the_field_attributes( $field ) ) );
							}
						}
						?></div><?php
					}

					if ( $has_content_attributes ) {
						?><div class="gv-grid-col-3-3 gv-list-view-content-attributes"><?php
						$extras = array( 'label_tag' => 'h4', 'wpautop' => true );
						foreach ( $attributes->all() as $i => $field ) {
							if ( $output = $gravityview->template->the_field( $field, $entry, $extras ) ) {
								echo $gravityview->template->wrap( $output, array( 'div' => $gravityview->template->the_field_attributes( $field ) ) );
							}
						}
						?></div><?php
					}

                    /**
                     * @action `gravityview_entry_content_after` Tap in at the end of the View Content wrapper <div>
                     * @since 2.0 Updated second parameter to pass \GV\Template_Context instead of \GravityView_View
                     * @param array $entry Gravity Forms Entry array
                     * @param \GV\Template_Context $gravityview Current $gravityview state
                     */
                    do_action( 'gravityview_entry_content_after', $entry, $gravityview );
			?>

            </div>

			<?php
		}

		/**
		 * @var bool $has_footer_left
		 * @var bool $has_footer_right
		 * @var \GV\Field_Collection $footer_left
		 * @var \GV\Field_Collection $footer_right
		 */
		extract( $gravityview->template->extract_zone_vars( array( 'footer-left', 'footer-right' ) ) );

		// Is the footer configured?
		if ( $has_footer_left || $has_footer_right ) {

			/**
			 * @action `gravityview_entry_footer_before` Tap in before the footer wrapper
             * @since 2.0 Updated second parameter to pass \GV\Template_Context instead of \GravityView_View
			 * @param array $entry Gravity Forms Entry array
			 * @param \GV\Template_Context $gravityview Current $gravityview state
			 */
			do_action( 'gravityview_entry_footer_before', $entry, $gravityview );
			?>

			<div class="gv-grid gv-list-view-footer">
				<div class="gv-grid-col-1-2 gv-left">
					<?php
						foreach ( $footer_left->all() as $i => $field ) {
							if ( $output = $gravityview->template->the_field( $field, $entry ) ) {
								echo $gravityview->template->wrap( $output, array( 'div' => $gravityview->template->the_field_attributes( $field ) ) );
							}
						}
					?>
				</div>

				<div class="gv-grid-col-1-2 gv-right">
					<?php
						foreach ( $footer_right->all() as $i => $field ) {
							if ( $output = $gravityview->template->the_field( $field, $entry ) ) {
								echo $gravityview->template->wrap( $output, array( 'div' => $gravityview->template->the_field_attributes( $field ) ) );
							}
						}
					?>
				</div>
			</div>

			<?php

			/**
			 * @action `gravityview_entry_footer_after` Tap in after the footer wrapper
             * @since 2.0 Updated second parameter to pass \GV\Template_Context instead of \GravityView_View
			 * @param array $entry Gravity Forms Entry array
			 * @param \GV\Template_Context $gravityview Current $gravityview state
			 */
			do_action( 'gravityview_entry_footer_after', $entry, $gravityview );

		} // End if footer is configured

		/**
		 * @action `gravityview_entry_after` Tap in after the entry has been displayed, but before the container is closed
		 * @param array $entry Gravity Forms Entry array
		 * @param \GV\Template_Context $gravityview Current $gravityview state
		 */
		do_action( 'gravityview_entry_after', $entry, $gravityview );

		?>

		</div>

	<?php }
}

/**
 * @action `gravityview_list_body_after` Tap in after the entry loop has been displayed
 * @since 2.0 Updated second parameter to pass \GV\Template_Context instead of \GravityView_View
 * @param \GV\Template_Context $gravityview Current $gravityview state
 */
do_action( 'gravityview_list_body_after', $gravityview );
