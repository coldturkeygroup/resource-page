<?php namespace ColdTurkey\ResourcePage;
/*
 * Plugin Name: Resource Page
 * Version: 1.1.1
 * Plugin URI: http://www.coldturkeygroup.com/
 * Description: Custom resource page containing informational videos for potential home buyers
 * Author: Cold Turkey Group
 * Author URI: http://www.coldturkeygroup.com/
 * Requires at least: 4.0
 * Tested up to: 4.3
 *
 * @package Resource Page Page
 * @author Aaron Huisinga
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'RESOURCE_PAGE_PLUGIN_PATH' ) )
	define( 'RESOURCE_PAGE_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

if ( ! defined( 'RESOURCE_PAGE_PLUGIN_VERSION' ) )
	define( 'RESOURCE_PAGE_PLUGIN_VERSION', '1.1.1' );

require_once( 'classes/class-resource-page.php' );

global $resource_page;
$resource_page = new ResourcePage( __FILE__, new PlatformCRM() );

if ( is_admin() ) {
	require_once( 'classes/class-resource-page-admin.php' );
	new ResourcePage_Admin( __FILE__ );
}
