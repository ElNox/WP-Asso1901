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
		add_action( "admin_post_asso1901_add_user", array($this, 'process_user_add') );
		add_action( "admin_post_asso1901_add_annee", array($this, 'process_annee_add') );

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

	public function asso1901_adherent_add_page() {
			include('pages/admin-adherent-add.php');
	}

	public function asso1901_annee_adhesion(){
			include('pages/admin-annee-adhesion.php');
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
			"Gérer les années",
			"Gérer les années",
			"manage_options",
			"asso1901/annee-adhesion-page.php",
			array($this,'asso1901_annee_adhesion')
		);

		add_submenu_page(
			'asso1901/admin-adherents.php',
			"Ajouter un adhérent",
			"Ajouter un adhérent",
			"manage_options",
			"asso1901/adherent-add-page.php",
			array($this,'asso1901_adherent_add_page')
		);
	}

	function process_user_add() {
		 if ( !current_user_can( 'manage_options' ) )
		 {
				wp_die( 'You are not allowed to be on this page.' );
		 }
		 // Check that nonce field
		 check_admin_referer( 'asso1901_op_verify' );

		 if ( isset( $_POST['first_name'] ) )
		 {
			 	$first_name = sanitize_text_field( $_POST['first_name']);
				$last_name = sanitize_text_field( $_POST['last_name']);
				$user_email = sanitize_text_field( $_POST['user_email']);

			 	$user_id = username_exists( $user_email );
				if ( !$user_id and email_exists($user_email) == false ) {
					$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );

					$userdata = array(
					    'user_login'  =>  $user_email,
							'first_name'  =>  $first_name,
							'last_name'  =>  $last_name,
							'nickname'	=> preg_replace('/\s+/', '', $first_name.".".$last_name),
							'display_name' => $first_name." ".ucfirst(substr($last_name, 0,1)).".",
					    'user_email'    =>  $user_email,
					    'user_pass'   =>  $random_password
					);

					$user_id = wp_insert_user( $userdata );
					update_user_meta( $user_id, 'asso1901-telephone', sanitize_text_field($_POST['asso1901-telephone']) );
					update_user_meta( $user_id, 'asso1901-adresse', sanitize_text_field( $_POST['asso1901-adresse']) );
					update_user_meta( $user_id, 'asso1901-type-adhesion', sanitize_text_field($_POST['asso1901-type-adhesion']) );
					update_user_meta( $user_id, 'asso1901-adhesion', date("Y") );
				} else {
					$random_password = __('User already exists.  Password inherited.');
				}

		 }

		 wp_redirect(  admin_url( 'admin.php?page=asso1901/adherent-add-page.php&m=1' ) );
		 exit;
	}

	function titre_exists($titre){
		return false;
	}

	function process_annee_add() {
		 if ( !current_user_can( 'manage_options' ) )
		 {
				wp_die( 'You are not allowed to be on this page.' );
		 }
		 // Check that nonce field
		 check_admin_referer( 'asso1901_op_verify' );

		 if ( isset( $_POST['titre'] ) )
		 {
			 	$titre = sanitize_text_field( $_POST['titre']);
				$dt = \DateTime::createFromFormat('d/m/Y', sanitize_text_field( $_POST['date_ag']));
				$date_ag = $dt->format('Y-m-d');
				$dt = \DateTime::createFromFormat('d/m/Y', sanitize_text_field( $_POST['date_debut']));
				$date_debut = $dt->format('Y-m-d');
				$dt = \DateTime::createFromFormat('d/m/Y', sanitize_text_field( $_POST['date_fin']));
				$date_fin = $dt->format('Y-m-d');

				if ( !$this->titre_exists( $titre ) ) {
					$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );

					$anneedata = array(
					    'titre'  =>  $titre,
							'date_ag'  =>  $date_ag,
							'date_debut'  =>  $date_debut,
							'date_fin'  =>  $date_fin,
					);

					global $wpdb;
					$wpdb->insert($wpdb->prefix . 'asso1901_annee_adhesion',
						$anneedata,
						array('%s','%s','%s','%s')
					);
				} else {
					wp_redirect(  admin_url( 'admin.php?page=asso1901/annee-adhesion-page.php&e=1' ) );
				}

		 }

		 wp_redirect(  admin_url( 'admin.php?page=asso1901/annee-adhesion-page.php&m=1' ) );
		 exit;
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
        'title'	=> __('Gestion des adhérents'),
        'content'	=> '<p>' . __( 'Ici, on peut gérer les adhérents de l\'association.' ) . '</p>',
    ) );
}




}
