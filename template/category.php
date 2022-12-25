<?php
if (!defined('AppiEngine')) {
    header( "refresh:0; url=/");
}

global $blocks;
global $queryMethods;
global $page;
global $tmp;

$getChildCat = $queryMethods->getChildCategories($page['category-']);
$getChildCatArr = $queryMethods->getChildArrayCategories($page['category-']);

$maxPrice = $queryMethods->getMaxPriceByCategories($getChildCatArr);
$maxPrice = $maxPrice['price'];
$search = (isset($_GET['q']) ? $_GET['q'] : '');

$filterPrice = (isset($_GET['max-price']) ? $_GET['max-price'] : $maxPrice);
$filterSort = (isset($_GET['psort']) ? $_GET['psort'] : 0);
$filterBrand = (isset($_GET['brand']) && is_array($_GET['brand']) ? $_GET['brand'] : array());
$filterDiscount = (isset($_GET['discount']) ? 1 : 0);
$filterSortRadio = $filterSort;

switch ($filterSort) {
    case 1:
        $filterSort = 'price ASC';
        break;
    case 2:
        $filterSort = 'price DESC';
        break;
    case 3:
        $filterSort = 'views DESC';
        break;
    default:
        $filterSort = 'id DESC';
        break;
}

?>
<input type="hidden" id="cat-id" value="<?php echo $page['category-']; ?>">
<div class="container">
    <div class="section">
        <h5>
            <?php $catId = $queryMethods->getCategoryById($page['category-']); echo $catId['name']; ?>
        </h5>
        <div style="display: flow-root;">
            <div class="catalog-left left hide-on-small-and-down">
                <div class="collection card" style="border: none">
                    <?php
                        foreach ($getChildCat as $item)
                            echo '<a href="/category-' . $item['id'] . '" class="collection-item black-text waves-effect">' . $item['name'] . '</a>';
                    ?>
                </div>
                Сотрировка
                <div class="card-panel">
                    <form method="get">
                        <div>Цена</div>
                        <p class="range-field" style="margin: 0;">
                            <input name="max-price" type="range" id="sort-price" value="<?php echo $filterPrice ?>" min="1" max="<?php echo $maxPrice ?>" style="margin: 0;"/>
                        </p>
                        <p>
                            <input <?php echo ($filterSortRadio == 0) ? 'checked' : '' ?> name="psort" value="0" type="radio" id="price-0" />
                            <label for="price-0">Нет</label>
                        </p>
                        <p>
                            <input <?php echo ($filterSortRadio == 1) ? 'checked' : '' ?> name="psort" value="1" type="radio" id="price-1" />
                            <label for="price-1">По возрастанию</label>
                        </p>
                        <p>
                            <input <?php echo ($filterSortRadio == 2) ? 'checked' : '' ?> name="psort" value="2" type="radio" id="price-2" />
                            <label for="price-2">По убыванию</label>
                        </p>
                        <p>
                            <input <?php echo ($filterSortRadio == 3) ? 'checked' : '' ?> name="psort" value="3" type="radio" id="price-3" />
                            <label for="price-3">По популярности</label>
                        </p>
                        <hr style="margin-bottom: 20px">
                        Бренд
                        <?php

                        foreach ($queryMethods->getBrandByCategories($getChildCatArr) as $item) {
                            echo '
                                <p>
                                    <input name="brand[]" value="' . $item['brand'] . '" type="checkbox" id="brand-' . $item['id'] . '" />
                                    <label for="brand-' . $item['id'] . '">' . $item['brand'] . '</label>
                                </p>
                            ';
                        }

                        ?>
                        <hr style="margin-bottom: 20px">
                        <!-- Switch -->
                        <p>
                            <input name="discount" <?php echo ($filterDiscount != 0) ? 'checked' : ''; ?> value="true" type="checkbox" id="sale-0" />
                            <label for="sale-0">Скидка</label>
                        </p><br>
                        <button class="btn z-depth-0 waves-effect waves-light <?php echo $this->colorBtn . ' ' . $this->colorBtnText ?>" style="width: 100%">Применить</button>
                    </form>
                </div>
                Рекомендации
                <?php
                    foreach ($queryMethods->getRecommendedProduct($getChildCatArr) as $item)
                        echo $blocks->getColBlock($blocks->getShopItemBlock($item['id'], $item['name'], $item['image_prev'], $item['price'], $item['discount'], $item['text']), $item['width_s'], $item['width_m'], $item['width_l']);
                ?>
            </div>
            <div class="catalog-right left">
                <div id="prod-more-blocks" class="row">
                    <?php
                        $products = $queryMethods->getAllFilterProductByCatId($getChildCatArr, $search, $filterPrice, $filterSort, $filterBrand, $filterDiscount);

                        if (!empty($products)) {
                            foreach ($products as $item)
                                echo $blocks->getColBlock($blocks->getShopItemBlock($item['id'], $item['name'], $item['image_prev'], $item['price'], $item['discount'], $item['text']), $item['width_s'], $item['width_m'], $item['width_l']);
                        }
                        else {
                            $tmp->showBlockPage('errors/product-404');
                        }
                    ?>
                </div>
                <div id="prod-more">
                    <button id="prod-more-btn" class="btn btn-large z-depth-0 waves-effect waves-light <?php echo $this->colorBtn . ' ' . $this->colorBtnText ?>" style="width: 100%">
                        Показать больше
                    </button>
                    <div id="prod-more-progress" style="display: none" class="progress <?php echo $this->colorBtn ?> lighten-4">
                        <div class="indeterminate <?php echo $this->colorBtn ?>"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>