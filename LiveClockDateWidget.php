<?php 
/*
Plugin Name: Live Clock Date
Plugin URI: http://appsnity.com/notification/wordpress/60-live-clock-date-widget-plugin-wordpress-that-display-a-calendar-and-real-time-clock.html
Description: Live Clock Date Widget is a kind of free plugins for wordpress-based blogs. This Plugin presents the combination between date and clock, whereas clock on this plugin runs real time.<a href="http://appsnity.com/notification/wordpress/60-live-clock-date-widget-plugin-wordpress-that-display-a-calendar-and-real-time-clock.html" target="_blank">Read More Details...</a>
License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
Version: 1.3
Author: AppsNity Project
Author URI: http://appsnity.com
*/

/*
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

add_action( 'widgets_init', 'liveclockdate_load_widget' );

function liveclockdate_load_widget() {
	register_widget( 'LiveClockDateReg_widget' );
}

add_filter( 'plugin_action_links', 'liveclockdate_widget_plugin_action_links', 10, 2 );

function liveclockdate_widget_plugin_action_links( $links, $file ) {
	if ( $file != plugin_basename( __FILE__ ))
		return $links;

	$settings_link = '<a href="' . admin_url().'widgets.php'. '">'
		. esc_html( __( 'Configure Widget', 'liveclockdate_widget' ) ) . '</a>';

	array_unshift( $links, $settings_link );

	return $links;
}

class LiveClockDateReg_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function LiveClockDateReg_widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'LiveClockDateReg_widget', 'description' => __('This Widget will show the Alexa website ranking as your choice', 'liveclockdate_widget') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'live-clock-date-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'live-clock-date-widget', __('LiveClockDate', 'liveclockdate_widget'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

	/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );

		$width 			= $instance['width'];
		$font_face 		= $instance['font_face'];
		$font_size 		= $instance['font_size'];
		$font_color 	= $instance['font_color'];
		$hoursystem 	= $instance['hoursystem'];
		$clockupdate 	= $instance['clockupdate'];
		$displaydate 	= $instance['displaydate'];
		$styledate 		= $instance['styledate'];
		$text_shadow 	= $instance['text_shadow'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		echo '<div style="display:none">Your browser is not supported for the Live Clock Timer, please visit the <a href="' . plugins_url( 'support.html', __FILE__ ) . '" target="_blank">Support Center</a> for support.</div>';

		/* Start --- Live Clock Date JS*/
		?>
		
			<script language="javascript">
				var myfont_face = "<?php echo $font_face; ?>"; 
				var myfont_size = "<?php echo $font_size; ?>";
				var myfont_color = "<?php echo $font_color; ?>";
				var myback_color = "transparent";
				var mytext_shadow = "0 1px <?php echo $text_shadow; ?>";
				var mypre_text = "The Time is:";
				var mysep_text = "On";
				var mywidth = <?php echo $width; ?>;
				var my12_hour = <?php echo $hoursystem; ?>;
				var myupdate = <?php echo $clockupdate; ?>;
				var DisplayDate = <?php echo $displaydate; ?>;
				var StyleDate = <?php echo $styledate; ?>;
				

			// Browser detect code
					var ie4=document.all
					var ns4=document.layers
					var ns6=document.getElementById&&!document.all

			//Browser Supported for Live Clock Date
					// document.getElementById('timerSupport').style.display='none';
			
			// Global varibale definitions:
				var dn = "";
				var mn = "th";
				var old = "";

			// The following arrays contain data which is used in the clock's
				var DaysOfWeek = new Array(7);
					DaysOfWeek[0] = "Sunday";
					DaysOfWeek[1] = "Monday";
					DaysOfWeek[2] = "Tuesday";
					DaysOfWeek[3] = "Wednesday";
					DaysOfWeek[4] = "Thursday";
					DaysOfWeek[5] = "Friday";
					DaysOfWeek[6] = "Saturday";

				var MonthsOfYear = new Array(12);
					MonthsOfYear[0] = "January";
					MonthsOfYear[1] = "February";
					MonthsOfYear[2] = "March";
					MonthsOfYear[3] = "April";
					MonthsOfYear[4] = "May";
					MonthsOfYear[5] = "June";
					MonthsOfYear[6] = "July";
					MonthsOfYear[7] = "August";
					MonthsOfYear[8] = "September";
					MonthsOfYear[9] = "October";
					MonthsOfYear[10] = "November";
					MonthsOfYear[11] = "December";

			// This array controls how often the clock is updated,
			// based on your selection in the configuration.
				var ClockUpdate = new Array(3);
					ClockUpdate[0] = 0;
					ClockUpdate[1] = 1000;
					ClockUpdate[2] = 60000;

			// For Version 4+ browsers, write the appropriate HTML to the
			// page for the clock, otherwise, attempt to write a static
			// date to the page.
				if (ie4||ns6) { document.write('<span id="LiveClockIE" style="width:'+mywidth+'px; background-color:'+myback_color+'"></span>'); }
				else if (document.layers) { document.write('<ilayer bgColor="'+myback_color+'" id="ClockPosNS" visibility="hide"><layer width="'+mywidth+'" id="LiveClockNS"></layer></ilayer>'); }
				else { old = "true"; show_clock(); }

			// The main part of the script:
				function show_clock() {
					if (old == "die") { return; }
				
				//show clock in NS 4
					if (ns4)
							document.ClockPosNS.visibility="show"
				// Get all our date variables:
					var Digital = new Date();
					var day = Digital.getDay();
					var mday = Digital.getDate();
					var month = Digital.getMonth();
					var hours = Digital.getHours();

					var minutes = Digital.getMinutes();
					var seconds = Digital.getSeconds();

				// Fix the "mn" variable if needed:
					if (mday == 1) { mn = "st"; }
					else if (mday == 2) { mn = "nd"; }
					else if (mday == 3) { mn = "rd"; }
					else if (mday == 21) { mn = "st"; }
					else if (mday == 22) { mn = "nd"; }
					else if (mday == 23) { mn = "rd"; }
					else if (mday == 31) { mn = "st"; }

				// Set up the hours for either 24 or 12 hour display:
					if (my12_hour) {
						dn = "AM";
						if (hours > 12) { dn = "PM"; hours = hours - 12; }
						if (hours == 0) { hours = 12; }
					} else {
						dn = "";
					}
					if (minutes <= 9) { minutes = "0"+minutes; }
					if (seconds <= 9) { seconds = "0"+seconds; }

				// This is the actual HTML of the clock. If you're going to play around
				// with this, be careful to keep all your quotations in tact. edit by RumahBelanja.com
					//Time-Date
					if (!StyleDate) {
					myclock = '';
					myclock += '<div class="liveclockdate" style="color:'+myfont_color+'; font-family:'+myfont_face+'; font-size:'+myfont_size+'pt; text-shadow:'+mytext_shadow+';" >';
					myclock += '<span class="liveclockdate-text">';
					myclock += mypre_text+' ';
					myclock += '</span>';
					myclock += '<span class="liveclockdate-time">';
					myclock += hours+':'+minutes;
					if ((myupdate < 2) || (myupdate == 0)) { myclock += ':'+seconds; }
					myclock += ' '+dn;
					myclock += '</span>';
					if (DisplayDate) {
					myclock += '<span class="liveclockdate-time">';
					myclock += ' '+mysep_text;
					myclock += ' '+DaysOfWeek[day]+', '+mday+mn+' '+MonthsOfYear[month]; 
					myclock += ' <?php echo(Date("Y")); ?></span>';}
					
					myclock += '</div>';
					}
					if (old == "true") {
						document.write(myclock);
						old = "die";
						return;
					}
					
					//Date-Time
					if (StyleDate) {
					myclock = '';
					myclock += '<div class="liveclockdate" style="color:'+myfont_color+'; font-family:'+myfont_face+'; font-size:'+myfont_size+'pt; text-shadow:'+mytext_shadow+';">';
					if (DisplayDate) { 
					myclock += '<span class="liveclockdate-time">';
					//myclock += ' '+mysep_text;
					myclock += DaysOfWeek[day]+', '+mday+mn+' '+MonthsOfYear[month]; 
					myclock += ' <?php echo(Date("Y")); ?></span>';}
					//myclock += '<span class="liveclockdate-text">';
					//myclock += ' / '+mypre_text;
					//myclock += '</span>';
					myclock += '<span class="liveclockdate-time">';
					
					if (!DisplayDate) {
					myclock += ''+hours+':'+minutes;
					}
					
					if (DisplayDate) {
					myclock += ' | '+hours+':'+minutes;
					}
					
					if ((myupdate < 2) || (myupdate == 0)) { myclock += ':'+seconds; }
					myclock += ' '+dn;
					myclock += '</span>';
					myclock += '</div>';
					}

					if (old == "true") {
						document.write(myclock);
						old = "die";
						return;
					}
				//end edit by RBO Team
				// Write the clock to the layer:
					if (ns4) {
						clockpos = document.ClockPosNS;
						liveclock = clockpos.document.LiveClockNS;
						liveclock.document.write(myclock);
						liveclock.document.close();
					} else if (ie4) {
						LiveClockIE.innerHTML = myclock;
					} else if (ns6){
						document.getElementById("LiveClockIE").innerHTML = myclock;
							}            

				if (myupdate != 0) { setTimeout("show_clock()",ClockUpdate[myupdate]); }
			}
			</script>
			<body onLoad="show_clock()">
		<?php
		/* End --- Live Clock Date JS*/
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );

		$instance['width']		= $new_instance['width'];
		$instance['font_face']	= $new_instance['font_face'];
		$instance['font_size'] 	= $new_instance['font_size'];
		$instance['font_color']	= $new_instance['font_color'];
		$instance['hoursystem']	= $new_instance['hoursystem'];
		$instance['clockupdate']= $new_instance['clockupdate'];
		$instance['displaydate']= $new_instance['displaydate'];
		$instance['styledate']	= $new_instance['styledate'];
		$instance['text_shadow']= $new_instance['text_shadow'];

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Live Clock Date', 'Live Clock Date'), 'width' => '300', 'font_face' => 'arial', 'font_size' => '10', 'font_color' => '#000000', 'hoursystem' => '1', 'clockupdate' => '1', 'displaydate' => '1', 'styledate' => '1', 'text_shadow' => '#ccc' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'hoursystem' ); ?>"><?php _e('Hours System:', 'hoursystem'); ?></label> 
			<select id="<?php echo $this->get_field_id( 'hoursystem' ); ?>" name="<?php echo $this->get_field_name( 'hoursystem' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( '1' == $instance['hoursystem'] ) echo 'selected="selected"'; ?> value="1">Half System (Per 12 Hours)</option>
				<option <?php if ( '0' == $instance['hoursystem'] ) echo 'selected="selected"'; ?> value="0">Full System (24 Hours)</option>
			</select>
		</p>
		
		
		<p>
			<label for="<?php echo $this->get_field_id( 'clockupdate' ); ?>"><?php _e('Clock Update:', 'clockupdate'); ?></label> 
			<select id="<?php echo $this->get_field_id( 'clockupdate' ); ?>" name="<?php echo $this->get_field_name( 'clockupdate' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( '2' == $instance['clockupdate'] ) echo 'selected="selected"'; ?> value="2">Every Minute</option>
				<option <?php if ( '1' == $instance['clockupdate'] ) echo 'selected="selected"'; ?> value="1">Every Second</option>
				<option <?php if ( '0' == $instance['clockupdate'] ) echo 'selected="selected"'; ?> value="0">Never</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'displaydate' ); ?>"><?php _e('Display Date:', 'displaydate'); ?></label> 
			<select id="<?php echo $this->get_field_id( 'displaydate' ); ?>" name="<?php echo $this->get_field_name( 'displaydate' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( '1' == $instance['displaydate'] ) echo 'selected="selected"'; ?>	value="1">Yes</option>
				<option <?php if ( '0' == $instance['displaydate'] ) echo 'selected="selected"'; ?> value="0">No</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'styledate' ); ?>"><?php _e('Style Date:', 'styledate'); ?></label> 
			<select id="<?php echo $this->get_field_id( 'styledate' ); ?>" name="<?php echo $this->get_field_name( 'styledate' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( '1' == $instance['styledate'] ) echo 'selected="selected"'; ?> value="1">Date-Time</option>
				<option <?php if ( '0' == $instance['styledate'] ) echo 'selected="selected"'; ?> value="0">Time-Date</option>
			</select>
		</p>
		
		<p>
			<label><?php _e('<hr><center><b><u>General Style/Design</u><b></center>'); ?></label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'font_face' ); ?>"><?php _e('Font Type:', 'font_face'); ?></label>
			<input id="<?php echo $this->get_field_id( 'font_face' ); ?>" name="<?php echo $this->get_field_name( 'font_face' ); ?>" value="<?php echo $instance['font_face']; ?>" style="width:100%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'font_size' ); ?>"><?php _e('Font Size:', 'font_size'); ?></label>
			<input id="<?php echo $this->get_field_id( 'font_size' ); ?>" name="<?php echo $this->get_field_name( 'font_size' ); ?>" value="<?php echo $instance['font_size']; ?>" style="width:100%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'font_color' ); ?>"><?php _e('Font Color:', 'font_color'); ?></label>
			<input id="<?php echo $this->get_field_id( 'font_color' ); ?>" name="<?php echo $this->get_field_name( 'font_color' ); ?>" value="<?php echo $instance['font_color']; ?>" style="width:100%;" />
		</p>	

		<p>
			<label for="<?php echo $this->get_field_id( 'text_shadow' ); ?>"><?php _e('Text Shadow Color:', 'text_shadow'); ?></label>
			<input id="<?php echo $this->get_field_id( 'text_shadow' ); ?>" name="<?php echo $this->get_field_name( 'text_shadow' ); ?>" value="<?php echo $instance['text_shadow']; ?>" style="width:100%;" />
		</p>	

		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e('Width (only need to IE):', 'width'); ?></label>
			<input id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>" style="width:100%;" />
		</p>	
	<?php
	}
}

?>