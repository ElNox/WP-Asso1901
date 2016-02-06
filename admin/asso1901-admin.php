<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Asso1901
 * @subpackage Asso1901/admin
 * @author     Loïc Carney <elnox04@gmail.com>
 */
class Asso1901_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'admin_menu', array($this,'asso1901_main_menu') );
		include_once plugin_dir_path( __FILE__ ).'partials/asso1901-user-meta.php';
		new Asso1901_UserMeta();
		include_once plugin_dir_path( __FILE__ ).'partials/asso1901-settings.php';
		new Asso1901_Settings();
		add_filter('set-screen-option', array($this,'adherents_table_set_option') , 10, 3);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Asso1901_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Asso1901_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/asso1901-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Asso1901_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Asso1901_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/asso1901-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function asso1901_main_menu_page() {
			include('pages/admin-home.php');
	}

	public function asso1901_adherents_page() {
			include('pages/admin-adherents.php');
	}

	public function asso1901_contrats_page() {
			include('pages/admin-contrats.php');
	}

	function asso1901_main_menu() {
		//add an item to the menu
		add_menu_page (
				get_option("asso1901-settings")['asso1901-name'],
				get_option("asso1901-settings")['asso1901-name'],
				'manage_options',
				'asso1901/admin-home.php',
				array($this,'asso1901_main_menu_page')
		);

		global $hook_adherents;
		$hook_adherents = add_submenu_page(
		  'asso1901/admin-home.php',
			'Les adhérents',
			'Les adhérents',
			'manage_options',
			'asso1901/admin-adherents.php',
			array($this,'asso1901_adherents_page')
		);
		add_action( "load-$hook_adherents", array($this, 'adherents_add_options') );
		add_action( "load-$hook_adherents", array($this, 'adherents_add_help_tab') );


		add_submenu_page(
		  'asso1901/admin-home.php',
			'Les contrats',
			'Les contrats',
			'manage_options',
			'asso1901/admin-contrats.php',
			array($this,'asso1901_contrats_page')
		);
	}

	function adherents_add_options() {
		global $hook_adherents;
		$screen = get_current_screen();
		// get out of here if we are not on our settings page
		if(!is_object($screen))
        return;
    switch($screen->id) :
	    case $hook_adherents :
			  $args = array(
			         'label' => 'Adhérents',
			         'default' => 20,
			         'option' => 'adherents_per_page'
			         );
	 		add_screen_option( 'per_page', $args );
			break;
		endswitch;

	}
	function adherents_table_set_option($status, $option, $value) {
		if ( 'adherents_per_page' == $option ) {
			return $value;
		}
		return $status;
	}

function adherents_add_help_tab () {
    $screen = get_current_screen();

    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'my_help_tab',
        'title'	=> __('My Help Tab'),
        'content'	=> '<p>' . __( 'Descriptive content that will show in My Help Tab-body goes here.' ) . '</p>',
    ) );
}




}
