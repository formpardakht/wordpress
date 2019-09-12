<?php

/**
 * @package Formpardakht
 */
/*
Plugin Name: اسکریپت فرم پرداخت
Plugin URI: https://formpardakht.com/
Description: 
Version: 1.0.0
Author: Formpardakht
Author URI: https://formpardakht.com
License: MIT
Text Domain: formpardakht
*/

if (!function_exists('add_action')) {
    echo 'Hi there!';
    exit;
}

define('FORMPARDAKHT_VERSION', '1.0.0');
define('FORMPARDAKHT_URL', 'https://formpardakht.com/latest.zip');
define('FORMPARDAKHT__PLUGIN_DIR', plugin_dir_path(__FILE__));

include(__DIR__ . '/functions.php');

register_activation_hook(__FILE__, 'formpardakht_activation');
register_deactivation_hook(__FILE__, 'formpardakht_deactivation');
register_uninstall_hook(__FILE__, 'formpardakht_uninstall');

add_action('admin_init', 'formpardakht_redirect');

function formpardakht_activation()
{
    add_option('formpardakht_do_activation_redirect', true);
}

function formpardakht_uninstall()
{
    global $wpdb;

    $drop = "DROP TABLE IF EXISTS migrations";
    $wpdb->query($drop);

    $drop = "DROP TABLE IF EXISTS fp_users";
    $wpdb->query($drop);

    $drop = "DROP TABLE IF EXISTS fp_configs";
    $wpdb->query($drop);

    $drop = "DROP TABLE IF EXISTS fp_factors";
    $wpdb->query($drop);

    $drop = "DROP TABLE IF EXISTS fp_files";
    $wpdb->query($drop);

    $drop = "DROP TABLE IF EXISTS fp_forms";
    $wpdb->query($drop);

    $drop = "DROP TABLE IF EXISTS fp_password_resets";
    $wpdb->query($drop);

    $drop = "DROP TABLE IF EXISTS fp_transactions";
    $wpdb->query($drop);

    $installDir = get_home_path() . get_option('formpardakht_directory');
    if (file_exists($installDir)) {
        formpardakht_delete_dir($installDir);
    }

    delete_option('formpardakht_installed');
    delete_option('formpardakht_directory');
}

function formpardakht_redirect()
{
    if (get_option('formpardakht_do_activation_redirect', false)) {
        delete_option('formpardakht_do_activation_redirect');
        wp_redirect(admin_url('admin.php?page=formpardakht'));
    }
}

function formpardakht_register_settings()
{
    add_option('formpardakht_installed', false);
    add_option('formpardakht_directory', 'formpardakht');
    register_setting('formpardakht_options_group', 'formpardakht_installed', 'formpardakht_callback');
    register_setting('formpardakht_options_group', 'formpardakht_directory', 'formpardakht_callback');
}
add_action('admin_init', 'formpardakht_register_settings');

function formpardakht_admin_menu()
{
    add_menu_page(
        __('اسکریپت فرم پرداخت', 'formpardakht'),
        __('اسکریپت فرم پرداخت', 'formpardakht'),
        'manage_options',
        'formpardakht',
        'formpardakht_admin_content',
        'dashicons-schedule',
        50
    );
}

add_action('admin_menu', 'formpardakht_admin_menu');

function formpardakht_admin_content()
{
    include(FORMPARDAKHT__PLUGIN_DIR . '/admin.php');
}

function formpardakht_scripts()
{
    wp_register_style('foo-styles',  plugin_dir_url(__FILE__) . 'installer/install.css');
    wp_enqueue_style('foo-styles');
}
add_action('wp_enqueue_scripts', 'formpardakht_scripts');
