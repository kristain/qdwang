<?php

$opencart_path = realpath(dirname(__FILE__) . '/../') . '/';

// Verify path is correct
if(!$opencart_path) die('COULD NOT DETERMINE CORRECT FILE PATH');

// Admin Configuration
// Rename the admin part of the text below if you've changed your admin folder name
require_once($opencart_path . 'admin/config.php');

$file_content = file_get_contents($opencart_path . 'index.php');

if (strpos($file_content, "modCheck(DIR_SYSTEM . 'startup.php'));") === false) {
	die('vQmod is not installed. <a href="' . HTTP_CATALOG . 'vqmod/install/index.php" target="_blank">Install it</a> or <a href="http://code.google.com/p/vqmod/downloads/list" target="_blank"> download it</a> and follow the installation instructions.');
}

$scss_cache_dir = $opencart_path . 'catalog/view/theme/omf2/stylesheet/scss_cache/';

if (!is_writable($scss_cache_dir))
	die("$scss_cache_dir is not writeable. Use your FTP client to create it or if it exists to set its permissions to 755");

$omf_files = array('colors', 'custom_mobile', 'settings');
$omf_folder = $opencart_path . 'catalog/view/theme/omf2/stylesheet/';
$omf_min_folder = $opencart_path . 'catalog/view/theme/omf2-min/stylesheet/';

foreach ($omf_files as $file) {
	$omf_file = $omf_folder . $file;
	$omf_min_file = $omf_min_folder . $file;

	if (!file_exists($omf_file . '.scss')) {
		rename($omf_file . '_dist.scss', $omf_file . '.scss');
	}

	if (!file_exists($omf_min_file . '.scss')) {
		rename($omf_min_file . '_dist.scss', $omf_min_file . '.scss');
	}
}

require_once(DIR_SYSTEM . 'library/db.php');

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

//Create [OMF]All_Categories layout

$omf_layout = array(
    'name'      => '[OMF]All_Categories',
    'route'		=> 'common/all_categories'
);

$query = $db->query("SELECT layout_id FROM " . DB_PREFIX . "layout WHERE name = '" . $db->escape($omf_layout['name']) . "'");

if ($query->num_rows < 1) {
	$db->query("INSERT INTO " . DB_PREFIX . "layout SET name = '" . $db->escape($omf_layout['name']) . "'");	
	$layout_id = $db->getLastId();
} else {
	$layout_id = $query->rows[0]['layout_id'];
}

$query = $db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE layout_id = '" . (int)$layout_id . "'AND store_id = 0");

if ($query->num_rows < 1)
	$db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int)$layout_id . "', store_id = 0, route = '" . $db->escape($omf_layout['route']) . "'");

$query = $db->query("SELECT store_id FROM " . DB_PREFIX . "store");
$stores = $query->rows;

foreach ($stores as $store) {
	$query = $db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE layout_id = '" . (int)$layout_id . "'AND store_id = '" . (int)$store['store_id'] . "'");

	if ($query->num_rows < 1)
		$db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int)$layout_id . "', store_id = '" . (int)$store['store_id'] . "', route = '" . $db->escape($omf_layout['route']) . "'");
}


$module_data = array();
$omfa_installed = false;
$installed = false;

$query = $db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE type = 'module' AND code = 'omfa'");

if ($query->num_rows < 1) {
	//Install Opencart Mobile Framework Admin Panel if it is not
	$db->query("INSERT INTO " . DB_PREFIX . "extension SET type = 'module', code = 'omfa'");

	$user_group_query = $db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '1'");
		
	if ($user_group_query->num_rows) {
		$data = unserialize($user_group_query->row['permission']);

		$data['access'][] = 'module/omfa';
		$data['modify'][] = 'module/omfa';

		$db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . serialize($data) . "' WHERE user_group_id = '1'");
	}

} else {
	$omfa_installed = true;
}

$query = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'config_mobile_theme' AND store_id = '" . 0 . "' AND `group` = 'omfa'");

if ($query->num_rows == 1) {
	$db->query("UPDATE " . DB_PREFIX . "setting SET `key` = 'config_mobile_theme', `value` = 'omf2' WHERE store_id = '" . 0 . "' AND `group` = 'omfa'");
	die('OpenCart Mobile Framework upgrade is complete. You can now setup OMF2 through the admin panel of your store or visit the mobile version of your site on your smartphone.');
}

$query = $db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE type = 'module' AND code = 'category_mobile'");

if ($query->num_rows < 1) {
	//Install Category Mobile if it is not
	$db->query("INSERT INTO " . DB_PREFIX . "extension SET type = 'module', code = 'category_mobile'");
} else {
	//Get all Category Mobile modules
	$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = 0 AND `group` = 'category_mobile' AND `key` = 'category_mobile_module'");
	if ($query->num_rows > 0) {
		$modules = unserialize($query->rows[0]['value']);

		foreach ($modules as $module) {
			//Check if there is a module already assigned to the [OMF]All_Categories layout
			if($module['layout_id'] == $layout_id)
				$installed = true;
			$module_data[] = array(
				'layout_id'		=> $module['layout_id'],
			    'position'		=> $module['position'],
			    'status'		=> $module['status'],
			    'sort_order'	=> $module['sort_order']
			);
		}
	}
}

//Assign a Category Mobile module to the [OMF]All_Categories layout
if (!$installed) {
	$module_data[] = array(
	    'layout_id'		=> $layout_id,
	    'position'		=> 'content_bottom',
	    'count'			=> '0',
	    'status'		=> '1',
	    'sort_order'	=> ''	
	);

	$db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = 0, `group` = 'category_mobile', `key` = 'category_mobile_module', `value` = '" . $db->escape(serialize($module_data)) . "', serialized = '1'");
} else if ($omfa_installed) {
	die('OpenCart Mobile Framework already installed!');
}

// output result to user
die('OpenCart Mobile Framework installation is complete. You can now setup OMF through the admin panel of your store or visit the mobile version of your site on your smartphone.');

?>
