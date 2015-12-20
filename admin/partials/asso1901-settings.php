<?php
class Asso1901_Settings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin',
            'Paramétrage de Asso1901',
            'manage_options',
            'asso1901-setting-admin',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'asso1901-settings' );
        ?>
        <div class="wrap">
            <h2>Paramètres</h2>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'asso1901_settings_group' );
                do_settings_sections( 'asso1901-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'asso1901_settings_group', // Option group
            'asso1901-settings', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Personnalisation du plugin', // Title
            array( $this, 'print_section_info' ), // Callback
            'asso1901-setting-admin' // Page
        );

        add_settings_field(
            'asso1901-name', // ID
            'Nom de l\'association', // Title
            array( $this, 'asso1901_name_callback' ), // Callback
            'asso1901-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'asso1901-title',
            'Title',
            array( $this, 'asso1901_title_callback' ),
            'asso1901-setting-admin',
            'setting_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['asso1901-name'] ) )
            $new_input['asso1901-name'] = sanitize_text_field( $input['asso1901-name'] );

        if( isset( $input['asso1901-title'] ) )
            $new_input['asso1901-title'] = sanitize_text_field( $input['asso1901-title'] );

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Saissiez les paramètres :';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function asso1901_name_callback()
    {
        printf(
            '<input type="text" id="asso1901-name" name="asso1901-settings[asso1901-name]" value="%s" />',
            isset( $this->options['asso1901-name'] ) ? esc_attr( $this->options['asso1901-name']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function asso1901_title_callback()
    {
        printf(
            '<input type="text" id="asso1901-title" name="asso1901-settings[asso1901-title]" value="%s" />',
            isset( $this->options['asso1901-title'] ) ? esc_attr( $this->options['asso1901-title']) : ''
        );
    }
}

?>
