<?php

use Tygh\Addons\CompetitiveMap\Service\RefererParser;
use Tygh\Addons\CompetitiveMap\Service\ProductService;
use Tygh\Addons\CompetitiveMap\Service\CompetitiveMapExporter;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

/* @var string $mode */
/* @var string $auth */

if ($mode === 'download') {
    if (empty($_SESSION['auth']['user_id'])) {
        return [CONTROLLER_STATUS_REDIRECT, 'profiles.add'];
    }

    $parser = new RefererParser($_SERVER['HTTP_REFERER'] ?? '');
    $params = [];

    if (isset($_REQUEST['params'])) {
        $params = $_REQUEST['params'];
    } elseif (isset($_REQUEST['category_id'])) {
        $params = ['category_id' => $_REQUEST['category_id']];
    }

    if (empty($params)) {
        return [CONTROLLER_STATUS_NO_PAGE];
    }

    $features_hash = $parser->getFeaturesHash();

    $filename = fn_get_category_name($params['category_id'])
        ? __("competitive_map") . ' - ' . fn_get_category_name($params['category_id'])
        : __("competitive_map") . ' - ' . __("search");

    $product_service = new ProductService();
    $products = $product_service->getFilteredProducts($params, $features_hash);
    $user_company_name = db_get_field("SELECT company FROM ?:users WHERE user_id = ?i", $auth['user_id']);

    $exporter = new CompetitiveMapExporter($products, [
        'filename'     => $filename,
        'referer'      => $_SERVER['HTTP_REFERER'] ?? '',
        'company_name' => $user_company_name,
    ]);

    $exporter->downloadXlsx();
}
