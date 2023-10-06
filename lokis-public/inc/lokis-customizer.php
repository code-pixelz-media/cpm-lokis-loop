<?php

//Creates a customizer for the user dashboard endpoint backgrounds
function lokis_endpoint_customizer_settings($wp_customize)
{
    // Add a new section
    $wp_customize->add_section(
        'lokis_endpoint_section',
        array(
            'title' => 'User Dashboard Endpoint Background',
            'priority' => 30,
        )
    );

    // Add a new setting
    $wp_customize->add_setting(
        'lokis_endpoint_background',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    // Add a control for the setting
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'lokis_endpoint_background',
            array(
                'label' => 'Background Image for User Dashboard',
                'section' => 'lokis_endpoint_section',
                'settings' => 'lokis_endpoint_background',
            )
        )
    );
}
add_action('customize_register', 'lokis_endpoint_customizer_settings');