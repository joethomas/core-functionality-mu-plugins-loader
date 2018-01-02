<?php
/*
	Plugin Name: Core Functionality Plugins Loader
	Description: This plugin loads must-use functionality plugins for your site. This functionality would need to persist if the WordPress theme being used is changed in the future.
	Plugin URI: https://github.com/joethomas/core-functionality-mu-plugins-loader
	Version: 1.1.1
	Author: Joe Thomas
	Author URI: https://github.com/joethomas
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// Prevent direct file access
defined( 'ABSPATH' ) or exit;

// Load Plugins
function joe_mu_plugins_loader() {
	if( ! defined( 'WPMU_PLUGIN_DIR' ) ) {
		return;
	}

	$mu_plugins_dirs = array_filter( scandir( WPMU_PLUGIN_DIR ) );

	$non_dirs = array(
		'.',
		'..',
		'.DS_Store',
	);

	//strip out non-dirs
	for( $i = 0; $i < count( $non_dirs ); $i++ ) {
		$not_dir_key = array_search( $non_dirs[ $i ], $mu_plugins_dirs );

		if( $not_dir_key !== false ) {
			unset( $mu_plugins_dirs[ $not_dir_key ] );
		}

		unset( $not_dir_key );
	}

	unset( $non_dirs );

	if( empty( $mu_plugins_dirs ) ) {
		return;
	}

	sort( $mu_plugins_dirs );

	//load up mu-plugins from each valid directory
	foreach( $mu_plugins_dirs as $dir ) {
		$plugin_dir = trailingslashit( WPMU_PLUGIN_DIR ) . $dir;
		$plugin_file = trailingslashit( $plugin_dir ) . $dir . '.php';

		if( is_dir( $plugin_dir ) && file_exists( $plugin_file ) ) {
			require_once( $plugin_file );
		}

		unset( $plugin_file, $plugin_dir );
	}
}

joe_mu_plugins_loader();