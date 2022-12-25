<?php
if (!defined('AppiEngine')) {
    header( "refresh:0; url=/");
}

global $blocks;
global $queryMethods;
global $page;
global $tmp;
$search = (isset($_GET['q']) ? $_GET['q'] : '');

?>

<div class="container">
    <div class="section">
        <h5>
            Топ 50 горящих товаров
        </h5>
        <div id="prod-more" class="row">
            <?php
            $products = $queryMethods->getHotProduct($search, 50);

            if (!empty($products)) {
                foreach ($products as $item)
                    echo $blocks->getColBlock($blocks->getShopItemBlock($item['id'], $item['name'], $item['image_prev'], $item['price'], $item['discount'], $item['text']), $item['width_s'], $item['width_m'], $item['width_l']);
            }
            else {
                $tmp->showBlockPage('errors/product-404');
            }
            ?>
        </div>
    </div>
</div>