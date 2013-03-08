<?php 

require_once '../lib/bootstrap.php';

$db = new Database();

$banners = __('Banners');
$template = new Template('banners');
$template->set('title', "$affiliate_programme_name: $banners");

$rows = $db->get_pdo()->query(
    'select id, name, link_target from banners where enabled order by id');

$rows = $rows->fetchAll();
$template->set('banners', $rows);

$slash = substr($store_home, -1) == '/' ? '' : '/';
$template->set('store_home', $store_home . $slash);

$dir = dirname($_SERVER['PHP_SELF']);
if($dir != '/') $dir .= '/';
$protocol = $https ? 'https' : 'http';
$template->set('banner_script',
    "$protocol://${_SERVER['HTTP_HOST']}${dir}servebanner.php?name=");

$template->set('refparam',
    $affiliate_referrer_parameter.'='.$_SESSION['affiliate_id']);

$template->set('dataparam', $affiliate_data_parameter);

$template->render();
