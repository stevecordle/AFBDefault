<?php

/**
 * WIDGET :: Countdown
 *
 * @package     EPL
 * @subpackage  Widget/Recent_Property
 * @copyright   Copyright (c) 2014, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

use Carbon\Carbon;

class AFB_Countdown_Widget extends WP_Widget {

    function __construct() {
        parent::WP_Widget(false, $name = __('AFB Countdown', 'epl'));
    }

    function widget($args, $instance) {
        extract($args);
        $default_now = Carbon::now();
        $defaults = array(
            'countdown_to_date' => $default_now->addMonth(),
            'duration_type' => 'day'
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        
        $countdown_to_date = $instance['countdown_to_date'];
        $duration_type    = $instance['duration_type'];
        
        $now = Carbon::now();
        $countdown_to = Carbon::parse($countdown_to_date);
        $clean_countdown_date = $countdown_to->formatLocalized('%A %B %d, %Y'); 
        switch($duration_type){
            case 'hour':
                $countdown = $now->diffInHours($countdown_to);
                if($countdown > 1){
                    $countdown_text = "Hours";
                }else{
                    $countdown_text = "Hour";
                }
                break;
            case 'week':
                $countdown = $now->diffInWeeks($countdown_to);
                if($countdown > 1){
                    $countdown_text = "Weeks";
                }else{
                    $countdown_text = "Week";
                }
                break;
            case 'month':
                $countdown = $now->diffInMonths($countdown_to);
                if($countdown > 1){
                    $countdown_text = "Months";
                }else{
                    $countdown_text = "Month";
                }
                break;
            default:
                $countdown = $now->diffInDays($countdown_to);
                if($countdown > 1){
                    $countdown_text = "Days";
                }else{
                    $countdown_text = "Day";
                }
        }
        
        echo $before_widget; ?>
        <div class="col-xs-12 col-sm-9 col-sm-offset-3">
            <div class="time-left pull-left">
                <span class="countdown_time"><?php echo $countdown; ?></span>
                <span class="countdown_text"><?php echo $countdown_text; ?></span>
            </div>
            <div class="time-text pull-left">
                <span class="grand_opening">GRAND OPENING</span>
                <span class="clean_date"><?php echo $clean_countdown_date; ?></span>
            </div>
        </div>
       <?php echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $instance['countdown_to_date'] = strip_tags( $new_instance['countdown_to_date'] );
        $instance['duration_type'] = strip_tags( $new_instance['duration_type'] );

        return $instance;
    }

    function form($instance) {
        $countdown_to_date  = esc_attr( $instance['countdown_to_date'] );
        $duration_type = esc_attr( $instance['duration_type'] );
        ?>
        <p>
          <label for="title">Countdown To Date</label>
          <input class="widefat" id="<?php echo $this->get_field_id('countdown_to_date'); ?>" name="<?php echo $this->get_field_name('countdown_to_date'); ?>" type="date" value="<?php echo $countdown_to_date; ?>"/>
        </p>
        <p>
          <label for="message">Duration Type</label>
          <select  class="widefat" id="<?php echo $this->get_field_id('duration_type'); ?>" name="<?php echo $this->get_field_name('duration_type'); ?>">
              <option id="hour" value="hour" <?php echo $duration_type == 'hour'? 'selected':''; ?>>Hour</option>
              <option id="day" value="day" <?php echo $duration_type == 'day'? 'selected':''; ?>>Day</option>
              <option id="week" value="week" <?php echo $duration_type == 'week'? 'selected':''; ?>>Week</option>
              <option id="month" value="month" <?php echo $duration_type == 'month'? 'selected':''; ?>>Month</option>
          </select>
        </p>
        <?php

    }

}

add_action('widgets_init', create_function('', 'return register_widget("AFB_Countdown_Widget");'));
