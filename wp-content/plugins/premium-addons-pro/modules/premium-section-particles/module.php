<?php

/**
 * Class: Module
 * Name: Section Particles
 * Slug: premium-particles
 */

namespace PremiumAddonsPro\Modules\PremiumSectionParticles;

use PremiumAddonsPro\Base\Module_Base;
use PremiumAddons\Helper_Functions;
use Elementor\Controls_Manager;

if( !defined( 'ABSPATH' ) ) exit;

class Module extends Module_Base {
    
    public function __construct() {
        
        parent::__construct();
        
        //Checks if Section Particles is enabled
        $particles = get_option( 'pa_pro_save_settings' )['premium-particles'];
        
        $check_particles_active = isset( $particles ) ? $particles : 1;
        
        if( $check_particles_active ) {
            
            //Enqueue the required JS file
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            
            //Register Controls inside Section Layout tab
            add_action( 'elementor/element/section/section_layout/after_section_end',array( $this,'register_controls' ), 10 );
            
            //insert data before section rendering
            add_action( 'elementor/frontend/section/before_render',array( $this,'before_render' ), 10, 1 );
            
        }
    }
    
    /**
	 * Enqueue scripts.
	 *
	 * Registers required dependencies for the extension and enqueues them.
	 *
	 * @since 1.6.5
	 * @access public
	 */
	public function enqueue_scripts() {
        
		wp_add_inline_script(
			'jquery',
			'window.scopes_array = [];
			window.backend = 0;
		    jQuery( window ).on( "elementor/frontend/init", function() {
				elementorFrontend.hooks.addAction( "frontend/element_ready/section", function( $scope, $ ){
					if ( "undefined" == typeof $scope ) {
							return;
					}
					if ( $scope.hasClass( "premium-particles-yes" ) ) {
						window.scopes_array.push( $scope );
					}
					if(elementorFrontend.isEditMode()){		
						var url = papro_addons.particles_url;
						jQuery.cachedAssets = function( url, options ) {
							// Allow user to set any option except for dataType, cache, and url.
							options = jQuery.extend( options || {}, {
								dataType: "script",
								cache: true,
								url: url
							});
							// Return the jqXHR object so we can chain callbacks.
							return jQuery.ajax( options );
						};
						jQuery.cachedAssets( url );
						window.backend = 1;
					}
				});
			});
			jQuery(document).ready(function(){
				if ( jQuery.find( ".premium-particles-yes" ).length < 1 ) {
                
					return;
				}
				var url = papro_addons.particles_url;
                
				jQuery.cachedAssets = function( url, options ) {
					// Allow user to set any option except for dataType, cache, and url.
					options = jQuery.extend( options || {}, {
						dataType: "script",
						cache: true,
						url: url
					});
                    
					// Return the jqXHR object so we can chain callbacks.
					return jQuery.ajax( options );
				};
				jQuery.cachedAssets( url );
			});	'
		);
	}
    
    public function register_controls( $element ) {
        
        $element->start_controls_section('premium_particles_section',
            [
                'label'         => sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Particles', 'premium-addons-pro') ),
                'tab'           => Controls_Manager::TAB_LAYOUT
            ]
        );
        
        $element->add_control('premium_particles_switcher',
            [
                'label'         => __( 'Enable Particles', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'yes',
                'prefix_class'  => 'premium-particles-',
                'render_type'	=> 'template'
            ]
        );
        
        $element->add_control('premium_particles_zindex',
            [
                'label'         => __( 'Z-index', 'premium-addons-pro' ),
                'type'          => Controls_Manager::NUMBER,
                'default'       => 0
            ]
        );
        
        $element->add_control('premium_particles_custom_style',
            [
                'label'         => __( 'Custom Style', 'premium-addons-pro' ),
                'type'          => Controls_Manager::CODE,
                'description'   => __( 'Paste your particles JSON code here - Generate it from <a href="http://vincentgarreau.com/particles.js/#default" target="_blank">Here!</a>', 'premium-addons-pro' ),
                'render_type' => 'template',
            ]
        );

        $element->add_control('premium_particles_notice',
            [
                'raw'           => __( 'Kindly, be noted that you will need to add a background as particles JSON code doesn\'t include a background color', 'premium-addons-pro' ),
                'type'          => Controls_Manager::RAW_HTML,
            ]
        );
        
        $element->add_control('premium_particles_responsive',
            [
                'label'             => __('Apply Particles On', 'premium-addons-pro'),
                'type'              => Controls_Manager::SELECT2,
                'options'           => [
                    'desktop'   => __('Desktop','premium-addons-pro'),
                    'tablet'    => __('Tablet','premium-addons-pro'),
                    'mobile'    => __('Mobile','premium-addons-pro'),
                ],
                'default'           => [ 'desktop', 'tablet', 'mobile' ],
                'multiple'          => true,
                'label_block'       => true
            ]);
        
        $element->end_controls_section();
        
    }
    
    public function before_render( $element ) {
        
        $data               = $element->get_data();
        
        $type               = $data['elType'];
        
        $settings           = $element->get_settings_for_display();
        
        $zindex             = ! empty( $settings['premium_particles_zindex'] ) ? $settings['premium_particles_zindex'] : 0;
        
        if( 'section' == $type && 'yes' === $settings['premium_particles_switcher'] ) {
            
            if( ! empty( $settings['premium_particles_custom_style'] ) ) {
                
                $particles_settings = [
                    'zindex'    => $zindex,
                    'style'     => $settings['premium_particles_custom_style'],
                    'responsive'=> $settings['premium_particles_responsive']
                ];
                
                $element->add_render_attribute( '_wrapper', [
                    'data-particles-style'   => $particles_settings['style'],
                    'data-particles-zindex'  => $particles_settings['zindex'],
                    'data-particles-devices' => $particles_settings['responsive']
                ]);
                
                ?>
                
            <?php }
        }
    }
}