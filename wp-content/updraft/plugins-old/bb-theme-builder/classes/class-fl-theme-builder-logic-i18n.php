<?php

/**
 * Handles translated strings.
 *
 * @since 0.1
 */
final class BB_Logic_I18n {

	/**
	 * Init.
	 *
	 * @since 0.1
	 * @return void
	 */
	static public function init() {
		add_action( 'bb_logic_enqueue_scripts', array( 'BB_Logic_I18n', 'insert_strings' ) );
	}

	/**
	 * Add the strings into the page as a var.
	 */
	static public function insert_strings() {
		wp_localize_script( 'bb-logic-core', 'LogicI18n', BB_Logic_I18n::strings() );
	}

	/**
	 * The array of strings.
	 */
	static public function strings() {
		return array(
			'Display this content if the following conditions are met...' => __( 'Display this content if the following conditions are met...', 'bb-theme-builder' ),
			'address line 1'              => __( 'address line 1', 'bb-theme-builder' ),
			'address line 2'              => __( 'address line 2', 'bb-theme-builder' ),
			'city'                        => __( 'city', 'bb-theme-builder' ),
			'state'                       => __( 'state', 'bb-theme-builder' ),
			'zip/postcode'                => __( 'zip/postcode', 'bb-theme-builder' ),
			'country'                     => __( 'country', 'bb-theme-builder' ),
			'minute(s) ago'               => __( 'minute(s) ago', 'bb-theme-builder' ),
			'hour(s) ago'                 => __( 'hour(s) ago', 'bb-theme-builder' ),
			'day(s) ago'                  => __( 'day(s) ago', 'bb-theme-builder' ),
			'week(s) ago'                 => __( 'week(s) ago', 'bb-theme-builder' ),
			'month(s) ago'                => __( 'month(s) ago', 'bb-theme-builder' ),
			'year(s) ago'                 => __( 'year(s) ago', 'bb-theme-builder' ),
			'minute(s)'                   => __( 'minute(s)', 'bb-theme-builder' ),
			'hour(s)'                     => __( 'hour(s)', 'bb-theme-builder' ),
			'day(s)'                      => __( 'day(s)', 'bb-theme-builder' ),
			'week(s)'                     => __( 'week(s)', 'bb-theme-builder' ),
			'month(s)'                    => __( 'month(s)', 'bb-theme-builder' ),
			'year(s)'                     => __( 'year(s)', 'bb-theme-builder' ),
			'Key'                         => __( 'Key', 'bb-theme-builder' ),
			'Value'                       => __( 'Value', 'bb-theme-builder' ),
			'equals'                      => __( 'equals', 'bb-theme-builder' ),
			'does not equal'              => __( 'does not equal', 'bb-theme-builder' ),
			'is less than'                => __( 'is less than', 'bb-theme-builder' ),
			'is less than or equal to'    => __( 'is less than or equal to', 'bb-theme-builder' ),
			'is greater than'             => __( 'is greater than', 'bb-theme-builder' ),
			'is greater than or equal to' => __( 'is greater than or equal to', 'bb-theme-builder' ),
			'starts with'                 => __( 'starts with', 'bb-theme-builder' ),
			'ends with'                   => __( 'ends with', 'bb-theme-builder' ),
			'contains'                    => __( 'contains', 'bb-theme-builder' ),
			'does not contain'            => __( 'does not contain', 'bb-theme-builder' ),
			'include'                     => __( 'include', 'bb-theme-builder' ),
			'includes'                    => __( 'includes', 'bb-theme-builder' ),
			'do not include'              => __( 'do not include', 'bb-theme-builder' ),
			'does not include'            => __( 'does not include', 'bb-theme-builder' ),
			'is set'                      => __( 'is set', 'bb-theme-builder' ),
			'is not set'                  => __( 'is not set', 'bb-theme-builder' ),
			'is empty'                    => __( 'is empty', 'bb-theme-builder' ),
			'is not empty'                => __( 'is not empty', 'bb-theme-builder' ),
			'on'                          => __( 'on', 'bb-theme-builder' ),
			'is on'                       => __( 'is on', 'bb-theme-builder' ),
			'not on'                      => __( 'not on', 'bb-theme-builder' ),
			'is not on'                   => __( 'is not on', 'bb-theme-builder' ),
			'before'                      => __( 'before', 'bb-theme-builder' ),
			'is before'                   => __( 'is before', 'bb-theme-builder' ),
			'on or before'                => __( 'on or before', 'bb-theme-builder' ),
			'is on or before'             => __( 'is on or before', 'bb-theme-builder' ),
			'after'                       => __( 'after', 'bb-theme-builder' ),
			'is after'                    => __( 'is after', 'bb-theme-builder' ),
			'on or after'                 => __( 'on or after', 'bb-theme-builder' ),
			'is on or after'              => __( 'is on or after', 'bb-theme-builder' ),
			'within'                      => __( 'within', 'bb-theme-builder' ),
			'is within'                   => __( 'is within', 'bb-theme-builder' ),
			'not within'                  => __( 'not within', 'bb-theme-builder' ),
			'is not within'               => __( 'is not within', 'bb-theme-builder' ),
			'between'                     => __( 'between', 'bb-theme-builder' ),
			'is between'                  => __( 'is between', 'bb-theme-builder' ),
			'not between'                 => __( 'not between', 'bb-theme-builder' ),
			'is not between'              => __( 'is not between', 'bb-theme-builder' ),
			'in the past'                 => __( 'in the past', 'bb-theme-builder' ),
			'over'                        => __( 'over', 'bb-theme-builder' ),
			'Post'                        => __( 'Post', 'bb-theme-builder' ),
			'Post Parent'                 => __( 'Post Parent', 'bb-theme-builder' ),
			'Post Type'                   => __( 'Post Type', 'bb-theme-builder' ),
			'Post Title'                  => __( 'Post Title', 'bb-theme-builder' ),
			'Post Excerpt'                => __( 'Post Excerpt', 'bb-theme-builder' ),
			'Post Content'                => __( 'Post Content', 'bb-theme-builder' ),
			'Post Featured Image'         => __( 'Post Featured Image', 'bb-theme-builder' ),
			'Post Comments Number'        => __( 'Post Comments Number', 'bb-theme-builder' ),
			'Post Comments Status'        => __( 'Post Comments Status', 'bb-theme-builder' ),
			'Post Template'               => __( 'Post Template', 'bb-theme-builder' ),
			'Post Taxonomy Term'          => __( 'Post Taxonomy Term', 'bb-theme-builder' ),
			'Post Status'                 => __( 'Post Status', 'bb-theme-builder' ),
			'Post Custom Field'           => __( 'Post Custom Field', 'bb-theme-builder' ),
			'Archive'                     => __( 'Archive', 'bb-theme-builder' ),
			'Archive Title'               => __( 'Archive Title', 'bb-theme-builder' ),
			'Archive Description'         => __( 'Archive Description', 'bb-theme-builder' ),
			'Archive Taxonomy Term'       => __( 'Archive Taxonomy Term', 'bb-theme-builder' ),
			'Archive Term Meta'           => __( 'Archive Term Meta', 'bb-theme-builder' ),
			'Author'                      => __( 'Author', 'bb-theme-builder' ),
			'Username'                    => __( 'Username', 'bb-theme-builder' ),
			'Author Bio'                  => __( 'Author Bio', 'bb-theme-builder' ),
			'Author Meta'                 => __( 'Author Meta', 'bb-theme-builder' ),
			'Author Login Status'         => __( 'Author Login Status', 'bb-theme-builder' ),
			'Author Role'                 => __( 'Author Role', 'bb-theme-builder' ),
			'User'                        => __( 'User', 'bb-theme-builder' ),
			'Username'                    => __( 'Username', 'bb-theme-builder' ),
			'User Bio'                    => __( 'User Bio', 'bb-theme-builder' ),
			'User Meta'                   => __( 'User Meta', 'bb-theme-builder' ),
			'User Login Status'           => __( 'User Login Status', 'bb-theme-builder' ),
			'Logged In'                   => __( 'Logged In', 'bb-theme-builder' ),
			'Logged Out'                  => __( 'Logged Out', 'bb-theme-builder' ),
			'User Role'                   => __( 'User Role', 'bb-theme-builder' ),
			'User Capability'             => __( 'User Capability', 'bb-theme-builder' ),
			'User Registered'             => __( 'User Registered', 'bb-theme-builder' ),
			'Post'                        => __( 'Post', 'bb-theme-builder' ),
			'Archive'                     => __( 'Archive', 'bb-theme-builder' ),
			'Author'                      => __( 'Author', 'bb-theme-builder' ),
			'User'                        => __( 'User', 'bb-theme-builder' ),
			'ACF Archive Field'           => __( 'ACF Archive Field', 'bb-theme-builder' ),
			'ACF Post Field'              => __( 'ACF Post Field', 'bb-theme-builder' ),
			'ACF Post Author Field'       => __( 'ACF Post Author Field', 'bb-theme-builder' ),
			'ACF User Field'              => __( 'ACF User Field', 'bb-theme-builder' ),
			'ACF Option Field'            => __( 'ACF Option Field', 'bb-theme-builder' ),
			'Advanced Custom Fields'      => __( 'Advanced Custom Fields', 'bb-theme-builder' ),
			'Customer Products Purchased' => __( 'Customer Products Purchased', 'bb-theme-builder' ),
			'Customer First Ordered'      => __( 'Customer First Ordered', 'bb-theme-builder' ),
			'Customer Last Ordered'       => __( 'Customer Last Ordered', 'bb-theme-builder' ),
			'Customer Total Orders'       => __( 'Customer Total Orders', 'bb-theme-builder' ),
			'Customer Total Products'     => __( 'Customer Total Products', 'bb-theme-builder' ),
			'Customer Total Spent'        => __( 'Customer Total Spent', 'bb-theme-builder' ),
			'Customer Billing Address'    => __( 'Customer Billing Address', 'bb-theme-builder' ),
			'Customer Shipping Address'   => __( 'Customer Shipping Address', 'bb-theme-builder' ),
			'Cart'                        => __( 'Cart', 'bb-theme-builder' ),
			'Cart Products'               => __( 'Cart Products', 'bb-theme-builder' ),
			'Cart Total'                  => __( 'Cart Total', 'bb-theme-builder' ),
			'WooCommerce'                 => __( 'WooCommerce', 'bb-theme-builder' ),
			'User Search History'         => __( 'User Search History', 'bb-theme-builder' ),
			'User Last Visited'           => __( 'User Last Visited', 'bb-theme-builder' ),
			'User Total Visits'           => __( 'User Total Visits', 'bb-theme-builder' ),
			'Behavior'                    => __( 'Behavior', 'bb-theme-builder' ),
			'Cookie'                      => __( 'Cookie', 'bb-theme-builder' ),
			'Referer'                     => __( 'Referer', 'bb-theme-builder' ),
			'URL Variable'                => __( 'URL Variable', 'bb-theme-builder' ),
			'Browser'                     => __( 'Browser', 'bb-theme-builder' ),
			'Country'                     => __( 'Country', 'bb-theme-builder' ),
			'State'                       => __( 'State', 'bb-theme-builder' ),
			'Geolocation'                 => __( 'Geolocation', 'bb-theme-builder' ),
			'Date and Time'               => __( 'Date and Time', 'bb-theme-builder' ),
			'Date'                        => __( 'Date', 'bb-theme-builder' ),
			'Month'                       => __( 'Month', 'bb-theme-builder' ),
			'January'                     => __( 'January', 'bb-theme-builder' ),
			'February'                    => __( 'February', 'bb-theme-builder' ),
			'March'                       => __( 'March', 'bb-theme-builder' ),
			'April'                       => __( 'April', 'bb-theme-builder' ),
			'May'                         => __( 'May', 'bb-theme-builder' ),
			'June'                        => __( 'June', 'bb-theme-builder' ),
			'July'                        => __( 'July', 'bb-theme-builder' ),
			'August'                      => __( 'August', 'bb-theme-builder' ),
			'September'                   => __( 'September', 'bb-theme-builder' ),
			'October'                     => __( 'October', 'bb-theme-builder' ),
			'November'                    => __( 'November', 'bb-theme-builder' ),
			'December'                    => __( 'December', 'bb-theme-builder' ),
			'Day'                         => __( 'Day', 'bb-theme-builder' ),
			'Sunday'                      => __( 'Sunday', 'bb-theme-builder' ),
			'Monday'                      => __( 'Monday', 'bb-theme-builder' ),
			'Tuesday'                     => __( 'Tuesday', 'bb-theme-builder' ),
			'Wednesday'                   => __( 'Wednesday', 'bb-theme-builder' ),
			'Thursday'                    => __( 'Thursday', 'bb-theme-builder' ),
			'Friday'                      => __( 'Friday', 'bb-theme-builder' ),
			'Saturday'                    => __( 'Saturday', 'bb-theme-builder' ),
			'Date and Time'               => __( 'Date and Time', 'bb-theme-builder' ),
			'Choose...'                   => __( 'Choose...', 'bb-theme-builder' ),
			'Add Rule Group'              => __( 'Add Rule Group', 'bb-theme-builder' ),
			'or'                          => __( 'or', 'bb-theme-builder' ),
			'Add Rule'                    => __( 'Add Rule', 'bb-theme-builder' ),
			'Cancel'                      => __( 'Cancel', 'bb-theme-builder' ),
			'Save'                        => __( 'Save', 'bb-theme-builder' ),
			'Conditional Logic Settings'  => __( 'Conditional Logic Settings', 'bb-theme-builder' ),
			'Add Rule Group'              => __( 'Add Rule Group', 'bb-theme-builder' ),
			'Rules'                       => __( 'Rules', 'bb-theme-builder' ),
			'Conditional Tag'             => __( 'Conditional Tag', 'bb-theme-builder' ),
			'404'                         => __( '404', 'bb-theme-builder' ),
			'Home'                        => __( 'Home', 'bb-theme-builder' ),
			'Front Page'                  => __( 'Front Page', 'bb-theme-builder' ),
			'Taxonomy Archive'            => __( 'Taxonomy Archive', 'bb-theme-builder' ),
			'Search Results'              => __( 'Search Results', 'bb-theme-builder' ),
			'WordPress Conditionals'      => __( 'WordPress Conditionals', 'bb-theme-builder' ),
			'Additional rules to determine if this layout should be displayed.' => __( 'Additional rules to determine if this layout should be displayed.', 'bb-theme-builder' ),

		);
	}
}

BB_Logic_I18n::init();
