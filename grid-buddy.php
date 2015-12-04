<?php
/*
    Plugin Name: Grid Buddies
    Plugin URI: http://relevad.com/wp-plugins/
    Description: Arranges content into custom grid arrangements
    Author: Relevad
    Version: 0.7
    Author URI: http://relevad.com/
*/

/*  
	Copyright 2015 Relevad Corporation (email: {FIGURE OUT EMAIL}@relevad.com) 
 
    This program is free software; you can redistribute it and/or modify 
    it under the terms of the GNU General Public License as published by 
    the Free Software Foundation; either version 3 of the License, or 
    (at your option) any later version. 
 
    This program is distributed in the hope that it will be useful, 
    but WITHOUT ANY WARRANTY; without even the implied warranty of 
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
    GNU General Public License for more details. 
 
    You should have received a copy of the GNU General Public License 
    along with this program; if not, write to the Free Software 
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA 
*/

/*check for PHP version */
$php_bad_version = version_compare( PHP_VERSION, '5.3.0', '<' );
if ($php_bad_version) {
	add_action( 'admin_init', 'se_deactivate' );
	add_action( 'admin_notices', 'se_deactivation_notice' );
	function st_deactivate() {
	  deactivate_plugins( plugin_basename( __FILE__ ) );
	}
	function st_deactivation_notice() {
	   echo '<div class="error"><p>Sorry, but the <strong>Grid Buddies</strong> plugin requires PHP version 5.3.0 or greater to use. Your PHP version is '.PHP_VERSION.'.</p></div>';
	   if ( isset( $_GET['activate'] ) )
			unset( $_GET['activate'] );
	}
} 

defined( 'ABSPATH' ) or die( 'ILLEGAL ACCESS: LOCAL JUSTICE AGENCIES NOTIFIED' );

													/*BACKEND*/
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~DATABASE*/
function installGridBuddyDatabase(){
    static $run_once = true; 
    if ($run_once === false) return;
	
    global $wpdb;

    $table_name = $wpdb->prefix . 'gridbuddytable'; 
	
	$charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE {$table_name} (
	id int NOT NULL AUTO_INCREMENT,
	name VARCHAR(100) NOT NULL,
	numColSelect int NOT NULL,
	gutterWidthSelect int NOT NULL,
	maxBoxHeightSelect int NOT NULL,
	boxColorSelect VARCHAR(7) NOT NULL,
	titleTextColorSelect VARCHAR(7) NOT NULL,
	excerptTextColorSelect VARCHAR(7) NOT NULL,
	numOfPostsSelect int NOT NULL,
	postOrderSelect VARCHAR(10) NOT NULL,
	excerptLengthSelect int NOT NULL,
	excerptMoreTextSelect VARCHAR(100) NOT NULL,
	gridBuddyPaginationSelect VARCHAR(10) NULL,
	gridBuddyThumbnailDisplaySelect VARCHAR(10) NULL,
	gridBuddyOlderPostsSelect VARCHAR(100) NOT NULL,
	gridBuddyNewerPostsSelect VARCHAR(100) NOT NULL,
	categoriesToPostSelect longtext NOT NULL,
	tagsToPostSelect longtext NOT NULL,
	addedWidgetsTitles longtext NOT NULL,
	addedWidgetsColors longtext NOT NULL,
	addedWidgetsHeights longtext NOT NULL,
	addedWidgetsWidths longtext NOT NULL,
	addedWidgetsTops longtext NOT NULL,
	addedWidgetsLefts longtext NOT NULL,
	UNIQUE KEY id (id),
	PRIMARY KEY  (id)
    ) {$charset_collate};";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
	$run_once = false;

}

register_activation_hook(__FILE__, 'installGridBuddyDatabase');

/* CODE TO BE USE TO UPDATE DATABASE IF NEEDED
global $custom_table_example_db_version;
$custom_table_example_db_version = '2'; 

function custom_table_example_install()
{
    global $wpdb;
    global $custom_table_example_db_version;

    $table_name = $wpdb->prefix . 'gridbuddytable'; 
	
	$charset_collate = $wpdb->get_charset_collate();

    add_option('custom_table_example_db_version', $custom_table_example_db_version);
	
    $installed_ver = get_option('custom_table_example_db_version');
    if ($installed_ver != $custom_table_example_db_version) {
		$sql = "CREATE TABLE {$table_name} (
		id int NOT NULL AUTO_INCREMENT,
		name VARCHAR(100) NOT NULL,
		numColSelect int NOT NULL,
		gutterWidthSelect int NOT NULL,
		maxBoxHeightSelect int NOT NULL,
		boxColorSelect VARCHAR(7) NOT NULL,
		titleTextColorSelect VARCHAR(7) NOT NULL,
		excerptTextColorSelect VARCHAR(7) NOT NULL,
		numOfPostsSelect int NOT NULL,
		postOrderSelect VARCHAR(10) NOT NULL,
		excerptLengthSelect int NOT NULL,
		excerptMoreTextSelect VARCHAR(100) NOT NULL,
		gridBuddyPaginationSelect VARCHAR(10) NULL,
		gridBuddyThumbnailDisplaySelect VARCHAR(10) NULL,
		gridBuddyOlderPostsSelect VARCHAR(100) NOT NULL,
		gridBuddyNewerPostsSelect VARCHAR(100) NOT NULL,
		categoriesToPostSelect longtext NOT NULL,
		tagsToPostSelect longtext NOT NULL,
		addedWidgetsTitles longtext NOT NULL,
		addedWidgetsColors longtext NOT NULL,
		addedWidgetsHeights longtext NOT NULL,
		addedWidgetsWidths longtext NOT NULL,
		addedWidgetsTops longtext NOT NULL,
		addedWidgetsLefts longtext NOT NULL,
		UNIQUE KEY id (id),
		PRIMARY KEY  (id)
		) {$charset_collate};";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // notice that we are updating option, rather than adding it
        update_option('custom_table_example_db_version', $custom_table_example_db_version);
    }
}

function custom_table_example_update_db_check()
{
    global $custom_table_example_db_version;
    if (get_site_option('custom_table_example_db_version') != $custom_table_example_db_version) {
        custom_table_example_install();
    }
}*/

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~SETUP*/

function grid_buddy_admin_script() {
	wp_enqueue_script('grid-buddy-admin-script', plugins_url( '/js/grid-buddy-admin-script.js', __FILE__ ),array( 'jquery' ));
	wp_enqueue_script('grid-buddy-jscolor', plugins_url( '/js/jscolor/jscolor.js', __FILE__ ),array( 'jquery' ));
}	

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~WP LIST TABLE*/

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php'); //admin control table parent class
}

class gridBuddyListTable extends WP_List_Table{
	
    function __construct(){
        global $status, $page;

        parent::__construct(array(
            'singular' => 'gridBuddy',
            'plural' => 'gridBuddies',
        ));
    }
	
    function column_default($item, $column_name){
        return $item[$column_name];
    }

    function column_name($item){
        $actions = array(
            'edit' => sprintf('<a href="?page=gridBuddies_form&id=%s">%s</a>', $item['id'], 'Edit'),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], 'Delete'),
        );

        return sprintf('%s %s',
            $item['name'],
            $this->row_actions($actions)
        );
    }

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }
	
	function column_shortcode($item){
		return sprintf(
            '<tt>[grid-buddy id=%s]</tt>',
            $item['id']
        );
	}

    function get_columns(){
        $columns = array(
            'cb' => '<input type="checkbox" />', 
            'name' => 'Name',
			'shortcode' => 'Shortcode',
        );
        return $columns;
    }

    function get_sortable_columns(){
        $sortable_columns = array('name' => array('name', true),);
        return $sortable_columns;
    }

    function get_bulk_actions(){
        $actions = array('delete' => 'Delete');
        return $actions;
    }

    function process_bulk_action(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'gridbuddytable'; // do not forget about tables prefix

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }

    function prepare_items(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'gridbuddytable'; 

        $per_page = 5; 

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();

        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'name';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

        $this->set_pagination_args(array(
            'total_items' => $total_items, 
            'per_page' => $per_page, 
            'total_pages' => ceil($total_items / $per_page) 
        ));
    }
}

function gridBuddyAdminMenus(){
    add_menu_page('GridBuddies', 'GridBuddies', 'activate_plugins', 'gridBuddies', 'gridBuddyAdminTable');
    add_submenu_page('gridBuddies', 'GridBuddies', 'GridBuddies', 'activate_plugins', 'gridBuddies', 'gridBuddyAdminTable');
    add_submenu_page('gridBuddies', 'Add New', 'Add New', 'activate_plugins', 'gridBuddies_form', 'gridBuddyInstanceSettings');
}

add_action('admin_menu', 'gridBuddyAdminMenus');

function gridBuddyAdminTable()
{
    global $wpdb;

    $table = new gridBuddyListTable();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p> Items deleted </p></div>';
    }
    ?>
	<div class="wrap">

		<h2>Grid Buddies<a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=gridBuddies_form');?>">Add New</a></h2>
		<p>Here's a table of all of the Grid Buddies you've made. Grid Buddies can be added into either a post or page, and content<br>
		   will be arranged relative to the size of the container where the shortcode was placed. If you'd wish to put a grid buddy<br>
		   in a widget, you first need to install the <a href="https://wordpress.org/plugins/shortcode-widget/" ref="external nofollow" target="_blank">Shortcode Widget</a> plugin.<br>
		   Alternatively, you can use <code>&lt;?php echo do_shortcode('[grid-buddy id=#]'); ?&gt;</code> inside your theme files or a <a href="https://wordpress.org/plugins/php-code-widget/" ref="external nofollow" target="_blank">PHP Code Widget</a> to place a grid buddy.
	   </p>
		
		
		<?php echo $message; ?>
		<form id="persons-table" method="GET">
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
			<?php $table->display() ?>
		</form>

	</div>
<?php
}
function gridBuddyInstanceSettings(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'gridbuddytable'; 

    $message = '';
    $notice = '';

	$defaultCatArray = array();
	foreach(get_categories(array('orderby' => 'name','order' => 'ASC')) as $cat){
		$defaultCatArray[$cat->name] = null;
	}
	
	$defaultTagArray = array();
	foreach(get_tags(array('orderby' => 'name','order' => 'ASC')) as $tag){
		$defaultTagArray[$tag->name] = null;
	}
	
	$defaultAddedWidget = array('title' => null, 'color' => null, 'height' => null, 'top' => null, 'left' => null);

    $default = array(
		'id' => 0,
		'name' => '',
		'numColSelect' => 5,
		'gutterWidthSelect' => 10,
		'maxBoxHeightSelect' => 0,
		'boxColorSelect' => '5c3170', 
		'titleTextColorSelect' => 'FFFFFF',  
		'excerptTextColorSelect' => 'FFFFFF', 		
		'numOfPostsSelect' => 5,
		'postOrderSelect' => '',
		'excerptLengthSelect' => 200, 
		'excerptMoreTextSelect' => 'Read More...',
		'gridBuddyPaginationSelect' => null,
		'gridBuddyThumbnailDisplaySelect' => null,
		'gridBuddyOlderPostsSelect' => 'Older Posts',
		'gridBuddyNewerPostsSelect' => 'Newer Posts',
		'categoriesToPostSelect' => $defaultCatArray,
		'tagsToPostSelect' => $defaultTagArray,
		'addedWidgetsTitles' => array(), 
		'addedWidgetsColors' => array(), 
		'addedWidgetsHeights' => array(), 
		'addedWidgetsWidths' => array(), 
		'addedWidgetsTops' => array(), 
		'addedWidgetsLefts' => array(), 
    );

    if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $item = shortcode_atts($default, $_REQUEST);
		
		/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~SERIALIZE ARRAYS~~~*/
		$item['categoriesToPostSelect'] = serialize($item['categoriesToPostSelect']);
		$item['tagsToPostSelect'] = serialize($item['tagsToPostSelect']);
		$item['addedWidgetsTitles'] = serialize($item['addedWidgetsTitles']);
		$item['addedWidgetsColors'] = serialize($item['addedWidgetsColors']);
		$item['addedWidgetsHeights'] = serialize($item['addedWidgetsHeights']);
		$item['addedWidgetsWidths'] = serialize($item['addedWidgetsWidths']);
		$item['addedWidgetsTops'] = serialize($item['addedWidgetsTops']);
		$item['addedWidgetsLefts'] = serialize($item['addedWidgetsLefts']);

		//have options verificatino method here?
        $item_valid = true;
		$item['numColSelect'] = intval($item['numColSelect']);
		$item['gutterWidthSelect'] = intval($item['gutterWidthSelect']);
		$item['maxBoxHeightSelect'] = intval($item['maxBoxHeightSelect']);
		$item['numOfPostsSelect'] = intval($item['numOfPostsSelect']);
		$item['excerptLengthSelect'] = intval($item['excerptLengthSelect']);
				
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $result = $wpdb->insert($table_name, $item);
                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = 'Options Saved';
                } else {
                    $notice = 'ERROR options DID NOT save';
                }
            } else {
                $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                if ($result) {
                    $message = 'Options Updated';
                } else {
                    $notice = 'ERROR options DID NOT update(or simply no changes were made)';
                }
            }
        } else {
            $notice = $item_valid;
        }
    }
    else {
        $item = $default;
        if (isset($_REQUEST['id'])) {
            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
            if (!$item) {
                $item = $default;
                $notice = 'Item not found';
            }
        }
    }

    add_meta_box('persons_form_meta_box', 'GridBuddy data', 'makeGridBuddyOptions', 'gridBuddy', 'normal', 'default');

    ?>
	<div class="wrap">
		<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
		<h2><a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=gridBuddies');?>">Back to list</a></h2>

		<?php if (!empty($notice)): ?>
		<div id="notice" class="error"><p><?php echo $notice ?></p></div>
		<?php endif;?>
		<?php if (!empty($message)): ?>
		<div id="message" class="updated"><p><?php echo $message ?></p></div>
		<?php endif;?>

		<form id="form" method="POST">
			<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
			<input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>

			<div class="metabox-holder" id="poststuff">
				<div id="post-body">
					<div id="post-body-content">
						<?php do_meta_boxes('gridBuddy', 'normal', $item); ?>
						<input type="submit" value="Save" id="submit" class="button-primary" name="submit">
					</div>
				</div>
			</div>
		</form>
	</div>
<?php
}

function makeGridBuddyOptions($item) { ?>
	<?php grid_buddy_admin_script() ?>
	<h1>Grid Buddy Options</h1>
	<hr>
	<p>
		Grid Area Name:<input type="text" name="name" id="name" value="<?php echo esc_attr($item['name'])?>" placeholder="Grid Area Name" required>
		<?php 
		if($item['id'] == 0){
			echo "Save this grid buddy to get shortcode";
		}else{
			echo "[grid-buddy id=" . $item['id'] . "]";
		}
		?>
	</p>
	<div style="background-color: lightgrey; margin: 5px; padding: 10px;">						
		<h2>Style</h2>
		Number of Columns:
			<input type="number" min="1" max="50" step="1" name="numColSelect" id="numColSelect" class="code" value="<?php echo intval(esc_attr($item['numColSelect']))?>" placeholder="Number of columns" required>
		<h4>Spacing</h4>
		Gutter Width:
			<input type="number" min="0" max="500" step="1" name="gutterWidthSelect" id="gutterWidthSelect" class="code" value="<?php echo esc_attr($item['gutterWidthSelect'])?>" placeholder="Gutter Width" required>
		<h4>Colors</h4>
		Box Color:
			<input type="text" name="boxColorSelect" id="boxColorSelect" class="color" value="<?php echo esc_attr($item['boxColorSelect'])?>" placeholder="Box Background Color" required>
		Title Text Color:
			<input type="text" class="color" name="titleTextColorSelect" id="titleTextColorSelect" value="<?php echo esc_attr($item['titleTextColorSelect'])?>" placeholder="Title Text Color" required>
		Excerpt Text Color:
			<input type="text" class="color" name="excerptTextColorSelect" id="excerptTextColorSelect" value="<?php echo esc_attr($item['excerptTextColorSelect'])?>" placeholder="Excerpt Text Color" required>
		<h4>Max Box Height</h4>
		Set max height(in px) for grid boxes(enter '0' to set height automatically):
			<input type="number" min="0" max="5000" step="1" name="maxBoxHeightSelect" id="maxBoxHeightSelect" value="<?php echo esc_attr($item['maxBoxHeightSelect'])?>" placeholder="Max Box Height" required> 
	</div>
		
	<div style="background-color: white; margin: 5px; padding: 10px;">
		<h2>Content</h2>
			<h4>Post settings</h4>
			Number of Posts to show per page(enter '0' to show all posts):
				<input type="number" min="0" step="1" name="numOfPostsSelect" id="numOfPostsSelect" value="<?php echo esc_attr($item['numOfPostsSelect'])?>" placeholder="Num" required>
			Post Order(by date):
				<select name="postOrderSelectMenu">
				  <option value="DESC" <?php if(strcmp($item['postOrderSelect'],'DESC') == 0){ echo 'selected'; }?> >Descending</option>
				  <option value="ASC" <?php if(strcmp($item['postOrderSelect'],'ASC') == 0){ echo 'selected'; }?> >Ascending</option>
				  <option value="rand" <?php if(strcmp($item['postOrderSelect'],'rand') == 0){ echo 'selected'; }?> >Random</option>
				</select>
				<?php $item['postOrderSelect'] = $_POST['postOrderSelectMenu']; ?>
			<h4>Select posts by category(Posts will be selected from both selected categories and tags)</h4>
				<ul>
					<?php 
					if(strcmp(gettype($item['categoriesToPostSelect']), 'array') != 0 ){
						$item['categoriesToPostSelect'] = unserialize($item['categoriesToPostSelect']); 
					}
					foreach(get_categories(array('orderby' => 'name','order' => 'ASC')) as $cat){?> 
						<li><input type="checkbox" name="categoriesToPostSelect[<?php echo $cat->name ?>]" value="yes" <?php if($item['categoriesToPostSelect'][$cat->name] == "yes"){ echo 'checked'; }?> ><?php echo $cat->name; ?></li>
					<?php }  ?>
				</ul>
			<h4>Select posts by tag</h4>
				<ul>
					<?php
					if(strcmp(gettype($item['tagsToPostSelect']), 'array') != 0 ){
						$item['tagsToPostSelect'] = unserialize($item['tagsToPostSelect']); 
					}
					foreach(get_tags(array('orderby' => 'name','order' => 'ASC')) as $tag){?> 
						<li><input type="checkbox" name="tagsToPostSelect[<?php echo $tag->name ?>]" value="yes" <?php if($item['tagsToPostSelect'][$tag->name] == "yes"){ echo 'checked'; }?> ><?php echo $tag->name; ?></li>
					<?php }  ?>
				</ul>
			<h4>Excerpt</h4>
			Show post thumbnails?<input type="checkbox" name="gridBuddyThumbnailDisplaySelect" value="yes" <?php if ( is_null ( $item['gridBuddyThumbnailDisplaySelect'] ) != true ) checked( $item['gridBuddyThumbnailDisplaySelect'], 'yes' ); ?> />
			Entry Excerpt Charecter Length(set to '0' to show whole post):
				<input type="number" min="0" max="1000" step="1" name="excerptLengthSelect" id="excerptLengthSelect" value="<?php echo esc_attr($item['excerptLengthSelect'])?>" placeholder="Num" required>
			Entry Excerpt 'More' text:
				<input type="text" name = "excerptMoreTextSelect" id="excerptMoreTextSelect" value="<?php echo esc_attr($item['excerptMoreTextSelect'])?>" placeholder="More Text" required>
			<h4>Navigation</h4>
			Show pagination controls:
				<input type="checkbox" name="gridBuddyPaginationSelect" value="yes" <?php if ( is_null ( $item['gridBuddyPaginationSelect'] ) != true ) checked( $item['gridBuddyPaginationSelect'], 'yes' ); ?> />
			Pagination Text:
				Older: <input type="text" name = "gridBuddyOlderPostsSelect" id="gridBuddyOlderPostsSelect" value="<?php echo esc_attr($item['gridBuddyOlderPostsSelect'])?>" placeholder="Older Posts Text" required>
				Newer: <input type="text" name = "gridBuddyNewerPostsSelect" id="gridBuddyNewerPostsSelect" value="<?php echo esc_attr($item['gridBuddyNewerPostsSelect'])?>" placeholder="Newer Posts Text" required>
	</div>
	
	<div style="background-color:lightgrey; margin: 5px; padding: 10px;">
		<h2>Stamping</h2>
		<i>Stamping allows you to fix either a post or widget area onto a fixed place on the grid, other un-stamped grid elements will automatically position themselves around these stamped elements</i><br>
		<i>Top and Left margins are relative to the grid buddy container</i><br>
			<input type="text" class="addNewWidgTitle"><button type="button" class="addNewWidgButton">Add New</button>
			<ul class="listOfWidgetAreas">
				<?php 		
				if(strcmp(gettype($item['addedWidgetsTitles']), 'array') != 0 ){
					$item['addedWidgetsTitles'] = unserialize($item['addedWidgetsTitles']); 
				}
				if(strcmp(gettype($item['addedWidgetsColors']), 'array') != 0 ){
					$item['addedWidgetsColors'] = unserialize($item['addedWidgetsColors']); 
				}
				if(strcmp(gettype($item['addedWidgetsHeights']), 'array') != 0 ){
					$item['addedWidgetsHeights'] = unserialize($item['addedWidgetsHeights']); 
				}
				if(strcmp(gettype($item['addedWidgetsWidths']), 'array') != 0 ){
					$item['addedWidgetsWidths'] = unserialize($item['addedWidgetsWidths']); 
				}
				if(strcmp(gettype($item['addedWidgetsTops']), 'array') != 0 ){
					$item['addedWidgetsTops'] = unserialize($item['addedWidgetsTops']); 
				}
				if(strcmp(gettype($item['addedWidgetsLefts']), 'array') != 0 ){
					$item['addedWidgetsLefts'] = unserialize($item['addedWidgetsLefts']); 
				}
				if($item['addedWidgetsTitles']){
					foreach($item['addedWidgetsTitles'] as $w){ ?>
						<li> 
							Title:<input style="width: 100px;" name="addedWidgetsTitles[<?php echo $w ?>]" value="<?php echo esc_attr($item['addedWidgetsTitles'][$w])?>" readonly><br>
							Background Color:<input style="width: 100px;" class="color" name="addedWidgetsColors[<?php echo $w ?>]" value="<?php echo esc_attr($item['addedWidgetsColors'][$w])?>" required>
							Height:<input type="number" min="0" max="5000" step="1" style="width: 100px;" name="addedWidgetsHeights[<?php echo $w ?>]" value="<?php echo esc_attr($item['addedWidgetsHeights'][$w])?>" required>
							Width:<input type="number" min="0" max="5000" step="1" style="width: 100px;" name="addedWidgetsWidths[<?php echo $w ?>]" value="<?php echo esc_attr($item['addedWidgetsWidths'][$w])?>" required>
							Top Margin:<input type="number" min="0" max="5000" step="1" style="width: 100px;" name="addedWidgetsTops[<?php echo $w ?>]" value="<?php echo esc_attr($item['addedWidgetsTops'][$w])?>" required>
							Left Margin:<input type="number" min="0" max="5000" step="1" style="width: 100px;" name="addedWidgetsLefts[<?php echo $w ?>]" value="<?php echo esc_attr($item['addedWidgetsLefts'][$w])?>" required>
							<button type="button" class="deleteWidgetButton">Delete This Widget</button>
						</li>
					<?php } 
				}?>
			</ul>
	</div>
<?php }

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~REGISTER WIDGET AREAS*/
function gridBuddyRegisterWidgetAreas() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'gridbuddytable';
	$result = $wpdb->get_results( "SELECT * FROM $table_name ", ARRAY_A);
	if(count($result) != 0){
		foreach($result as $row){
			if(!is_null(consolidateWidgetOptions(intval($row['id'])))){
				$addW = new widgetAddClass();
				$addW->registerWidgetAreas(intval($row['id']));
			}
		}
	}
}

class widgetAddClass {
	function registerWidgetAreas($id){
		$alreadyRegistered = array();
		foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { 
		  array_push($alreadyRegistered, $sidebar['name']);
		}
		foreach(consolidateWidgetOptions($id) as $w){
			if(!in_array($w['nameOUT'], $alreadyRegistered)){
				register_sidebar( array(
					'name' => $w['nameOUT'],
					'before_widget' => '<div id ="' .  $w['nameOUT'] . 'div' . '" class="registeredWidget" style="position: relative; padding: 5px; width: ' . $w['widthOUT'] . 'px; height:' . $w['heightOUT'] . 'px; background-color: #' . $w['colorOUT'] . '; top: ' . $w['topOUT'] . 'px; left: ' . $w['leftOUT'] . 'px;">',
					'after_widget'  => '</div>',
				));
			}
		}
	}
}

add_action( 'widgets_init', 'gridBuddyRegisterWidgetAreas');	

												/*FRONTEND*/
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~SETUP*/
function getGridBuddy($id){
	if($id != 0){
		global $wpdb;
		$table_name = $wpdb->prefix . 'gridbuddytable';
		$query = "SELECT * FROM $table_name WHERE id =" . $id;
		$itemRow = $wpdb->get_row($query, ARRAY_A);
		return $itemRow;
	}
}	
function grid_buddy_script() {
	wp_enqueue_script('grid-buddy-packery', plugins_url( '/js/packery.pkgd.min.js', __FILE__ ),array( 'jquery' ));
	wp_enqueue_script('grid-buddy-script', plugins_url( '/js/grid-buddy-script.js', __FILE__ ),array( 'jquery' ));
}	

add_action('wp_enqueue_scripts', 'grid_buddy_script');

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~LOGIC/OUTPUT*/	
function outputGridOptions($id){
	$gridBuddyOUT= getGridBuddy($id); ?>
	<script type='text/javascript'>
		var numColSelectOUT = <?php echo $gridBuddyOUT['numColSelect']; ?>;
		var gutterWidthOUT = <?php echo $gridBuddyOUT['gutterWidthSelect']; ?>;
		var maxBoxHeightOUT = <?php echo $gridBuddyOUT['maxBoxHeightSelect']; ?>;
		var boxColorOUT = "<?php echo $gridBuddyOUT['boxColorSelect']; ?>";
		var titleTextColorOUT = "<?php echo $gridBuddyOUT['titleTextColorSelect']; ?>";
		var excerptTextColorOUT = "<?php echo $gridBuddyOUT['excerptTextColorSelect']; ?>";
		var widgsOUT = <?php echo json_encode(consolidateWidgetOptions($id));?>;
	</script>
	<?php
}
function consolidateWidgetOptions($id){
	if($id != 0){
		$gridBuddyOUT = getGridBuddy($id);	
		if(count(unserialize($gridBuddyOUT['addedWidgetsTitles'])) != 0){
			if(strcmp(gettype($gridBuddyOUT['addedWidgetsTitles']), 'array') != 0 ){
				$gridBuddyOUT['addedWidgetsTitles'] = unserialize($gridBuddyOUT['addedWidgetsTitles']); 
			}
			if(strcmp(gettype($gridBuddyOUT['addedWidgetsColors']), 'array') != 0 ){
				$gridBuddyOUT['addedWidgetsColors'] = unserialize($gridBuddyOUT['addedWidgetsColors']); 
			}
			if(strcmp(gettype($gridBuddyOUT['addedWidgetsHeights']), 'array') != 0 ){
				$gridBuddyOUT['addedWidgetsHeights'] = unserialize($gridBuddyOUT['addedWidgetsHeights']); 
			}
			if(strcmp(gettype($gridBuddyOUT['addedWidgetsWidths']), 'array') != 0 ){
				$gridBuddyOUT['addedWidgetsWidths'] = unserialize($gridBuddyOUT['addedWidgetsWidths']); 
			}
			if(strcmp(gettype($gridBuddyOUT['addedWidgetsTops']), 'array') != 0 ){
				$gridBuddyOUT['addedWidgetsTops'] = unserialize($gridBuddyOUT['addedWidgetsTops']); 
			}
			if(strcmp(gettype($gridBuddyOUT['addedWidgetsLefts']), 'array') != 0 ){
				$gridBuddyOUT['addedWidgetsLefts'] = unserialize($gridBuddyOUT['addedWidgetsLefts']); 
			}
			
			$widgs = array();
			foreach($gridBuddyOUT['addedWidgetsTitles'] as $w){
				$widgs[$w] = array( 'nameOUT' => $w, 'colorOUT' => $gridBuddyOUT['addedWidgetsColors'][$w], 'heightOUT' => $gridBuddyOUT['addedWidgetsHeights'][$w], 'widthOUT' => $gridBuddyOUT['addedWidgetsWidths'][$w], 'topOUT' => $gridBuddyOUT['addedWidgetsTops'][$w], 'leftOUT' => $gridBuddyOUT['addedWidgetsLefts'][$w]);
			}
			
			return $widgs;
		}
	}
}

function getPosts($id){
	$gridBuddyOUT = getGridBuddy($id);
	$numPosts = $gridBuddyOUT['numOfPostsSelect'];
	if($numPosts === 0){ $numPosts = -1; }
	
	$catsToGet = '';
	$tagsToGet = '';
	
	if(unserialize($gridBuddyOUT['categoriesToPostSelect']) != false){ 
		foreach(array_keys(unserialize($gridBuddyOUT['categoriesToPostSelect'])) as $c){$catsToGet .= "," . get_cat_ID($c);}
	}

	if(unserialize($gridBuddyOUT['categoriesToPostSelect']) != false){ 
		foreach((array_keys(unserialize($gridBuddyOUT['categoriesToPostSelect']))) as $t){$tagsToGet .= "," . $t;}
	}

	if(empty($catsToGet) and empty($tagsToGet)){ 
		return $thePosts; 
	}
	
	$catsToGet = substr($catsToGet, 1);
	$tagsToGet = substr($tagsToGet, 1);

	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	
	$args = array(
		'posts_per_page'   => $numPosts,
		'cat'              => $catsToGet,
		'orderby'          => 'date',
		'order'            => $gridBuddyOUT['postOrderSelect'],
		'post_status'      => 'publish',
		'paged'            => $paged,
	);
	$thePosts = new WP_Query($args);
	return $thePosts;	
}
function buildBoxes($loop, $id){ ?>
	<?php $gridBuddyOUT= getGridBuddy($id); ?>
	<div class="gridBuddyWrapper">
		<?php 
		$widgs = unserialize($gridBuddyOUT['addedWidgetsTitles']);		
		if($widgs){
			foreach($widgs as $w){
				dynamic_sidebar($w); 
			}
		}
		while($loop->have_posts()){
			$loop->the_post(); ?>
			<a style="display:block" href="<?php the_permalink() ?>">
				<div class = "gridBuddyBox">
					<div class="gridBuddyBoxThumbnail"><?php 
					if(has_post_thumbnail()){
						if(strcmp($gridBuddyOUT['gridBuddyThumbnailDisplaySelect'], "yes") == 0){
							the_post_thumbnail(); 
						}
					}?>
					</div>
					<div class="gridBuddyBoxWords">
						<div class="gridBuddyBoxEntryTitle">
							<h4 style="margin: 0px; padding-left: 5px;padding-right: 5px;"><?php the_title() ?></h4>
						</div>
						<div class="gridBuddyBoxEntryContent">
							<p style="margin: 0px; padding-top: 10px; padding-left: 5px;padding-right: 5px;"><?php echo substr(get_the_excerpt(), 0, $gridBuddyOUT['excerptLengthSelect']) . " " . $gridBuddyOUT['excerptMoreTextSelect'] ; ?></p>
						</div>
					</div>
				</div>
			</a>
	<?php } ?>
	</div>
	
	<?php if(strcmp($gridBuddyOUT['gridBuddyPaginationSelect'], "yes") == 0){?>
		<div class="gridBuddyPagination" style="position: relative;width: 25%;height: 20px;margin-top: 10px;margin-right: auto;margin-left: auto;">
			<div class="gridBuddyOlderPosts" style="width:50%;float:left;">
				<?php echo get_next_posts_link($gridBuddyOUT['gridBuddyOlderPostsSelect'], $loop->max_num_pages ); ?>
			</div>
			<div class="gridBuddyNewerPosts" style="width:50%; float:right;">
				<?php echo get_previous_posts_link($gridBuddyOUT['gridBuddyNewerPostsSelect']); ?>
			</div>
		</div>
	<?php }
	wp_reset_postdata(); 
}

function grid_buddy_shortcode( $atts ) {

    $a = shortcode_atts( array(
        'id' => '1',
    ), $atts, 'grid-buddy' );
	
	$posts = getPosts(intval($atts['id']));
	outputGridOptions(intval($atts['id']));

	if($posts != NULL){
		buildBoxes($posts, intval($atts['id']));
	}else{
		echo "There's no posts!";
	}
}

add_shortcode( 'grid-buddy', 'grid_buddy_shortcode' );   

?>