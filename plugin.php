<?php
/**
 * @package           company_name\plugin_name
 *
 * Plugin Name:       Plugin Name
 * Plugin URI:        https://plugin.uri
 * Description:       Plugin Description.
 * Version:           1.0.0
 * Author:            Author Name <author-name@author.uri>
 * Author URI:        https://author.uri
 * Text Domain:       plugin-name
 * Domain Path:       /languages
 */

 /**
 * Copyright (C) 2018  Author Name  author-name@author.uri
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 3, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace company_name\plugin_name;

// Abort if this file is called directly.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Constants.
define( 'COMPANY_NAME_PLUGIN_NAME_ROOT', __FILE__ );
define( 'COMPANY_NAME_PLUGIN_NAME_PREFIX', 'plugin_name' );

/**
 * The main loader for this plugin
 */
class Main {

	/**
	 * Dependancies
	 * 
	 * In order to use Gutenberg, every time you load a library (eg wp.element, wp.blocks)
	 * you need to ensure you have added the dependency to the enqueue. Otherwise your
	 * application will error (especailly if its the only block in the soloution).
	 *
	 * @var array
	 * 
	 * @since 1.0.0
	 */
	public $dependencies;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		/**
		 * Dependencies
		 * 
		 * In order to use Gutenberg, every time you load a library (eg wp.element, wp.blocks)
	 	 * you need to ensure you have added the dependency to the enqueue. Otherwise your
	 	 * application will error (especailly if its the only block in the soloution).
		 */
		$this->dependencies = [
			'wp-api',
			'wp-blocks',
			'wp-components',
			'wp-data',
			'wp-editor',
			'wp-element',
			'wp-i18n',
		];
	}

	/**
	 * Run all of the plugin functions.
	 *
	 * @since 1.0.0
	 */
	public function run() {

		/**
		 * Load Text Domain
		 */
		load_plugin_textdomain( 'mkdo-blocks', false, COMPANY_NAME_PLUGIN_NAME_ROOT . '\languages' );

		/**
		 * Load assets.
		 */

		/**
		 * Load Admin Assets.
		 * 
		 * Admin assets do not alter the Gutenberg blocks, but load in the editor. 
		 * If you need to style an InspectorControl, this is the place you can do it.
		 **/ 
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );

		// Load Block Editor Assets.
		add_action( 'enqueue_block_editor_assets', array( $this, 'editor_assets' ) ); 

		// Load Block Front and Back End Assets (can use a conditional to restrict load).
		add_action( 'enqueue_block_assets', array( $this, 'assets' ) ); 


		/**
		 * Setup Block Categories
		 * 
		 * If you would like an additional category for your block (in addition to common, 
		 * layout, widget etc... ). This is the hook to set it.
		 */
		add_action( 'block_categories', array( $this, 'block_categories' ), 10, 2 );

	}

	/**
	 * Enqueue Admin Styles.
	 * 
	 * Assets to alter the editor (InspectorControls), does not impact front end block styles.
	 * 
	 * @since 1.0.0
	 */
	public function admin_assets() {

		$styles = '/assets/css/admin.css';

		// Enqueue Styles.
		wp_enqueue_style(
			'mkdo-blocks-admin',
			plugins_url( $styles, __FILE__ ),
			[],
			filemtime( plugin_dir_path( __FILE__ ) . $styles )
		);
	}

	/**
	 * Block Editor Assets.
	 * 
	 * Assets that load on on the Gutenberg 'Edit' interface. Use the styles to
	 * add styles that only impact the 'edit' view.
	 * 
	 * The `editor.js` file is the combined React for your Gutenberg Block
	 * 
	 * @since 1.0.0
	 */
	public function editor_assets() {

		$scripts = '/assets/js/editor.js';
		$styles  = '/assets/css/editor.css';

		// Enqueue editor JS.
		wp_enqueue_script(
			'mkdo-blocks-editor',
			plugins_url( $scripts, __FILE__ ),
			$this->dependencies,
			filemtime( plugin_dir_path( __FILE__ ) . $scripts )
		);

		// Enqueue edtior Styles.
		wp_enqueue_style(
			'mkdo-blocks-editor',
			plugins_url( $styles, __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . $styles )
		);
	}

	/**
	 * Block Assets.
	 * 
	 * Assets that load on on the Gutenberg 'Save' and Admin view. If you want 
	 * certain assets to only load on the front end, wrap them in a `is_admin` conditional.
	 * 
	 * @since 1.0.0
	 */
	public function assets() {

		$scripts = '/assets/js/script.js';
		$styles  = '/assets/css/style.css';

		// Example: JavaScript will run on the Front End only.
		if ( ! is_admin() ) {
			// Enqueue JS.
			wp_enqueue_script(
				'mkdo-blocks',
				plugins_url( $scripts, __FILE__ ),
				$this->dependencies,
				filemtime( plugin_dir_path( __FILE__ ) . $scripts )
			);
		}

		// Enqueue Styles.
		wp_enqueue_style(
			'mkdo-blocks',
			plugins_url( $styles, __FILE__ ),
			[],
			filemtime( plugin_dir_path( __FILE__ ) . $styles )
		);

	}

	/**
	 * Block Categories
	 * 
	 * Create a custom category to host your blocks.
	 * 
	 * @param  array  $categories Array of categories.
	 * @param  object $post       The post object.
	 * @return array  $categories Array of categories.
	 * 
	 * @since 1.0.0
	 */
	public function block_categories( $categories, $post ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'starter-blocks',
					'title' => __( 'Starter Blocks', 'plugin-name' ),
				),
			)
		);
	}
}

$main = new Main();
$main->run();