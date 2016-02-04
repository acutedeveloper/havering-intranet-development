<?php
/**
 * Single Event Template for Widgets
 *
 * This template is used to render single events for both the calendar and advanced
 * list widgets, facilitating a common appearance for each as standard.
 *
 * You can override this template in your own theme by creating a file at
 * [your-theme]/tribe-events/pro/widgets/modules/single-event.php
 *
 * @package TribeEventsCalendarPro
 *
 */

$mini_cal_event_atts = tribe_events_get_widget_event_atts();

$postDate = tribe_events_get_widget_event_post_date();

$organizer_ids = tribe_get_organizer_ids();
$multiple_organizers = count( $organizer_ids ) > 1;
?>
<!-- THIS IS THE BLOCK -->
<div class="block block-calendar">
	<div class="block-date gradient-orange">
		<div class="day"><?php echo apply_filters( 'tribe-mini_helper_tribe_events_ajax_list_dayname', date_i18n( 'D', $postDate ), $postDate, $mini_cal_event_atts['class'] ); ?></div>
		<div class="date"><?php echo apply_filters( 'tribe-mini_helper_tribe_events_ajax_list_daynumber', date_i18n( 'd', $postDate ), $postDate, $mini_cal_event_atts['class'] ); ?></div>
	</div>
	<?php do_action( 'tribe_events_list_widget_before_the_event_title' ); ?>

	<h2 class="text-orange"><a href="<?php echo esc_url( tribe_get_event_link() ); ?>"><?php the_title(); ?></a></h2>

	<?php do_action( 'tribe_events_list_widget_after_the_event_title' ); ?>

	<?php do_action( 'tribe_events_list_widget_before_the_meta' ) ?>
	<p><?php echo tribe_events_event_schedule_details(); ?></p>
	<a href="<?php echo esc_url( tribe_get_event_link() ); ?>">Read more <i class="fa fa-chevron-circle-right"></i></a>
</div>
<!-- THIS IS THE BLOCK -->

		<?php do_action( 'tribe_events_list_widget_after_the_meta' ) ?>
