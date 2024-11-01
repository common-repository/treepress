<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.blackandwhitedigital.eu/
 * @since      1.0.0
 *
 * @package    Treepress
 * @subpackage Treepress/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Treepress
 * @subpackage Treepress/admin
 * @author     Black and White Digital Ltd <bd.kabiruddin@gmail.com>
 */
class Treepress_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name       The name of this plugin.
     * @param      string $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Treepress_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Treepress_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style(
            $this->plugin_name . '-select2',
            plugin_dir_url( __FILE__ ) . 'css/select2.min.css',
            array( 'wp-jquery-ui-dialog' ),
            $this->version,
            'all'
        );
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/treepress-admin.css',
            array( 'wp-jquery-ui-dialog', $this->plugin_name . '-select2' ),
            $this->version,
            'all'
        );
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Treepress_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Treepress_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script(
            $this->plugin_name . '-select2',
            plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js',
            array( 'jquery' ),
            $this->version,
            true
        );
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/treepress-admin.js',
            array( 'jquery', 'jquery-ui-dialog', $this->plugin_name . '-select2' ),
            $this->version,
            true
        );
        wp_localize_script( $this->plugin_name, 'cTPadmin', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'tpajax-nonce' ),
        ) );
    }
    
    /**
     * Register a custom post type called "chart".
     *
     * @see get_post_type_labels() for label keys.
     */
    public function init_post_type_chart()
    {
        $labels = array(
            'name'                  => _x( 'Charts', 'Post type general name', 'treepress' ),
            'singular_name'         => _x( 'Chart', 'Post type singular name', 'treepress' ),
            'menu_name'             => _x( 'Charts', 'Admin Menu text', 'treepress' ),
            'name_admin_bar'        => _x( 'Chart', 'Add New on Toolbar', 'treepress' ),
            'add_new'               => __( 'Add New', 'treepress' ),
            'add_new_item'          => __( 'Add New Chart', 'treepress' ),
            'new_item'              => __( 'New Chart', 'treepress' ),
            'edit_item'             => __( 'Edit Chart', 'treepress' ),
            'view_item'             => __( 'View Chart', 'treepress' ),
            'all_items'             => __( 'All Charts', 'treepress' ),
            'search_items'          => __( 'Search Charts', 'treepress' ),
            'parent_item_colon'     => __( 'Parent Charts:', 'treepress' ),
            'not_found'             => __( 'No charts found.', 'treepress' ),
            'not_found_in_trash'    => __( 'No charts found in Trash.', 'treepress' ),
            'featured_image'        => _x( 'Chart Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'treepress' ),
            'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'treepress' ),
            'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'treepress' ),
            'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'treepress' ),
            'archives'              => _x( 'Chart archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'treepress' ),
            'insert_into_item'      => _x( 'Insert into chart', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'treepress' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this chart', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'treepress' ),
            'filter_items_list'     => _x( 'Filter charts list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'treepress' ),
            'items_list_navigation' => _x( 'Charts list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'treepress' ),
            'items_list'            => _x( 'Charts list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'treepress' ),
        );
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'treepress',
            'query_var'          => true,
            'rewrite'            => array(
            'slug' => 'chart',
        ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => true,
            'supports'           => array( 'title', 'author', 'thumbnail' ),
        );
        register_post_type( 'chart', $args );
    }
    
    /**
     * Register member post type.
     *
     * @since    1.0.0
     */
    public function init_post_type_member()
    {
        $labels = array(
            'name'                  => _x( 'Members', 'Post type general name', 'treepress' ),
            'singular_name'         => _x( 'Member', 'Post type singular name', 'treepress' ),
            'menu_name'             => _x( 'Members', 'Admin Menu text', 'treepress' ),
            'name_admin_bar'        => _x( 'Member', 'Add New on Toolbar', 'treepress' ),
            'add_new'               => __( 'Add New', 'treepress' ),
            'add_new_item'          => __( 'Add New Member', 'treepress' ),
            'new_item'              => __( 'New Member', 'treepress' ),
            'edit_item'             => __( 'Edit Member', 'treepress' ),
            'view_item'             => __( 'View Member', 'treepress' ),
            'all_items'             => __( 'All Members', 'treepress' ),
            'search_items'          => __( 'Search Members', 'treepress' ),
            'parent_item_colon'     => __( 'Parent Members:', 'treepress' ),
            'not_found'             => __( 'No Members found.', 'treepress' ),
            'not_found_in_trash'    => __( 'No Members found in Trash.', 'treepress' ),
            'featured_image'        => _x( 'Member Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'treepress' ),
            'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'treepress' ),
            'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'treepress' ),
            'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'treepress' ),
            'archives'              => _x( 'Member archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'treepress' ),
            'insert_into_item'      => _x( 'Insert into member', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'treepress' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this member', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'treepress' ),
            'filter_items_list'     => _x( 'Filter Members list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'treepress' ),
            'items_list_navigation' => _x( 'Members list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'treepress' ),
            'items_list'            => _x( 'Members list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'treepress' ),
        );
        $supports = array( 'title', 'custom-fields' );
        $args = array(
            'labels'             => $labels,
            'description'        => 'Member custom post type.',
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'treepress',
            'query_var'          => true,
            'rewrite'            => array(
            'slug' => 'member',
        ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'supports'           => $supports,
            'taxonomies'         => array( 'family-group' ),
            'show_in_rest'       => true,
        );
        register_post_type( 'member', $args );
    }
    
    /**
     * Register family post type.
     *
     * @since    1.0.0
     */
    public function init_post_type_family()
    {
        $labels = array(
            'name'                  => _x( 'Families', 'Post type general name', 'treepress' ),
            'singular_name'         => _x( 'Family', 'Post type singular name', 'treepress' ),
            'menu_name'             => _x( 'Families', 'Admin Menu text', 'treepress' ),
            'name_admin_bar'        => _x( 'Family', 'Add New on Toolbar', 'treepress' ),
            'add_new'               => __( 'Add New', 'treepress' ),
            'add_new_item'          => __( 'Add New Family', 'treepress' ),
            'new_item'              => __( 'New Family', 'treepress' ),
            'edit_item'             => __( 'Edit Family', 'treepress' ),
            'view_item'             => __( 'View Family', 'treepress' ),
            'all_items'             => __( 'All Families', 'treepress' ),
            'search_items'          => __( 'Search Families', 'treepress' ),
            'parent_item_colon'     => __( 'Parent Families:', 'treepress' ),
            'not_found'             => __( 'No Families found.', 'treepress' ),
            'not_found_in_trash'    => __( 'No Families found in Trash.', 'treepress' ),
            'featured_image'        => _x( 'Family Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'treepress' ),
            'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'treepress' ),
            'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'treepress' ),
            'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'treepress' ),
            'archives'              => _x( 'Family archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'treepress' ),
            'insert_into_item'      => _x( 'Insert into family', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'treepress' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this family', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'treepress' ),
            'filter_items_list'     => _x( 'Filter Families List', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'treepress' ),
            'items_list_navigation' => _x( 'Families List navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'treepress' ),
            'items_list'            => _x( 'Families List', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'treepress' ),
        );
        $args = array(
            'labels'             => $labels,
            'description'        => 'Family custom post type.',
            'public'             => true,
            'publicly_queryable' => true,
            'show_in_menu'       => 'treepress',
            'query_var'          => true,
            'rewrite'            => array(
            'slug' => 'family',
        ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'supports'           => array( 'title', 'author', 'thumbnail' ),
            'taxonomies'         => array( 'family-group' ),
            'show_in_rest'       => true,
        );
        register_post_type( 'family', $args );
    }
    
    /**
     * Create two taxonomies, family-groups and writers for the post type "book".
     *
     * @see register_post_type() for registering custom post types.
     */
    public function init_post_type_family_group_taxonomies()
    {
        // Add new taxonomy, make it hierarchical (like categories).
        $labels = array(
            'name'              => _x( 'Family Groups', 'taxonomy general name', 'treepress' ),
            'singular_name'     => _x( 'Family Group', 'taxonomy singular name', 'treepress' ),
            'search_items'      => __( 'Search Family Groups', 'treepress' ),
            'all_items'         => __( 'All Family Groups', 'treepress' ),
            'parent_item'       => __( 'Parent Family Group', 'treepress' ),
            'parent_item_colon' => __( 'Parent Family Group:', 'treepress' ),
            'edit_item'         => __( 'Edit Family Group', 'treepress' ),
            'update_item'       => __( 'Update Family Group', 'treepress' ),
            'add_new_item'      => __( 'Add New Family Group', 'treepress' ),
            'new_item_name'     => __( 'New Family Group Name', 'treepress' ),
            'menu_name'         => __( 'Family Group', 'treepress' ),
        );
        $args = array(
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_in_menu'      => 'treepress',
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array(
            'slug' => 'family-group',
        ),
            'show_in_rest'      => true,
        );
        register_taxonomy( 'family-group', array( 'member', 'family' ), $args );
    }
    
    /**
     * TreePress menu page.
     *
     * @since    1.0.0
     */
    public function admin_menu()
    {
        add_menu_page(
            __( 'TreePress', 'treepress' ),
            __( 'TreePress', 'treepress' ),
            'manage_options',
            'treepress',
            array( $this, 'treepress_page' ),
            'dashicons-networking',
            30
        );
        add_submenu_page(
            'treepress',
            __( 'Add Chart', 'treepress' ),
            __( 'Add Chart', 'treepress' ),
            'manage_options',
            'post-new.php?post_type=chart'
        );
        add_submenu_page(
            'treepress',
            __( 'Add Family', 'treepress' ),
            __( 'Add Family', 'treepress' ),
            'manage_options',
            'post-new.php?post_type=family'
        );
        add_submenu_page(
            'treepress',
            __( 'Add Member', 'treepress' ),
            __( 'Add Member', 'treepress' ),
            'manage_options',
            'post-new.php?post_type=member'
        );
        add_submenu_page(
            'treepress',
            __( 'Family Groups', 'treepress' ),
            __( 'Family Groups', 'treepress' ),
            'manage_categories',
            'edit-tags.php?taxonomy=family-group&post_type=member'
        );
        add_submenu_page(
            'treepress',
            __( 'Options', 'treepress' ),
            __( 'Options', 'treepress' ),
            'manage_options',
            'treepress-options',
            array( $this, 'options_panel' )
        );
        add_submenu_page(
            'treepress',
            __( 'Account Settings', 'treepress' ),
            __( 'Account Settings', 'treepress' ),
            'manage_options',
            'treepress-account-settings',
            array( $this, 'account_settings' )
        );
    }
    
    /**
     * Submenu order.
     *
     * @param mixed $menu_order menu_order.
     *
     * @since    1.0.0
     */
    public function submenu_order( $menu_order )
    {
        global  $submenu ;
        $order = array(
            'edit.php?post_type=chart',
            'post-new.php?post_type=chart',
            'edit.php?post_type=member',
            'post-new.php?post_type=member',
            'edit.php?post_type=family',
            'post-new.php?post_type=family',
            'edit-tags.php?taxonomy=family-group&post_type=member',
            'treepress-options',
            'treepress-import',
            'treepress-export'
        );
        $new = array();
        foreach ( $order as $order_item ) {
            foreach ( $submenu['treepress'] as $key => $menu_item ) {
                
                if ( $menu_item['2'] === $order_item ) {
                    $new[] = $menu_item;
                    unset( $submenu['treepress'][$key] );
                }
            
            }
        }
        $submenu['treepress'] = $new + $submenu['treepress'];
        // phpcs:ignore
        return $menu_order;
    }
    
    /**
     * Set current menu.
     *
     * @param mixed $parent_file parent_file.
     *
     * @return mixed
     *
     * @since    1.0.0
     */
    public function set_current_menu( $parent_file )
    {
        global  $submenu_file, $current_screen, $pagenow ;
        
        if ( 'member' === $current_screen->post_type ) {
            
            if ( 'edit-tags.php' === $pagenow ) {
                $submenu_file = 'edit-tags.php?taxonomy=family-group&post_type=member';
                // phpcs:ignore
            }
            
            $parent_file = 'treepress';
        }
        
        return $parent_file;
    }
    
    /**
     * Add the meta box.
     *
     * @since    1.0.0
     */
    public function add_metabox_member()
    {
        add_meta_box(
            'treepress_member_info',
            __( 'Member info', 'treepress' ),
            array( $this, 'render_metabox_member_info' ),
            'member',
            'advanced',
            'default'
        );
    }
    
    /**
     * Adds the meta box.
     *
     * @since    1.0.0
     */
    public function add_metabox_family()
    {
        add_meta_box(
            'treepress_family_info',
            __( 'Family info', 'treepress' ),
            array( $this, 'render_metabox_family_info' ),
            'family',
            'advanced',
            'default'
        );
    }
    
    /**
     * Register meta box(es).
     */
    public function add_metabox_chart()
    {
        add_meta_box(
            'treepress_chart_setting',
            __( 'Chart Settings', 'treepress' ),
            array( $this, 'render_metabox_chart_setting' ),
            'chart',
            'advanced',
            'default'
        );
    }
    
    /**
     * Renders the meta box member info.
     *
     * @param mixed $post post.
     *
     * @return mixed
     *
     * @since    1.0.0
     */
    public function render_metabox_member_info( $post )
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/treepress-admin-member-info.php';
    }
    
    /**
     * Renders the meta box member hide featured image.
     *
     * @param mixed $post post.
     *
     * @return mixed
     *
     * @since    1.0.0
     */
    public function render_metabox_member_hide_featured_image( $post )
    {
        wp_nonce_field( 'treepress_member_hide_featured_image_nonce_action', 'treepress_member_hide_featured_image_nonce' );
        $hide_featured = get_post_meta( $post->ID, '_hide_featured', true );
        ?>
		<input type="radio" name="_hide_featured" value="1" <?php 
        checked( $hide_featured, 1 );
        ?>><?php 
        esc_html_e( 'Yes', 'hide-featured-image' );
        ?>&nbsp;&nbsp;
		<input type="radio" name="_hide_featured" value="2" <?php 
        checked( $hide_featured, 2 );
        ?>><?php 
        esc_html_e( 'No', 'hide-featured-image' );
        ?>
		<?php 
    }
    
    /**
     * Renders the meta box.
     *
     * @param mixed $post post.
     *
     * @return mixed
     *
     * @since    1.0.0
     */
    public function render_metabox_family_info( $post )
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/treepress-admin-family-info.php';
    }
    
    /**
     * Tree style.
     *
     * @return mixed
     */
    public function tree_style()
    {
        return array(
            '11' => 'Individual Descendents Vertical <!-- Style 1 ( A ) Vertical Descendant (Spouses next to each other) --> ',
            '12' => 'Individual Pedigree Vertical <!-- Style 1 ( B ) Vertical Pedigree (Spouses next to each other) (Shows Ancestors)-->',
            '13' => 'Couple Ancestors Hourglass Vertical <!-- Style 1 ( C ) Vertical (Spouses next to each other) Hourglass (Each Spouse Ancestors) -->',
            '14' => 'Individual\'s Family Hourglass Vertical <!-- Style 1 ( D ) Vertical (Spouses next to each other) Hourglass 2 (Person + Spouse Ancestors & Descendants) -->',
            '21' => 'Individual Descendents Vertical ♥ <!-- Style 2 ( A ) Vertical Descendant (Inline spouse indicated by ♡) (addon) -->',
            '31' => 'Individual Descendent Vertical Text  <!-- Style 3 ( A ) Vertical Descendant (Spouses next to each other)  -->',
            '32' => 'Individual Pedigree Vertical Text  <!-- Style 3 ( B ) Vertical Pedigree (Spouses next to each other) (Shows Ancestors)  -->',
            '33' => 'Couple Ancestors Hourglass Vertical Text  <!-- Style 3 ( C ) Vertical Hourglass (Spouses next to each other) (Each Spouse Ancestors)  -->',
            '34' => 'Individual\'s Family Hourglass Text  <!-- Style 3 ( D ) Vertical Hourglass 2 (Spouses next to each other) (Person + Spouse Ancestors & Descendants)  -->',
            '41' => 'Individual Descendent Horizontal  <!-- Style 4 ( A ) Horizontal Descendant (Spouses next to each other)  -->',
            '42' => 'Individual Pedigree Horizontal <!-- Style 4 ( B ) Horizontal Pedigree (Spouses next to each other) (Shows Ancestors) (addon) -->',
            '43' => 'Couple Ancestors Hourglass Horizontal <!-- Style 4 ( C ) Horizontal Hourglass (Spouses next to each other) (Each Spouse Ancestors) (addon) -->',
            '44' => 'Individual\'s Family Hourglass Horizontal    <!-- Style 4 ( D ) Horizontal Hourglass 2 (Spouses next to each other) (Person + Spouse Ancestors & Descendants) -->',
            '51' => 'Individual Descendent Horizontal ♥ <!-- Style 5 ( A ) Horizontal Descendant (Inline spouse indicated by ♡) (addon) -->',
            '61' => 'Individual Descendent Horizontal Text  <!-- Style 6 ( A ) Horizontal Text Descendant (Compact)  -->',
            '62' => 'Individual Pedigree Horizontal Text <!-- Style 6 ( B ) Horizontal Text Pedigree (Compact) (Shows Ancestors) (addon) -->',
            '63' => 'Couple Ancestors Hourglass Horizontal Text  <!-- Style 6 ( C ) Horizontal Text Hourglass (Compact) (Each Spouse Ancestors)  -->',
            '64' => 'Individual\'s Family Hourglass Horizontal Text  <!-- Style 6 ( D ) Horizontal Text Hourglass 2 (Compact) (Person + Spouse Ancestors & Descendants)  -->',
        );
    }
    
    /**
     * Meta box display callback.
     *
     * @param WP_Post $post Current post object.
     *
     * @return mixed
     *
     * @since    1.0.0
     */
    public function render_metabox_chart_setting( $post )
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/treepress-admin-chart-settings.php';
    }
    
    /**
     * Handles saving the meta box.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     * @return null
     *
     * @since    1.0.0
     */
    public function save_metabox_member_info( $post_id, $post )
    {
        if ( !isset( $_POST['treepress_member_info_nonce'] ) ) {
            return;
        }
        // Add nonce for security and authentication.
        $nonce_name = ( isset( $_POST['treepress_member_info_nonce'] ) ? sanitize_key( wp_unslash( $_POST['treepress_member_info_nonce'] ) ) : '' );
        // Check if nonce is valid.
        if ( !wp_verify_nonce( $nonce_name, 'treepress_member_info_nonce_action' ) ) {
            return;
        }
        // Check if user has permissions to save data.
        if ( !current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }
        $member = ( isset( $_POST['treepress']['member'] ) ? map_deep( wp_unslash( $_POST['treepress']['member'] ), 'sanitize_text_field' ) : array() );
        if ( isset( $member['Id'] ) ) {
            update_post_meta( $post_id, 'Id', $member['Id'] );
        }
        delete_post_meta( $post_id, 'famc' );
        if ( isset( $member['famc'] ) && $member['famc'] ) {
            foreach ( $member['famc'] as $key => $famd ) {
                add_post_meta( $post_id, 'famc', $famd );
            }
        }
        delete_post_meta( $post_id, 'names' );
        if ( isset( $member['names'] ) && $member['names'] ) {
            foreach ( $member['names'] as $key => $name ) {
                add_post_meta( $post_id, 'names', $name );
            }
        }
        if ( isset( $member['sex'] ) ) {
            update_post_meta( $post_id, 'sex', $member['sex'] );
        }
        delete_post_meta( $post_id, 'even' );
        if ( isset( $member['even'] ) && $member['even'] ) {
            foreach ( $member['even'] as $key => $ev ) {
                add_post_meta( $post_id, 'even', $ev );
            }
        }
        delete_post_meta( $post_id, 'note' );
        if ( isset( $member['note'] ) && $member['note'] ) {
            foreach ( $member['note'] as $key => $no ) {
                add_post_meta( $post_id, 'note', $no );
            }
        }
        
        if ( class_exists( 'TreepressGallery' ) ) {
            global  $custom_meta_fields ;
            foreach ( $custom_meta_fields as $field ) {
                $new_meta_value = ( isset( $_POST['treepress_gallery_gallery'] ) ? sanitize_text_field( wp_unslash( $_POST['treepress_gallery_gallery'] ) ) : '' );
                $new_meta_caption_value = ( isset( $_POST['treepress_gallery_gallery_title'] ) ? sanitize_text_field( wp_unslash( $_POST['treepress_gallery_gallery_title'] ) ) : '' );
                $meta_key = 'treepress_gallery_gallery';
                $meta_caption_key = 'treepress_gallery_gallery_title';
                $meta_value = get_post_meta( $post_id, $meta_key, true );
                $meta_value_caption = get_post_meta( $post_id, $meta_caption_key, true );
                
                if ( $new_meta_value && $meta_value == null ) {
                    add_post_meta(
                        $post_id,
                        $meta_key,
                        $new_meta_value,
                        true
                    );
                } elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
                    update_post_meta( $post_id, $meta_key, $new_meta_value );
                } elseif ( $new_meta_value == null && $meta_value ) {
                    delete_post_meta( $post_id, $meta_key, $meta_value );
                }
                
                
                if ( $new_meta_caption_value && $meta_value_caption == null ) {
                    add_post_meta(
                        $post_id,
                        $meta_caption_key,
                        $new_meta_caption_value,
                        true
                    );
                } elseif ( $new_meta_caption_value && $new_meta_caption_value != $meta_value_caption ) {
                    update_post_meta( $post_id, $meta_caption_key, $new_meta_caption_value );
                } elseif ( $new_meta_caption_value == null && $meta_value_caption ) {
                    delete_post_meta( $post_id, $meta_caption_key, $meta_value_caption );
                }
            
            }
            $Objes = ( isset( $_POST['Obje'] ) ? $_POST['Obje'] : array() );
            
            if ( $Objes ) {
                delete_post_meta( $post_id, 'Obje' );
                foreach ( $Objes as $key => $Obje ) {
                    add_post_meta( $post_id, 'Obje', $Obje );
                }
            }
        
        }
    
    }
    
    /**
     * When the post is saved, saves our custom data.
     *
     * @param int $post_id post_id.
     *
     * @return void
     */
    public function save_metabox_member_hide_featured_image( $post_id )
    {
        // Add nonce for security and authentication.
        $nonce_name = ( isset( $_POST['treepress_member_hide_featured_image_nonce'] ) ? sanitize_key( wp_unslash( $_POST['treepress_member_hide_featured_image_nonce'] ) ) : '' );
        // Check if nonce is valid.
        if ( !wp_verify_nonce( $nonce_name, 'treepress_member_hide_featured_image_nonce_action' ) ) {
            return;
        }
        // Check if user has permissions to save data.
        if ( !current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }
        $hide_featured = ( isset( $_POST['_hide_featured'] ) && $_POST['_hide_featured'] == 1 ? '1' : sanitize_text_field( wp_unslash( $_POST['_hide_featured'] ) ) );
        update_post_meta( $post_id, '_hide_featured', $hide_featured );
    }
    
    /**
     * Handles saving the meta box.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     * @return null
     *
     * @since    1.0.0
     */
    public function save_metabox_family_info( $post_id, $post )
    {
        // Add nonce for security and authentication.
        $nonce_name = ( isset( $_POST['treepress_family_info_nonce'] ) ? sanitize_key( wp_unslash( $_POST['treepress_family_info_nonce'] ) ) : '' );
        // Check if nonce is valid.
        if ( !wp_verify_nonce( $nonce_name, 'treepress_family_info_nonce_action' ) ) {
            return;
        }
        // Check if user has permissions to save data.
        if ( !current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }
        $active_post_id = $post_id;
        $post_id_found = false;
        $family = ( isset( $_POST['treepress']['family'] ) ? map_deep( wp_unslash( $_POST['treepress']['family'] ), 'sanitize_text_field' ) : array() );
        
        if ( $family['husb'] && $family['husb'] ) {
            $args = array(
                'post_type'      => 'family',
                'posts_per_page' => '1',
                'post_status'    => array( 'publish' ),
                'meta_query'     => array(
                'relation' => 'AND',
                array(
                'key'     => 'husb',
                'value'   => $family['husb'],
                'compare' => '=',
            ),
                array(
                'key'     => 'wife',
                'value'   => $family['wife'],
                'compare' => '=',
            ),
            ),
            );
            $query = new WP_Query( $args );
            
            if ( !empty($query->posts) ) {
                $post_id = current( $query->posts )->ID;
                $post_id_found = true;
            }
        
        }
        
        
        if ( !$family['husb'] && $family['wife'] && $family['chil'] ) {
            $args = array(
                'post_type'      => 'family',
                'posts_per_page' => '1',
                'post_status'    => array( 'publish' ),
                'meta_query'     => array(
                'relation' => 'AND',
                array(
                'key'     => 'husb',
                'compare' => 'NOT EXISTS',
            ),
                array(
                'key'     => 'wife',
                'value'   => $family['wife'],
                'compare' => '=',
            ),
            ),
            );
            $query = new WP_Query( $args );
            
            if ( !empty($query->posts) ) {
                $post_id = current( $query->posts )->ID;
                $post_id_found = true;
            }
        
        }
        
        
        if ( $family['husb'] && !$family['wife'] && $family['chil'] ) {
            $args = array(
                'post_type'      => 'family',
                'posts_per_page' => '1',
                'post_status'    => array( 'publish' ),
                'meta_query'     => array(
                'relation' => 'AND',
                array(
                'key'     => 'husb',
                'value'   => $family['husb'],
                'compare' => '=',
            ),
                array(
                'key'     => 'husb',
                'compare' => 'NOT EXISTS',
            ),
            ),
            );
            $query = new WP_Query( $args );
            
            if ( !empty($query->posts) ) {
                $post_id = current( $query->posts )->ID;
                $post_id_found = true;
            }
        
        }
        
        if ( $post_id_found ) {
            if ( $active_post_id != $post_id ) {
                wp_delete_post( $active_post_id, true );
            }
        }
        if ( isset( $family['Id'] ) && !$post_id_found ) {
            update_post_meta( $post_id, 'Id', $family['Id'] );
        }
        delete_post_meta( $post_id, 'husb' );
        
        if ( isset( $family['husb'] ) ) {
            update_post_meta( $post_id, 'husb', $family['husb'] );
            $args = array(
                'post_type'      => 'member',
                'posts_per_page' => '1',
                'post_status'    => array( 'publish' ),
                'meta_query'     => array( array(
                'key'     => 'Id',
                'value'   => $family['husb'],
                'compare' => '=',
            ) ),
            );
            $query = new WP_Query( $args );
            $husb_id = current( $query->posts )->ID;
            $famss = ( get_post_meta( $husb_id, 'fams' ) ? get_post_meta( $husb_id, 'fams' ) : array() );
            $famss_fam = array();
            foreach ( $famss as $key => $value ) {
                if ( $value['fam'] ) {
                    $famss_fam[] = $value['fam'];
                }
            }
            if ( !in_array( $family['Id'], $famss_fam ) ) {
                add_post_meta( $husb_id, 'fams', array(
                    'fam' => $family['Id'],
                ) );
            }
        }
        
        delete_post_meta( $post_id, 'wife' );
        
        if ( isset( $family['wife'] ) ) {
            update_post_meta( $post_id, 'wife', $family['wife'] );
            $args = array(
                'post_type'      => 'member',
                'posts_per_page' => '1',
                'post_status'    => array( 'publish' ),
                'meta_query'     => array( array(
                'key'     => 'Id',
                'value'   => $family['wife'],
                'compare' => '=',
            ) ),
            );
            $query = new WP_Query( $args );
            $wife_id = current( $query->posts )->ID;
            $famss = ( get_post_meta( $wife_id, 'fams' ) ? get_post_meta( $wife_id, 'fams' ) : array() );
            $famss_fam = array();
            foreach ( $famss as $key => $value ) {
                if ( $value['fam'] ) {
                    $famss_fam[] = $value['fam'];
                }
            }
            if ( !in_array( $family['Id'], $famss_fam ) ) {
                add_post_meta( $wife_id, 'fams', array(
                    'fam' => $family['Id'],
                ) );
            }
        }
        
        $chil = ( get_post_meta( $post_id, 'chil' ) ? get_post_meta( $post_id, 'chil' ) : array() );
        if ( isset( $family['chil'] ) && $family['chil'] ) {
            foreach ( $family['chil'] as $key => $chi ) {
                if ( !in_array( $chi, $chil ) ) {
                    add_post_meta( $post_id, 'chil', $chi );
                }
                $args = array(
                    'post_type'      => 'member',
                    'posts_per_page' => '1',
                    'post_status'    => array( 'publish' ),
                    'meta_query'     => array( array(
                    'key'     => 'Id',
                    'value'   => $chi,
                    'compare' => '=',
                ) ),
                );
                $query = new WP_Query( $args );
                $chi_id = current( $query->posts )->ID;
                $famcs = ( get_post_meta( $chi_id, 'famc' ) ? get_post_meta( $chi_id, 'famc' ) : array() );
                $famcs_fam = array();
                foreach ( $famcs as $key => $value ) {
                    if ( $value['fam'] ) {
                        $famcs_fam[] = $value['fam'];
                    }
                }
                if ( !in_array( $family['Id'], $famcs_fam ) ) {
                    add_post_meta( $chi_id, 'famc', array(
                        'fam' => $family['Id'],
                    ) );
                }
            }
        }
        delete_post_meta( $post_id, 'even' );
        if ( isset( $family['even'] ) && $family['even'] ) {
            foreach ( $family['even'] as $key => $ev ) {
                add_post_meta( $post_id, 'even', $ev );
            }
        }
        delete_post_meta( $post_id, 'note' );
        if ( isset( $family['note'] ) && $family['note'] ) {
            foreach ( $family['note'] as $key => $no ) {
                add_post_meta( $post_id, 'note', $no );
            }
        }
        $husb = get_post_meta( $post_id, 'husb', true );
        $wife = get_post_meta( $post_id, 'wife', true );
        $husb_title = '';
        $wife_title = '';
        
        if ( $husb ) {
            $husb_id = $this->get_member_id( $husb );
            $husb_title = get_the_title( $husb_id );
        }
        
        
        if ( $wife ) {
            $wife_id = $this->get_member_id( $wife );
            $wife_title = get_the_title( $wife_id );
        }
        
        $title = ( $husb_title ? $husb_title : '' );
        $title .= ( $husb_title && $wife_title ? ' and ' : '' );
        $title .= ( $wife_title ? $wife_title : '' );
        remove_action( 'save_post', array( $this, 'save_metabox_family_info' ) );
        wp_update_post( array(
            'ID'         => $post_id,
            'post_title' => $title,
        ) );
        add_action( 'save_post', array( $this, 'save_metabox_family_info' ) );
        wp_safe_redirect( get_edit_post_link( $post_id, '' ) );
        exit;
    }
    
    /**
     * Save meta box content.
     *
     * @param int $post_id Post ID.
     */
    public function save_metabox_chart_setting( $post_id )
    {
        if ( !isset( $_POST['_nonce_chart'] ) ) {
            return $post_id;
        }
        $nonce = sanitize_key( wp_unslash( $_POST['_nonce_chart'] ) );
        if ( !wp_verify_nonce( $nonce, 'nonce_chart' ) ) {
            return $post_id;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        
        if ( isset( $_POST['chart'] ) ) {
            $default_chart = $this->default_tree_meta();
            $chart = map_deep( wp_unslash( $_POST['chart'] ), 'sanitize_text_field' );
            $chart = $this->merge_chart( $default_chart, $chart, false );
            delete_post_meta( $post_id, 'chart' );
            $chart = update_post_meta( $post_id, 'chart', $chart );
        }
    
    }
    
    /**
     * Chart columns.
     *
     * @param  array $columns columns.
     *
     * @return array
     */
    public function chart_columns( $columns )
    {
        $columns['type'] = __( 'Type', 'treepress' );
        $columns['family-group'] = __( 'Family Group', 'treepress' );
        $columns['root-member'] = __( 'Root Person', 'treepress' );
        $columns['date'] = __( 'Date', 'treepress' );
        $columns['short-code'] = __( 'Shortcode', 'treepress' );
        return $columns;
    }
    
    /**
     * Manage chart post columns.
     *
     * @param  string $column column.
     * @param  int    $post_id post_id.
     *
     * @return void
     */
    public function manage_chart_posts_custom_column( $column, $post_id )
    {
        $chart = get_post_meta( $post_id, 'chart', true );
        $chart_type = ( isset( $chart['chart_type'] ) ? $chart['chart_type'] : '' );
        $group_id = ( isset( $chart['group_id'] ) ? $chart['group_id'] : '' );
        $root_id = ( isset( $chart['root_id'] ) ? $chart['root_id'] : '' );
        switch ( $column ) {
            case 'type':
                echo  esc_html( $chart_type ) . ' - ' . esc_html( $this->tree_style()[$chart_type] ) ;
                break;
            case 'family-group':
                echo  esc_html( $group_id ) . ' - ' . esc_html( get_term( $group_id )->name ) ;
                break;
            case 'root-member':
                $name = ( get_post_meta( $root_id, 'names', true ) ? get_post_meta( $root_id, 'names', true ) : array(
                    'name' => '',
                ) );
                echo  esc_html( $root_id ) . ' - ' . esc_html( $name['name'] ) ;
                break;
            case 'generation':
                echo  esc_html( 'generation' ) ;
                break;
            case 'short-code':
                echo  '[Chart ID=' . esc_html( $post_id ) . ']' ;
                break;
        }
    }
    
    /**
     * Member clomn
     *
     * @param  array $columns columns.
     *
     * @return array
     */
    public function member_columns( $columns )
    {
        $columns['ID'] = __( 'ID', 'treepress' );
        $columns['born'] = __( 'Born', 'treepress' );
        $columns['title'] = __( 'Name', 'treepress' );
        $columns['father'] = __( 'Father', 'treepress' );
        $columns['mother'] = __( 'Mother', 'treepress' );
        return $columns;
    }
    
    /**
     * Manage member posts custom column.
     *
     * @param  string $column column.
     * @param  int    $post_id post_id.
     *
     * @return void
     */
    public function manage_member_posts_custom_column( $column, $post_id )
    {
        $famc = ( get_post_meta( $post_id, 'famc' ) ? current( get_post_meta( $post_id, 'famc' ) ) : array() );
        
        if ( !empty($famc) ) {
            $family_id = $famc['fam'];
            $family_id = $this->get_family_id( $famc['fam'] );
            $father_id = ( get_post_meta( $family_id, 'husb', true ) ? get_post_meta( $family_id, 'husb', true ) : null );
            $father_id = $this->get_member_id( $father_id );
            $mother_id = ( get_post_meta( $family_id, 'wife', true ) ? get_post_meta( $family_id, 'wife', true ) : null );
            $mother_id = $this->get_member_id( $mother_id );
        }
        
        switch ( $column ) {
            case 'father':
                
                if ( isset( $father_id ) && $father_id ) {
                    $name = ( get_post_meta( $father_id, 'names', true ) ? get_post_meta( $father_id, 'names', true ) : array(
                        'name' => '',
                    ) );
                    echo  '<a href="' . esc_attr( get_edit_post_link( $father_id ) ) . '">' . esc_html( $name['name'] ) . '</a>' ;
                }
                
                break;
            case 'mother':
                
                if ( isset( $mother_id ) && $mother_id ) {
                    $name = ( get_post_meta( $mother_id, 'names', true ) ? get_post_meta( $mother_id, 'names', true ) : array(
                        'name' => '',
                    ) );
                    echo  '<a href="' . esc_attr( get_edit_post_link( $mother_id ) ) . '">' . esc_html( $name['name'] ) . '</a>' ;
                }
                
                break;
            case 'born':
                $even = ( get_post_meta( $post_id, 'even' ) ? get_post_meta( $post_id, 'even' ) : array() );
                
                if ( !empty($even) ) {
                    $found_birt = false;
                    foreach ( $even as $key => $event ) {
                        
                        if ( 'BIRT' === $event['type'] && false === $found_birt ) {
                            $birt = $event;
                            $found_birt = true;
                        }
                    
                    }
                }
                
                if ( isset( $birt ) && $birt ) {
                    echo  esc_html( $birt['date'] ) ;
                }
                break;
            case 'ID':
                echo  esc_html( $post_id ) ;
                break;
        }
    }
    
    /**
     * Sortable columns on member listing page.
     *
     * @param array $columns columns.
     *
     * @return array
     */
    public function member_sortable_columns( $columns )
    {
        $columns['taxonomy-family'] = 'family-group';
        return $columns;
    }
    
    /**
     * Function for `member_sortable_columns_pre_get_posts`
     *
     * @param  object $query query.
     *
     * @return void
     */
    public function member_sortable_columns_pre_get_posts( $query )
    {
        $orderby = $query->get( 'orderby' );
        
        if ( 'family' == $orderby ) {
            $query->set( 'tax_query', array(
                'taxonomy' => 'family-group',
            ) );
            $query->set( 'orderby', 'meta_value' );
        }
    
    }
    
    /**
     * Add a custom column to family group listing page
     * so user can get each group id easyly.
     *
     * @param array $columns columns.
     *
     * @return array
     */
    public function family_group_columns( $columns )
    {
        $columns['ID'] = __( 'ID', 'treepress' );
        return $columns;
    }
    
    /**
     * Display value to aditional column
     * on family group listion page.
     *
     * @param  string $content content.
     * @param  string $column_name column_name.
     * @param  int    $term_id term_id.
     *
     * @return string
     */
    public function manage_family_group_custom_column( $content, $column_name, $term_id )
    {
        switch ( $column_name ) {
            case 'ID':
                $content = '<code>' . $term_id . '</code>';
                break;
        }
        return $content;
    }
    
    /**
     * Aditional fields on family group edit page to store default tree link and members page link.
     *
     * @param  object $term term.
     *
     * @return void
     */
    public function family_group_edit_form_fields( $term )
    {
        $family_tree_link = get_term_meta( $term->term_id, 'family_tree_link', true );
        $family_members_link = get_term_meta( $term->term_id, 'family_members_link', true );
        ?>
		<?php 
        wp_nonce_field( 'family_nonce_action', 'family_nonce' );
        ?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="presenter_id"><?php 
        esc_html_e( 'Family tree link', 'treepress' );
        ?></label>
			</th>
			<td>
				<input type="text" name="family_tree_link" id="family_tree_link" size="25" style="width:60%;" value="<?php 
        echo  esc_attr( $family_tree_link ) ;
        ?>"><br />
				<span class="description"><?php 
        esc_html_e( 'Family tree link', 'treepress' );
        ?></span>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="presenter_id"><?php 
        esc_html_e( 'Family members link', 'treepress' );
        ?></label>
			</th>
			<td>
				<input type="text" name="family_members_link" id="family_members_link" size="25" style="width:60%;" value="<?php 
        echo  esc_attr( $family_members_link ) ;
        ?>"><br />
				<span class="description"><?php 
        esc_html_e( 'Family members link', 'treepress' );
        ?></span>
			</td>
		</tr>
		<?php 
    }
    
    /**
     * Update value of aditional filelds of family group.
     *
     * @param  int $term_id term_id.
     *
     * @return void
     */
    public function save_family_group_custom_fields( $term_id )
    {
        
        if ( isset( $_POST['family_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['family_nonce'] ) ), 'family_nonce_action' ) ) {
            
            if ( isset( $_POST['family_tree_link'] ) ) {
                $family_tree_link = sanitize_text_field( wp_unslash( $_POST['family_tree_link'] ) );
                update_term_meta( $term_id, 'family_tree_link', $family_tree_link );
            }
            
            
            if ( isset( $_POST['family_members_link'] ) ) {
                $family_members_link = sanitize_text_field( wp_unslash( $_POST['family_members_link'] ) );
                update_term_meta( $term_id, 'family_members_link', $family_members_link );
            }
        
        }
    
    }
    
    /**
     * TreePress menu page callback function.
     *
     * @since    1.0.0
     */
    public function treepress_page()
    {
    }
    
    /**
     * Submenu page callback function
     * for acccount setting page.
     *
     * @since    1.0.0
     */
    public function options_panel()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/treepress-admin-options.php';
    }
    
    /**
     * Submenu page callback function
     * for acccount setting page.
     *
     * @since    1.0.0
     */
    public function account_settings()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/treepress-admin-account.php';
    }
    
    /**
     * Submenu page callback function
     * for acccount setting page.
     *
     * @since    1.0.0
     */
    public function treepress_import()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/treepress-admin-import.php';
    }
    
    /**
     * Submenu page callback function
     * for acccount setting page.
     *
     * @since    1.0.0
     */
    public function treepress_export()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/treepress-admin-export.php';
    }
    
    /**
     * Function for `member_change_title_text`
     *
     * @param  string $title title.
     *
     * @return string
     */
    public function member_change_title_text( $title )
    {
        $screen = get_current_screen();
        if ( 'member' === $screen->post_type ) {
            $title = __( 'Enter name here', 'treepress' );
        }
        return $title;
    }
    
    /**
     * Run when family-group create.
     *
     * If user is not paying we will allow to create only one family group.
     *
     * @param  int $term_id term_id.
     *
     * @return void
     */
    public function create_family_free( $term_id )
    {
        // Check if user paying or not.
        
        if ( tre_fs()->is_not_paying() ) {
            $terms = get_terms( array(
                'taxonomy'   => 'family-group',
                'hide_empty' => false,
            ) );
            // Chcek if current number of family group are more than our limit.
            
            if ( count( $terms ) > 1 ) {
                // Delete created family group.
                wp_delete_term( $term_id, 'family' );
                echo  '<section>' ;
                echo  '<strong>' . esc_html__( 'You are not allowed to create more than one family tree on free version. ', 'treepress' ) . '</strong>' ;
                echo  '<br>' ;
                echo  '<a href="' . esc_attr( tre_fs()->get_upgrade_url() ) . '">' . esc_html__( 'Upgrade Now!', 'treepress' ) . '</a>' ;
                echo  '</section>' ;
                die;
            }
        
        }
    
    }
    
    /**
     * Unlink child or spouse from family and disconnect member from that family.
     *
     * @return void
     */
    public function unlink_function()
    {
        // Return if nonce field exist.
        if ( !isset( $_POST['nonce'] ) ) {
            return;
        }
        $nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
        // return if verify not success.
        if ( !wp_verify_nonce( $nonce, 'tpajax-nonce' ) ) {
            return;
        }
        $post_id = ( isset( $_POST['post_id'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) : '' );
        $key = ( isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '' );
        $family_id = ( isset( $_POST['family_id'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['family_id'] ) ) : '' );
        $member_id = ( isset( $_POST['member_id'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['member_id'] ) ) : '' );
        $get_family_id = $this->get_family_id( $family_id );
        // If requested type is unlink child.
        
        if ( 'chil' === $key ) {
            // Remove child from family.
            delete_post_meta( $get_family_id, 'chil', $member_id );
            // Disconnect member from family.
            $famc = ( get_post_meta( $post_id, 'famc' ) ? get_post_meta( $post_id, 'famc' ) : array() );
            
            if ( !empty($famc) ) {
                foreach ( $famc as $keyx => $fam ) {
                    if ( (int) $fam['fam'] === $family_id ) {
                        unset( $famc[$keyx] );
                    }
                }
                delete_post_meta( $post_id, 'famc' );
                foreach ( $famc as $keyx => $fam ) {
                    add_post_meta( $post_id, 'famc', $fam );
                }
            }
        
        }
        
        // If requested type is unlink spouse.
        
        if ( 'spouse' === $key ) {
            // Remove spouse from family.
            delete_post_meta( $get_family_id, 'wife', $member_id );
            delete_post_meta( $get_family_id, 'husb', $member_id );
            // Disconnect member from family.
            $fams = ( get_post_meta( $post_id, 'fams' ) ? get_post_meta( $post_id, 'fams' ) : array() );
            
            if ( !empty($fams) ) {
                foreach ( $fams as $keyy => $famd ) {
                    if ( (int) $famd['fam'] === $family_id ) {
                        unset( $fams[$keyy] );
                    }
                }
                delete_post_meta( $post_id, 'fams' );
                foreach ( $fams as $keyy => $famd ) {
                    add_post_meta( $post_id, 'fams', $famd );
                }
            }
            
            // Delete family if there is no child, as there is only husband or only wife.
            $chil = ( get_post_meta( $get_family_id, 'chil' ) ? get_post_meta( $get_family_id, 'chil' ) : array() );
            if ( empty($chil) ) {
                if ( !(get_post_meta( $get_family_id, 'wife', true ) && get_post_meta( $get_family_id, 'husb', true )) ) {
                    wp_delete_post( $get_family_id, true );
                }
            }
        }
        
        die;
    }
    
    /**
     * Member short details name.
     *
     * @param  int $id id.
     *
     * @return void
     */
    public function short_details_name( $id )
    {
        $ref_id = ( get_post_meta( $id, 'Id', true ) ? get_post_meta( $id, 'Id', true ) : null );
        $even = ( get_post_meta( $id, 'even' ) ? get_post_meta( $id, 'even' ) : array() );
        $birt = array(
            'date' => '',
        );
        $found_birt = false;
        foreach ( $even as $key => $event ) {
            
            if ( 'BIRT' === $event['type'] && false === $found_birt ) {
                $birt = $event;
                $found_birt = true;
            }
        
        }
        $deat = array(
            'date' => '',
        );
        $found_deat = false;
        foreach ( $even as $key => $event ) {
            
            if ( 'DEAT' === $event['type'] && false === $found_deat ) {
                $deat = $event;
                $found_deat = true;
            }
        
        }
        $x = '';
        $t = '';
        if ( $birt['date'] ) {
            $t = 'b:' . $birt['date'];
        }
        if ( $birt['date'] && $deat['date'] ) {
            $t .= ',';
        }
        if ( $deat['date'] ) {
            $t .= 'd:' . $deat['date'];
        }
        if ( $t ) {
            $x = '(' . $t . ')';
        }
        $name = ( get_post_meta( $id, 'names' ) ? get_post_meta( $id, 'names' )[0] : array(
            'name' => '',
            'npfx' => '',
            'givn' => '',
            'nick' => '',
            'spfx' => '',
            'surn' => '',
            'nsfx' => '',
        ) );
        echo  esc_html( $id ) . ' - ' . esc_html( $name['name'] ) . ' ' . esc_html( $x ) ;
    }
    
    /**
     * Get member post id.
     *
     * @param  int $ref_id ref_id.
     *
     * @return mixed
     */
    public function get_member_id( $ref_id )
    {
        if ( !$ref_id ) {
            return null;
        }
        $args = array(
            'post_type'      => 'member',
            'posts_per_page' => 1,
            'meta_query'     => array( array(
            'key'     => 'Id',
            'value'   => $ref_id,
            'compare' => '=',
        ) ),
        );
        $query = new WP_Query( $args );
        
        if ( !empty($query->posts) ) {
            $member_id = current( $query->posts )->ID;
            return $member_id;
        }
        
        return null;
    }
    
    /**
     * Get family post id.
     *
     * @param  int $ref_id ref_id.
     *
     * @return mixed
     */
    public function get_family_id( $ref_id )
    {
        if ( !$ref_id ) {
            return null;
        }
        $args = array(
            'post_type'      => 'family',
            'posts_per_page' => 1,
            'meta_query'     => array( array(
            'key'     => 'Id',
            'value'   => $ref_id,
            'compare' => '=',
        ) ),
        );
        $query = new WP_Query( $args );
        
        if ( !empty($query->posts) ) {
            $family_id = current( $query->posts )->ID;
            return $family_id;
        }
        
        return null;
    }
    
    /**
     * Default tree meta.
     **/
    public function default_tree_meta()
    {
        return array(
            'privacy'            => array(
            'show_living_dates'    => '',
            'show_spouse'          => 'on',
            'show_only_one_spouse' => 'on',
            'show_gender'          => 'on',
        ),
            'align'              => 'on',
            'node_opacity'       => '1',
            'node_minimum_width' => '150px',
            'height_generations' => '10px',
            'chart_type'         => '2',
            'group_id'           => '',
            'root_id'            => '',
            'image_bg'           => array(
            'show'   => '',
            'size'   => '',
            'repeat' => '',
        ),
            'bg_color'           => '#eff4ff',
            'box'                => array(
            'show'             => 'on',
            'border'           => array(
            'style'  => 'solid',
            'weight' => '1px',
            'radius' => '5px',
            'color'  => array(
            'male'   => '#6d97bf',
            'female' => '#ddad66',
            'other'  => '#ffffff',
        ),
        ),
            'bg_color'         => array(
            'male'   => '#0066bf',
            'female' => '#dd8500',
            'other'  => '#ffffff',
        ),
            'enable_toolbar'   => 'on',
            'single_page_link' => 'on',
            'tree_nav_link'    => 'on',
            'padding'          => '4px',
        ),
            'line'               => array(
            'show'   => 'on',
            'style'  => 'solid',
            'weight' => '1px',
            'color'  => '#0024f2',
        ),
            'thumb'              => array(
            'show'          => 'on',
            'border_style'  => 'dotted',
            'border_weight' => '1px',
            'border_radius' => '4px',
            'margin'        => '4px',
        ),
            'name'               => array(
            'font'             => 'Arial',
            'font_size'        => '12px',
            'font_color'       => '#222222',
            'font_other'       => 'Arial',
            'font_other_size'  => '10px',
            'font_other_color' => '#222222',
        ),
            'name_format'        => 'full',
            'dob_format'         => 'full',
            'dod_format'         => 'full',
            'dom_format'         => 'full',
            'wrap_names'         => 'on',
            'date_birth_prefix'  => 'b.',
            'death_date_prefix'  => 'd.',
        );
    }
    
    /**
     * It merges two arrays, but if the value is an array, it recursively calls itself to merge the arrays
     *
     * @param array   $base     The base array to merge into.
     * @param array   $input    The input array to be merged.
     * @param boolean $default  true/false - if true, the default values will be used if the input is empty. If
     * false, the default values will be ignored.
     *
     * @return array The array that will be returned.
     */
    public function merge_chart( $base, $input, $default = true )
    {
        if ( empty($tree) && true === $default ) {
            return $base;
        }
        foreach ( $base as $key => $value ) {
            
            if ( is_array( $value ) ) {
                $base[$key] = $this->merge_chart( $value, ( $input[$key] ? $input[$key] : array() ) );
            } else {
                $base[$key] = ( $input[$key] ? $input[$key] : '' );
            }
        
        }
        return $base;
    }
    
    /**
     * Donation link. added on plugins listing page
     *
     * @param  array $links links.
     *
     * @return array
     */
    public function treepress_paypal_link( $links )
    {
        $links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RSXSRDQ7HANFQ&source=in-plugin-donate-link" target="_blank">' . esc_html__( 'Donate', 'treepress' ) . '</a>';
        return $links;
    }

}