<?php

namespace PremiumAddonsPro\Includes\White_Label;

if( ! defined('ABSPATH') ) exit;

/**
 * Contains White Label functions
 * @since 1.0.0
 */
class Helper {
   
    /**
     * Return plugin pro version author name
     * @since 1.0.0
     * @access public
     * @return string
     */
    public static function author_pro(){
        if( isset( get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-name-pro'] ) ) {
            $author_pro = get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-name-pro'];
        }
        
        return ( isset( $author_pro ) && '' != $author_pro ) ? $author_pro : 'Leap13';
    }
    
    /**
     * Return plugin pro version name
     * @since 1.0.0
     * @access public
     * @return string
     */
    public static function name_pro(){
        if( isset( get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-plugin-name-pro'] ) ) {
            $name_pro = get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-plugin-name-pro'];
        }
        
        return ( isset( $name_pro ) && '' != $name_pro ) ? $name_pro : 'Premium Addons PRO for Elementor';
    }
    
    /**
     * Check if hide plugin license tab is enabled
     * @since 1.0.0
     * @access public
     * @return boolean
     */
    public static function is_hide_license() {
        if( isset( get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-license'] ) ) {
            $hide_license = get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-license'];
        }
        
        return isset( $hide_license ) ? $hide_license : false;
    }
    
    /**
     * Check if license if valid
     * @since 1.0.0
     * @access public
     * @return boolean
     */
    public static function is_lic_act(){
        $license_status = get_option( 'papro_license_status' );
        return ( 'valid' == $license_status ) ? true : false;
    }
    
    /**
     * Check if hide plugin changelog link is enabled
     * @since 1.7.7
     * @access public
     * @return boolean
     */
    public static function is_hide_changelog() {
        if( isset( get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-changelog'] ) ) {
            $hide_changelog = get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-changelog'];
        }
        
        return isset( $hide_changelog ) ? $hide_changelog : false;
    }
    
}