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
class Asso1901_UserMeta {

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct() {
    include_once(ABSPATH.'wp-includes/option.php');
    add_action( 'show_user_profile', array($this,'asso1901_extra_user_profile_fields'));
		add_action( 'edit_user_profile', array($this,'asso1901_extra_user_profile_fields' ));
		add_action( 'personal_options_update', array($this,'asso1901_save_extra_user_profile_fields' ));
		add_action( 'edit_user_profile_update', array($this,'asso1901_save_extra_user_profile_fields' ));
  }

  function asso1901_save_extra_user_profile_fields( $user_id ) {
    if(!current_user_can( 'edit_user', $user_id )) {
       return false;
    }
    update_user_meta( $user_id, 'asso1901-adresse', $_POST['asso1901-adresse'] );
    update_user_meta( $user_id, 'asso1901-telephone', $_POST['asso1901-telephone'] );
  }

  function asso1901_extra_user_profile_fields( $user ) {
    ?>
    <h3><?= get_option("asso1901-settings")['asso1901-name']; ?></h3>

    <table class="form-table">
      <tr>
        <th><label for="asso1901-adresse">Adresse</label></th>
        <td>
        <textarea id="asso1901-adresse" name="asso1901-adresse" size="200"><?php echo esc_attr( get_the_author_meta('asso1901-adresse', $user->ID )); ?></textarea>
        <span class="description">Saisissez votre adresse postale</span>
        </td>
      </tr>
      <tr>
        <th><label for="asso1901-telephone">Téléphone</label></th>
        <td>
        <input type="text" id="asso1901-telephone" name="asso1901-telephone" size="20" value="<?php echo esc_attr( get_the_author_meta('asso1901-telephone', $user->ID )); ?>">
        <span class="description">Saisissez votre numéro de téléphone</span>
        </td>
      </tr>
    </table>
    <?php
   }
 }?>
