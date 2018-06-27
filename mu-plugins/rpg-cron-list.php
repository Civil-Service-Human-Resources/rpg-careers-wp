<?php 
/*
Plugin Name: RPG Cron list
Description: List all current cron jobs set up via wp_schedule_event
Version: 1.0.0
Author: Valtech Ltd
Author URI: http://www.valtech.co.uk
Copyright: Valtech Ltd
Text Domain: rpgcronlist
Domain Path: /lang
*/

if(!defined('ABSPATH')) exit; //EXIT IF ACCESSED DIRECTLY

if(!class_exists('rpgcronlist')):

class rpgcronlist{

	var $version = '1.0.0';
    var $settings = array();
    
    function __construct(){
        /* DO NOTHING HERE - ENSURE ONLY INITIALIZED ONCE */
    }

	function initialize(){
        $this->settings = array(
            'name'               => __('RPG Cron list', 'rpgcronlist'),
            'version'            => $this->version,
        );

		add_action('admin_menu', array($this,'admin_menu'));

    }

	function admin_menu(){
		if (function_exists('add_options_page')) {
			add_options_page(__('RPG Cron list'), __('RPG Cron list'), 'manage_options', basename(__FILE__), array($this,'custom_rpg_cron_list_page'));
		}
	}
	
	function custom_rpg_cron_list_page(){

		$opt_html_val = '<div><h1>RPG Cron list</h1></div>';
		$crons = _get_cron_array();
		$events = array();

		if (empty($crons)) {
			return new WP_Error('no_events', 'No scheduled cron events found.');
		}

		foreach ($crons as $time => $cron) {
			foreach ($cron as $hook => $dings) {
				foreach ($dings as $sig => $data) {
					$events["$hook-$sig-$time"] = (object) array(
						'hook'     => $hook,
						'time'     => $time,
						'sig'      => $sig,
						'args'     => $data['args'],
						'schedule' => $data['schedule'],
						'interval' => isset($data['interval']) ? $data['interval'] : null,
					);
				}
			}
		}

		$time_format = 'd-m-Y H:i:s';
		$results = '';

		foreach ($events as $id => $event) {
			$results .= "<tr><td>".$event->hook."</td><td>".var_export($event->args, true)."</td><td>".strtolower(esc_html($this->get_schedule_name($event->interval)))."</td><td>".
			esc_html(get_date_from_gmt(date('d-m-Y H:i:s', $event->time), $time_format))."<br/><em style='color:#0073aa;'>".esc_html($this->time_since(time(), $event->time))."</em></td></tr>";
		}

		$table = '<div class="wrap">
					<table class="wp-list-table widefat fixed" style="margin: 10px;">
					<thead><tr><th>Hook name</th><th>Arguments</th><th>Schedule</th><th>Next run</th></tr></thead>'.$results.'</table></div>';
	
		echo str_replace('\\', '', $opt_html_val.$table);
	}

	function get_schedules() {
		$schedules = wp_get_schedules();
		uasort($schedules, array($this, 'sort_schedules'));
		return $schedules;
	}

	function get_schedule_name($interval) {
		$schedules = $this->get_schedules();

		foreach ($schedules as $schedule) {
			if ($interval === $schedule['interval']) {
				return $schedule['display'];
			}
		}

		return $this->interval($interval);
	}

	function sort_schedules($a, $b) {
		return ($a['interval'] - $b['interval']);
	}

	function time_since($older_date, $newer_date) {
		return $this->interval($newer_date - $older_date);
	}
	 
	function interval($since) {
		//TIME PERIOD CHUNKS
		$chunks = array(
			//YEARS
			array(60 * 60 * 24 * 365, _n_noop('%s year', '%s years')),
			//MONTHS
			array(60 * 60 * 24 * 30, _n_noop('%s month', '%s months')),
			//WEEKS
			array(60 * 60 * 24 * 7, _n_noop('%s week', '%s weeks')),
			//DAYS
			array(60 * 60 * 24, _n_noop('%s day', '%s days')),
			//HOURS
			array(60 * 60, _n_noop('%s hour', '%s hours')),
			//MINUTES
			array(60, _n_noop('%s minute', '%s minutes')),
			//SECONDS
			array(1, _n_noop('%s second', '%s seconds')),
		);

		if ($since <= 0) {
			return 'now';
		}

		//LOOKING TO OUTPUT TWO CHUNKS OF TIME - e.g.:
		//x years, xx months
		//x days, xx hours
		$j = count($chunks);

		//FIRST CHUNK
		for ($i = 0; $i < $j; $i++) {
			$seconds = $chunks[ $i ][0];
			$name = $chunks[ $i ][1];

			//FOUND THE BIGGEST CHUNK break
			$count = floor($since / $seconds);
			if ($count) {
				break;
			}
		}

		$output = sprintf(translate_nooped_plural($name, $count), $count);

		//SECOND CHUNK
		if ($i + 1 < $j) {
			$seconds2 = $chunks[ $i + 1 ][0];
			$name2 = $chunks[ $i + 1 ][1];
			$count2 = floor(($since - ($seconds * $count)) / $seconds2);
			if ($count2) {
				$output .= ' ' . sprintf(translate_nooped_plural($name2, $count2), $count2);
			}
		}

		return $output;
	}

}

function rpgcronlist() {
    global $rpgcronlist;
    
    if(!isset($rpgcronlist)) {
        $rpgcronlist = new rpgcronlist();
        $rpgcronlist->initialize();
    }
    
    return $rpgcronlist;
}

//KICK OFF
rpgcronlist();

endif;
?>