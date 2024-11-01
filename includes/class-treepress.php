<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.blackandwhitedigital.eu/
 * @since      1.0.0
 *
 * @package    Treepress
 * @subpackage Treepress/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Treepress
 * @subpackage Treepress/includes
 * @author     Black and White Digital Ltd <bd.kabiruddin@gmail.com>
 */
class Treepress {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Treepress_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'TREEPRESS_VERSION' ) ) {
			$this->version = TREEPRESS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'treepress';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Treepress_Loader. Orchestrates the hooks of the plugin.
	 * - Treepress_i18n. Defines internationalization functionality.
	 * - Treepress_Admin. Defines all hooks for the admin area.
	 * - Treepress_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-treepress-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-treepress-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-treepress-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-treepress-public.php';

		$this->loader = new Treepress_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Treepress_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Treepress_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Treepress_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_admin, 'init_post_type_chart' );
		$this->loader->add_action( 'init', $plugin_admin, 'init_post_type_member' );
		$this->loader->add_action( 'init', $plugin_admin, 'init_post_type_family' );
		$this->loader->add_action( 'init', $plugin_admin, 'init_post_type_family_group_taxonomies' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
		$this->loader->add_filter( 'custom_menu_order', $plugin_admin, 'submenu_order' );
		$this->loader->add_filter( 'parent_file', $plugin_admin, 'set_current_menu' );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_metabox_member' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_metabox_family' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_metabox_chart' );

		$this->loader->add_action( 'save_post', $plugin_admin, 'save_metabox_member_info', 10, 2 );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_metabox_member_hide_featured_image' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_metabox_family_info', 10, 2 );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_metabox_chart_setting' );

		$this->loader->add_filter( 'manage_edit-chart_columns', $plugin_admin, 'chart_columns' );
		$this->loader->add_action( 'manage_chart_posts_custom_column', $plugin_admin, 'manage_chart_posts_custom_column', 10, 2 );

		$this->loader->add_filter( 'manage_edit-member_columns', $plugin_admin, 'member_columns' );
		$this->loader->add_action( 'manage_member_posts_custom_column', $plugin_admin, 'manage_member_posts_custom_column', 10, 2 );

		$this->loader->add_filter( 'manage_edit-member_sortable_columns', $plugin_admin, 'member_sortable_columns' );
		$this->loader->add_action( 'pre_get_posts', $plugin_admin, 'member_sortable_columns_pre_get_posts', 10, 2 );

		$this->loader->add_filter( 'manage_edit-family-group_columns', $plugin_admin, 'family_group_columns' );
		$this->loader->add_filter( 'manage_family-group_custom_column', $plugin_admin, 'manage_family_group_custom_column', 10, 3 );

		$this->loader->add_action( 'family-group_edit_form_fields', $plugin_admin, 'family_group_edit_form_fields', 10, 2 );
		$this->loader->add_action( 'edited_family-group', $plugin_admin, 'save_family_group_custom_fields', 10, 2 );

		$this->loader->add_action( 'create_family-group', $plugin_admin, 'create_family_free' );
		$this->loader->add_filter( 'enter_title_here', $plugin_admin, 'member_change_title_text' );
		$this->loader->add_action( 'wp_ajax_unlink_function', $plugin_admin, 'unlink_function' );

		$this->loader->add_filter( 'plugin_action_links_treepress/treepress.php', $plugin_admin, 'treepress_paypal_link' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Treepress_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_filter( 'the_content', $plugin_public, 'bio_data_insert_in_single_page' );

		add_shortcode( 'family-tree', array( $plugin_public, 'treepress_family_tree_shortcode' ) );
		add_shortcode( 'family-members', array( $plugin_public, 'treepress_family_members_shortcode' ) );
		add_shortcode( 'members', array( $plugin_public, 'treepress_members_shortcode' ) );
		add_shortcode( 'Chart', array( $plugin_public, 'chart_shortcode' ) );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Treepress_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}


