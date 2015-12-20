<div class="wrap">
    <h1>Les adhérents <a href="#">Ajouter</a></h1>
    <?php
    $args = array(
              'blog_id'      => 'WP-Asso1901',
              'role'         => '',
              'meta_key'     => '',
              'meta_value'   => '',
              'meta_compare' => '',
              'meta_query'   => array(),
              'date_query'   => array(),
              'include'      => array(),
              'exclude'      => array(),
              'orderby'      => 'login',
              'order'        => 'ASC',
              'offset'       => '',
              'search'       => '',
              'number'       => '',
              'count_total'  => false,
              'fields'       => 'all',
              'who'          => ''
              );
      $users = get_users();
     ?>

<table class="form-table">
<tr>
  <th>Nom</th>
  <th>Prénom</th>
  <th>Mail</th>
  <th>Téléphone</th>
  <th>Adresse</th>
</tr>
<?php
foreach ( $users as $user ) {
  echo '<tr>';
  echo '<td>' . esc_html( $user->first_name ) . '</td>';
  echo '<td>' . esc_html( $user->last_name ) . '</td>';
	echo '<td>' . esc_html( $user->user_email ) . '</td>';
  echo '<td>' . esc_html( get_user_meta($user->ID, 'asso1901-telephone',true) ) . '</td>';
  echo '<td>' . esc_html( get_user_meta($user->ID, 'asso1901-adresse',true) ) . '</td>';
  $adhesions = get_user_meta($user->ID, 'asso1901-adhesion',true);
  $user_adh = "Aucune";
  if(sizeof($adhesions)>0 && $adhesions!=""){
    $user_adh = $adhesions[sizeof($adhesions)];
  }
  echo '<td>' . esc_html( $user_adh ) . '</td>';
  echo '</tr>';
}
?>
</table>

</div>
