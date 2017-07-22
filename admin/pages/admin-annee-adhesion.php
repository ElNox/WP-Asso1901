<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Annees_List_Table extends WP_List_Table {

  function no_items() {
    _e( 'Aucune année d\'adhésion de paramétré.' );
  }

  function get_columns(){
    $columns = array(
      'cb' => '<input type="checkbox" />',
      'titre' => 'Titre',
      'date_ag' => 'Date AG',
      'date_debut' => 'Date début',
      'date_fin' => 'Date fin',
    );
    return $columns;
  }

  function get_sortable_columns() {
    $sortable_columns = array(
      'titre'  => array('titre',false),
      'date_ag'  => array('date_ag',false),
      'date_debut'  => array('date_debut',false),
    );
    return $sortable_columns;
  }

  function column_cb($item) {
      return sprintf(
          '<input type="checkbox" name="annee[]" value="%s" />', $item->ID
      );
  }

  function column_default( $item, $column_name ) {
    switch( $column_name ) {
      case 'last_name':
        $actions = array(
          'edit'      => sprintf('<a href="?page=%s&action=%s&annee=%s">Modifier</a>',$_REQUEST['page'],'edit',$item->id),
          'delete'    => sprintf('<a href="?page=%s&action=%s&annee=%s">Supprimer</a>',$_REQUEST['page'],'delete',$item->id),
        );
        return sprintf('%1$s %2$s', $item->titre, $this->row_actions($actions) );
        //return $item->last_name;
      case 'titre':
        return $item->titre;
      case 'date_ag':
        return $item->date_ag;
      case 'date_debut':
        return $item->date_debut;
        case 'date_fin':
          return $item->date_fin;
      default:
        return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }

  function get_bulk_actions() {
    $actions = array(
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
            if(array_key_exists('annee', $_REQUEST)){
              if ( ! current_user_can( 'asso1901_delete_annee' ) )
                  $errors = new WP_Error( 'asso1901_delete_annee', __( 'You can&#8217;t delete years.' ) );

              $entry_id = ( is_array( $_REQUEST['annee'] ) ) ? $_REQUEST['annee'] : array( $_REQUEST['annee'] );
              global $wpdb;
              foreach ( $entry_id as $id ) {
                  $id = absint( $id );
                  echo $id.', ';
                  //$wpdb->query( "DELETE FROM $this->entries_table_name WHERE entries_id = $id" );
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
    $per_annee = $screen->get_option('per_annee', 'option');

    $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'date_debut';
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
    global $wpdb;
    $annees = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix .'asso1901_annee_adhesion');

    // retrieve the "per_page" option
    $screen_option = $screen->get_option('per_page', 'option');
    // retrieve the value of the option stored for the current user
    $per_page = get_user_meta(get_current_user_id(), $screen_option, true);
    if ( empty ( $per_page) || $per_page < 1 ) {
    	// get the default value if none is set
    	$per_page = $screen->get_option( 'per_page', 'default' );
    }
    $per_page=10;
    $current_page = $this->get_pagenum();
    $total_items = count($annees);
    $this->set_pagination_args( array(
      'total_items' => $total_items,                  //WE have to calculate the total number of items
      'per_page'    => $per_page                     //WE have to determine how many items to show on a page
    ) );

    $columns = $this->get_columns();
    $hidden = array();
    $sortable = $this->get_sortable_columns();
    $this->_column_headers = array($columns, $hidden, $sortable);

    // only ncessary because we have sample data
    $this->items = array_slice($annees,(($current_page-1)*$per_page),$per_page);
  }

}
?>


<div class="wrap">

    <h2>Les années de l'association <a href="<?= admin_url('admin.php?page=asso1901/annee-add-page.php') ?>">Ajouter</a></h2>
    <?php
    $myListTable = new Annees_List_Table();
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
