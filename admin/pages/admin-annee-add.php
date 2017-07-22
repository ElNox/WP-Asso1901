<div class="wrap">
  <?php
    if ( isset( $_GET['m'] ) && $_GET['m'] == '1' )
    {
  ?>
     <div id='message' class='updated fade'><p><strong>Vous avez ajouté une nouvelle année.</strong></p></div>
  <?php
    }
  ?>
    <h2>Ajouter une nouvelle année</h2>

<?php  $options = get_option( 'jk_op_array' ); ?>
  <form method="post"  action="<?php echo esc_url( admin_url('admin-post.php') );?>" autocomplete="off" accept-charset="UTF-8" onsubmit="validate_form()">
    <input type="hidden" name="action" value="asso1901_add_user" />
    <?php wp_nonce_field( 'asso1901_op_verify' ); ?>
    <table class="form-table">
          <tr class="form-field form-required">
                  <th scope="row"><label for="titre">Titre</label></th>
                  <td><input id="titre" name="titre" type="text" required id="addannee-titre" class="wp-suggest-user" value="" /></td>
          </tr>
          <tr class="form-field form-required">
                  <th scope="row"><label for="date_ag">Date d'AG</label></th>
                  <td><input id="date_ag" name="date_ag" type="text" required id="addannee-date_ag" class="wp-suggest-user" value="" /></td>
          </tr>
          <tr class="form-field form-required">
                  <th scope="row"><label for="date_debut">Date début</label></th>
                  <td><input id="date_debut" name="date_debut" type="date" required id="addannee-date_debut" class="wp-suggest-user" value="" /></td>
          </tr>
          <tr class="form-field">
                  <th scope="row"><label for="date_fin">Date fin</label></th>
                  <td><input id="date_fin" name="date_fin" type="date" id="addannee-date_fin" class="wp-suggest-user" value="" /></td>
          </tr>
    </table>
    <input type="submit" value="Ajouter nouvelle année" class="button-primary"/>
  </form>

</div>
