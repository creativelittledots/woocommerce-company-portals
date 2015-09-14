<?php
/**
 * Taxonomies Admin
 *
 * @author      Creative Little Dots
 * @category    Admin
 * @package     WooCommerce Company Portals/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Company_Portals_Admin_Taxonomies' ) ) :

/**
 * WC_Company_Portals_Admin_Taxonomies Class
 *
 * Handles the taxonomy functionality.
 */
class WC_Company_Portals_Admin_Taxonomies {

	/**
	 * Constructor
	 */
	public function __construct() {
		
		add_filter('post_row_actions', array($this, 'generate_company_portal_action_link'), 10, 2);
		add_action('admin_action_generate_company_portal', array($this, 'generate_company_portal') );

	}
	
	/**
	 * Generate Company Portal Action Link
	 */
	public function generate_company_portal_action_link($actions, $post) {
		
		if ( $post->post_type == "wc-company" ) {
			
			if( $company = wc_get_company($post) ) {
				
				if( ! $company->portal_id ) { 
					
					$actions['generate_company_portal'] = '<a href="' . admin_url('admin.php?action=generate_company_portal&company_id=' . $post->ID) . '">Generate Company Portal</a>';
					
				}
				
			}
			
		}
		
		return $actions;
		
	}
	
	/**
	 * Generate Company Portal Action
	 */
	public function generate_company_portal() {
		
		if( isset( $_REQUEST['company_id'] ) ) {
			
			if( $company = wc_get_company( $_REQUEST['company_id']) ) {
				
				if( ! $company->portal_id && ! term_exists( $company->slug, 'company_portal' ) ) {
				
					$portal_id = wp_insert_term(
					  $company->get_title(), // the term 
					  'company_portal', // the taxonomy
					  array(
					    'slug' => $company->slug,
					  )
					);
					
					update_post_meta($company->id, 'portal_id', $portal_id);
					
				}
				
			}
			
		}
		
		wp_redirect( admin_url( 'edit-tags.php?taxonomy=company_portal&post_type=product' ) );
		
	}
			
}

endif;

new WC_Company_Portals_Admin_Taxonomies();
