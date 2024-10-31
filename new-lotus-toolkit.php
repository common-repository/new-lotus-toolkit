<?php
/*
Plugin Name: New Lotus Toolkit
Description: A specific plugin use in New Lotus Theme, included some custom widgets.
Version: 1.0.0
Author: Kopa Theme
Author URI: http://kopatheme.com
License: GNU General Public License v3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Revant Toolkit plugin, Copyright 2015 Kopatheme.com
Revant Toolkit is distributed under the terms of the GNU GPL

Requires at least: 4.1
Tested up to: 4.2.2
Text Domain: new-lotus-toolkit
Domain Path: /languages/
*/

define('NLT_PATH', plugin_dir_path(__FILE__));
add_action('plugins_loaded', array('New_Lotus_Toolkit', 'plugins_loaded'));
add_action('after_setup_theme', array('New_Lotus_Toolkit', 'after_setup_theme'), 20 );

class New_Lotus_Toolkit {

	function __construct(){
		require NLT_PATH . 'inc/hook.php';
		require NLT_PATH . 'inc/widget.php';
	}

	public static function plugins_loaded(){
		load_plugin_textdomain('new-lotus-toolkit', false, NLT_PATH . '/languages/');
	}

	public static function after_setup_theme(){
		if (!defined('KOPA_THEME_NAME') || !class_exists('Kopa_Framework'))
			return; 		
		else	
			new New_Lotus_Toolkit();
	}
}