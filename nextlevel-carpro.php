<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.kri8it.com
 * @since             1.0.0
 * @package           nextlevel_carpro
 *
 * @wordpress-plugin
 * Plugin Name:       NEXTLEVEL Carpro
 * Plugin URI:        https://nextlevel.thrifty.co.za
 * Description:       Syncs and connects to the NEXTLEVEL hub and CARPRO
 * Version:           1.0.1
 * Author:            Hilton Moore
 * Author URI:        https://www.kri8it.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nextlevel-carpro
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NEXTLEVEL_CARPRO_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nextlevel-carpro-activator.php
 */
function activate_nextlevel_carpro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nextlevel-carpro-activator.php';
	Nextlevel_Carpro_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-nextlevel-carpro-deactivator.php
 */
function deactivate_nextlevel_carpro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nextlevel-carpro-deactivator.php';
	Nextlevel_Carpro_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_nextlevel_carpro' );
register_deactivation_hook( __FILE__, 'deactivate_nextlevel_carpro' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nextlevel-carpro.php';








add_action( 'plugins_loaded', 'check_for_update_carpro' );
function check_for_update_carpro(){

    require_once plugin_dir_path( __FILE__ ) . 'includes/class-nextlevel-carpro-updater.php';


      $config = array(
            'slug'               => plugin_basename( __FILE__ ),
            'proper_folder_name' => 'nextlevel-carpro',
            'api_url'            => 'https://api.github.com/repos/kri8itdigital/nextlevel-carpro',
            'raw_url'            => 'https://raw.github.com/kri8itdigital/nextlevel-carpro/master',
            'github_url'         => 'https://github.com/kri8itdigital/nextlevel-carpro',
            'zip_url'            => 'https://github.com/kri8itdigital/nextlevel-carpro/archive/master.zip',
            'homepage'           => 'https://github.com/kri8itdigital/nextlevel-carpro',
            'sslverify'          => true,
            'requires'           => '5.0',
            'tested'             => '5.7',
            'readme'             => 'README.md',
            'version'            => '1.0.1'
        );

        new Nextlevel_Carpro_Updater( $config );

}









/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_nextlevel_carpro() {

	$plugin = new Nextlevel_Carpro();
	$plugin->run();

}
run_nextlevel_carpro();
