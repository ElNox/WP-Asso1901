<div class="wrap">
  <?php
    if ( isset( $_GET['m'] ) && $_GET['m'] == '1' )
    {
  ?>
     <div id='message' class='updated fade'><p><strong>Vous avez ajouté un nouvel adhérent.</strong></p></div>
  <?php
    }
  ?>
    <h2>Ajouter un nouvel adhérent</h2>

<?php  $options = get_option( 'jk_op_array' ); ?>
  <form method="post"  action="<?php echo esc_url( admin_url('admin-post.php') );?>" autocomplete="off" accept-charset="UTF-8" onsubmit="validate_form()">
    <input type="hidden" name="action" value="asso1901_add_user" />
    <?php wp_nonce_field( 'asso1901_op_verify' ); ?>
    <table class="form-table">
          <tr class="form-field form-required">
                  <th scope="row"><label for="first_name">Prénom</label></th>
                  <td><input id="first_name" name="first_name" type="text" required id="adduser-first_name" class="wp-suggest-user" value="" /></td>
          </tr>
          <tr class="form-field form-required">
                  <th scope="row"><label for="last_name">Nom</label></th>
                  <td><input id="last_name" name="last_name" type="text" required id="adduser-last_name" class="wp-suggest-user" value="" /></td>
          </tr>
          <tr class="form-field form-required">
                  <th scope="row"><label for="user_email">Mail</label></th>
                  <td><input id="user_email" name="user_email" type="email" required id="adduser-email" class="wp-suggest-user" value="" /></td>
          </tr>
          <tr class="form-field form-required">
                  <th scope="row"><label for="asso1901-telephone">Téléphone</label></th>
                  <td>
                    <input id="asso1901-telephone" name="asso1901-telephone" type="tel" required pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" id="adduser-asso1901-telephone" class="wp-suggest-user" value="" />
                    <span class="error" aria-live="polite"></span>
                  </td>
          </tr>
          <tr class="form-field form-required">
                  <th scope="row"><label for="asso1901-adresse">Adresse</label></th>
                  <td><textarea id="asso1901-adresse" name="asso1901-adresse" required id="adduser-asso1901-adresse" class="wp-suggest-user" value="" ></textarea></td>
          </tr>
          <tr class="form-field">
                  <th scope="row"><label for="asso1901-type-adhesion">Type d'adhésion</label></th>
                  <td>
                    <input type="radio" name="asso1901-type-adhesion" value="Acteur" checked> Acteur
                    <input type="radio" name="asso1901-type-adhesion" value="Intermittent"> Intermittent
                    <input type="radio" name="asso1901-type-adhesion" value="Producteur"> Producteur
                  </td>
          </tr>
          <tr>
              <th scope="row"><label for="noconfirmation"><?php _e('Skip Confirmation Email') ?></label></th>
              <td>
                <label for="noconfirmation">
                <input type="checkbox" name="noconfirmation" id="adduser-noconfirmation" checked /> <?php _e( 'Add the user without sending an email that requires their confirmation.' ); ?></label>
              </td>
          </tr>
    </table>
    <input type="submit" value="Ajouter nouvel adhérent" class="button-primary"/>
  </form>

<script type="text/javascript">
  var tel = document.getElementById("adduser-asso1901-telephone");

  tel.addEventListener("keyup", function (event) {
    if(tel.validity.patternMismatch) {
      tel.setCustomValidity("Merci de saisir le numéro de téléphone au bon format.");
    } else {
      tel.setCustomValidity("");
    }
  });
</script>

</div>
