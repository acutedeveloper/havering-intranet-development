<?php
/**
 * Events Pro List Widget Template
 * This is the template for the output of the events list widget.
 * All the items are turned on and off through the widget admin.
 * There is currently no default styling, which is highly needed.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/pro/widgets/list-widget.php
 *
 * When the template is loaded, the following vars are set:
 *
 * @var string $start
 * @var string $end
 * @var string $venue
 * @var string $address
 * @var string $city
 * @var string $state
 * @var string $province
 * @var string $zip
 * @var string $country
 * @var string $phone
 * @var string $cost
 * @var array  $instance
 *
 * @package TribeEventsCalendarPro
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Retrieves the posts used in the List Widget loop.
$posts = tribe_get_list_widget_events();

// The URL for this widget's "View More" link.
$link_to_all = tribe_events_get_list_widget_view_all_link( $instance );

// Check if any posts were found.
if ( isset( $posts ) && $posts ) : ?>
	<div class="b-container <?php tribe_events_event_classes() ?>">

<?php

	foreach ( $posts as $post ) :
		setup_postdata( $post );

		do_action( 'tribe_events_widget_list_inside_before_loop' ); ?>

		<!-- Event  -->
		<?php

		$mini_cal_event_atts = tribe_events_get_widget_event_atts();

		$event_terms = wp_get_post_terms( $post->ID, 'tribe_events_cat' );

		$postDate = tribe_events_get_widget_event_post_date();

		$organizer_ids = tribe_get_organizer_ids();
		$multiple_organizers = count( $organizer_ids ) > 1;
		?>
		<!-- THIS IS THE BLOCK -->
		<div class="block block-calendar">
			<div class="block-date gradient-<?php echo $event_terms[0]->slug ?>">
				<div class="day"><?php echo apply_filters( 'tribe-mini_helper_tribe_events_ajax_list_dayname', date_i18n( 'D', $postDate ), $postDate, $mini_cal_event_atts['class'] ); ?></div>
				<div class="date"><?php echo apply_filters( 'tribe-mini_helper_tribe_events_ajax_list_daynumber', date_i18n( 'd', $postDate ), $postDate, $mini_cal_event_atts['class'] ); ?></div>
			</div>
			<?php do_action( 'tribe_events_list_widget_before_the_event_title' ); ?>

			<h2 class="text-<?php echo $event_terms[0]->slug ?>"><a href="<?php echo esc_url( tribe_get_event_link() ); ?>"><?php the_title(); ?></a></h2>

			<?php do_action( 'tribe_events_list_widget_after_the_event_title' ); ?>

			<?php do_action( 'tribe_events_list_widget_before_the_meta' ) ?>
			<p><?php echo tribe_get_start_date( null, true, 'j F Y - g:i a') . (tribe_get_end_time ( null, 'g:i a') == '' ? '' : ' to '.tribe_get_end_time ( null, 'g:i a')); ?></p>
			<a href="<?php echo esc_url( tribe_get_event_link() ); ?>">Read more <i class="fa fa-chevron-circle-right"></i></a>
		</div>
		<!-- THIS IS THE BLOCK -->

				<?php do_action( 'tribe_events_list_widget_after_the_meta' ) ?>

		<?php do_action( 'tribe_events_widget_list_inside_after_loop' ) ?>

	<?php endforeach ?>
</div>

<?php
// No Events were found.
else:
?>
	<p><?php printf( __( 'There are no upcoming %s at this time.', 'tribe-events-calendar-pro' ), strtolower( tribe_get_event_label_plural() ) ); ?></p>
<?php
endif;

// Cleanup. Do not remove this.
wp_reset_postdata();
