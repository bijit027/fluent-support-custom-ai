<?php defined('ABSPATH') or die;
/**
 * Plugin Name:  Fluent Support Custom AI
 * Plugin URI:   https://fluentsupport.com
 * Description:  Customer Support and Ticketing System for WordPress
 * Version:      1.0.0
 * Author:       WPManageNinja LLC
 * Author URI:   https://fluentsupport.com
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  fluent-support-pro
 * Domain Path:  /languages
 */

if (defined('FLUENT_SUPPORT_CUSTOM_AI_DIR_FILE')) {
    return;
}

define('FLUENT_SUPPORT_CUSTOM_AI_DIR_FILE', __FILE__);

add_action('plugins_loaded', function () {
    add_action('init', function () {
        load_plugin_textdomain('fluent-support-custom-ai', false, 'fluent-support-custom-ai/languages/');
    });
});
require_once("fluent-support-custom-ai-boot.php");
add_action('fluent_support_loaded', function ($app) {
    (new \FluentSupportCustomAI\App\Application($app));
    do_action('fluent_support_custom_ai_loaded', $app);
});


