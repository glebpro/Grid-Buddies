<?php 
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	echo "ACCESS FORBIDDEN";
    exit();
}
global $wpdb;
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "gridbuddytable" );
?>