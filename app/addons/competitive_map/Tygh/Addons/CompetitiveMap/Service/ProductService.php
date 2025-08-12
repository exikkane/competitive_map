<?php

namespace Tygh\Addons\CompetitiveMap\Service;

use Tygh\Enum\OutOfStockActions;
use Tygh\Enum\YesNo;


class ProductService
{
    public function getFilteredProducts($params, ?string $features_hash): array
    {
        $p_data = [
            'category_id'         => $params['category_id'],
            'pid'                 => $params['product_ids'],
            'features_hash'       => $features_hash,
            'subcats'             => YesNo::YES,
            'cid'                 => $params['category_id'],
            'features_display_on' => 'A'
        ];

        [$products,] = fn_get_products($p_data);

        fn_gather_additional_products_data($products, [
            'get_features' => true,
        ]);

        return $this->setStockInfo($products);
    }

    protected function setStockInfo(array &$products): array
    {
        if (empty($products)) {
            return [];
        }

        foreach ($products as &$product) {
            if ($product['amount'] > 0) {
                $product['stock_info'] = __("in_stock");
            } else {
                switch ($product['out_of_stock_actions']) {
                    case OutOfStockActions::BUY_IN_ADVANCE:
                        $product['stock_info'] = __("on_backorder");
                        break;
                    case OutOfStockActions::NONE:
                        $product['stock_info'] = __("text_out_of_stock");
                        break;
                }
            }
        }
        return $products;
    }
}
