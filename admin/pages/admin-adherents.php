<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class My_List_Table extends WP_List_Table {

  function no_items() {
    _e( 'Aucun adhérents.' );
  }

  function get_columns(){
    $columns = array(
      'cb' => '<input type="checkbox" />',
      'last_name' => 'Nom',
      'first_name' => 'Prénom',
      'user_email' => 'Mail',
      'telephone' => 'Téléphone',
      'adress' => 'Adresse',
      'type-adhesion' => 'Type d\'adhérent',
      'adhesion' => 'Adhésion'
    );
    return $columns;
  }

  function get_sortable_columns() {
    $sortable_columns = array(
      'last_name'  => array('last_name',false),
      'first_name'  => array('first_name',false)
    );
    return $sortable_columns;
  }

  function column_cb($item) {
      return sprintf(
          '<input type="checkbox" name="adherent[]" value="%s" />', $item->ID
      );
  }

  function column_default( $item, $column_name ) {
    switch( $column_name ) {
      case 'last_name':
        $actions = array(
          'renewal'      => sprintf('<a href="?page=%s&action=%s&adherent=%s">Renouvellement</a>',$_REQUEST['page'],'renewal',$item->ID),
          'edit'      => sprintf('<a href="?page=%s&action=%s&adherent=%s">Modifier</a>',$_REQUEST['page'],'edit',$item->ID),
          'delete'    => sprintf('<a href="?page=%s&action=%s&adherent=%s">Supprimer</a>',$_REQUEST['page'],'delete',$item->ID),
        );
        return sprintf('%1$s %2$s', $item->last_name, $this->row_actions($actions) );
        //return $item->last_name;
      case 'first_name':
        return $item->first_name;
      case 'user_email':
        return $item->user_email;
      case 'telephone':
        return esc_html( get_user_meta($item->ID, 'asso1901-telephone',true) );
      case 'adress':
        return esc_html( get_user_meta($item->ID, 'asso1901-adresse',true) );
      case 'type-adhesion':
        return esc_html( get_user_meta($item->ID, 'asso1901-type-adhesion',true) );
      case 'adhesion':
        $adhesions = explode(",", get_user_meta($item->ID, 'asso1901-adhesion',true));
        $user_adh = "Aucune";
        if(sizeof($adhesions)>0 && $adhesions!=""){
          $user_adh = $adhesions[sizeof($adhesions)-1];
        }
        return esc_html( $user_adh );
      default:
        return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }

  function get_bulk_actions() {
    $actions = array(
      'renewal'    => 'Renouveller',
      'delete'    => 'Supprimer'
    );
    return $actions;
  }

  function process_bulk_action(){
    // security check!
    if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {
        $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
        $action = 'bulk-' . $this->_args['plural'];
        if ( ! wp_verify_nonce( $nonce, $action ) )
            wp_die( 'Nope! Security check failed!' );
    }

    switch ( $this->current_action() ) {
        case 'delete':
            wp_die("La suppression n'est pas implémentée");
            if(array_key_exists('adherent', $_REQUEST)){
              if ( ! current_user_can( 'delete_users' ) )
                  $errors = new WP_Error( 'edit_users', __( 'You can&#8217;t delete users.' ) );

              $entry_id = ( is_array( $_REQUEST['adherent'] ) ) ? $_REQUEST['adherent'] : array( $_REQUEST['adherent'] );
              global $wpdb;
              foreach ( $entry_id as $id ) {
                  $id = absint( $id );
                  echo $id.', ';
                  //$wpdb->query( "DELETE FROM $this->entries_table_name WHERE entries_id = $id" );
              }
            }
            break;
        case 'renewal':
          if(array_key_exists('adherent', $_REQUEST)){
              $entry_id = ( is_array( $_REQUEST['adherent'] ) ) ? $_REQUEST['adherent'] : array( $_REQUEST['adherent'] );
              global $wpdb;
              foreach ( $entry_id as $id ) {
                  $id = absint( $id );
                  $adhesions = get_user_meta($id, 'asso1901-adhesion',true);
                  $user_adh = "Aucune";
                  if(strpos($adhesions, date("Y"))==false){
                    update_user_meta( $id, 'asso1901-adhesion',get_user_meta($id, 'asso1901-adhesion',true).",".date("Y") );
                  }
              }
            }
            break;
        default:
            // do nothing or something else
            return;
            break;
    }
    return;
  }

  function prepare_items() {
    $this->process_bulk_action();

    // get the current admin screen
    $screen = get_current_screen();
    // Retrieve filter value
    $per_adhesion = $screen->get_option('per_adhesion', 'option');

    $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'last_name';
    $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
    $search = ( ! empty($_POST['s'] ) ) ? '*'.$_POST['s'].'*' : '';
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
              'orderby'      => $orderby,
              'order'        => $order,
              'offset'       => '',
              'search'       => $search,
              'number'       => '',
              'count_total'  => false,
              'fields'       => 'all',
              'who'          => ''
              );
    $users = get_users($args);

    // retrieve the "per_page" option
    $screen_option = $screen->get_option('per_page', 'option');
    // retrieve the value of the option stored for the current user
    $per_page = get_user_meta(get_current_user_id(), $screen_option, true);
    if ( empty ( $per_page) || $per_page < 1 ) {
    	// get the default value if none is set
    	$per_page = $screen->get_option( 'per_page', 'default' );
    }
    $current_page = $this->get_pagenum();
    $total_items = count($users);
    $this->set_pagination_args( array(
      'total_items' => $total_items,                  //WE have to calculate the total number of items
      'per_page'    => $per_page                     //WE have to determine how many items to show on a page
    ) );

    $columns = $this->get_columns();
    $hidden = array();
    $sortable = $this->get_sortable_columns();
    $this->_column_headers = array($columns, $hidden, $sortable);

    // only ncessary because we have sample data
    $this->items = array_slice($users,(($current_page-1)*$per_page),$per_page);
  }

}
?>


<div class="wrap">

    <h2>Les adhérents <a href="<?= admin_url('admin.php?page=asso1901/adherent-add-page.php') ?>">Ajouter</a></h2>
    <?php
    $myListTable = new My_List_Table();
    $myListTable->prepare_items();
    ?>
    <form method="post">
      <input type="hidden" name="page" value="my_list_test" />
      <?php $myListTable->search_box('search', 'search_id'); ?>
    </form>

    <form method="post">
      <?php $myListTable->display();?>
    </form>

</div>
