<?php

/*
 * Plugin Name:       Form Alternance
 * Plugin URI:        https://example.com/plugins/form-alternance/
 * Description:       Handle the basics with this plugin.
 * Version:           0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Flo
 * Author URI:        https://author.example.com/
*/
 
 
// Define the shortcode to display the registration form
function registration_form_shortcode() {

    $output = '';

    if (isset($_POST['submit_registration'])) {

        global $wpdb;

        $table_name = $wpdb->prefix . 'form_alternance';

        $wpdb->insert(
            $table_name,
            array(
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'date_of_birth' => $_POST['date_of_birth'],
                 'current_diploma' => $_POST['current_diploma'],
                'education_level' => $_POST['education_level'],
                'desired_education' => $_POST['desired_education'],
                'city' => $_POST['city'],
                'mobility' => $_POST['mobility'],
                'comment' => $_POST['comment'],
            )
        );

        $output = '<p class="registration-success">Nous avons bien reçu vos informations.</p>';
    }

    ob_start(); 
    include 'registration-form.php';
    $form_content = ob_get_clean(); 

    $output .= $form_content;

    return $output;
}


// Register the shortcode
add_shortcode('registration_form', 'registration_form_shortcode');

// Create the table for storing form data on plugin activation
function create_form_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'form_alternance';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        first_name VARCHAR(255) NOT NULL,
        last_name VARCHAR(255) NOT NULL,
        date_of_birth DATE NOT NULL,
        current_diploma VARCHAR(255) NOT NULL,
        education_level VARCHAR(255) NOT NULL,
        desired_education VARCHAR(255) NOT NULL,
        city VARCHAR(255) NOT NULL,
        mobility INT NOT NULL,
        comment TEXT NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Enqueue the stylesheet 
function form_alternance_enqueue_styles() {
    
    wp_enqueue_style('registration-form-style', plugin_dir_url(__FILE__) . 'registration-form.css');
}

// Hook the enqueue function to WordPress
add_action('wp_enqueue_scripts', 'form_alternance_enqueue_styles');

register_activation_hook(__FILE__, 'create_form_table');


