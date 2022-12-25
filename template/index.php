<?php
if (!defined('AppiEngine')) {
    header( "refresh:0; url=/");
}

global $blocks;
global $webSetting;
global $queryMethods;

$mainSlider = $webSetting->getMainSlider();
?>
<div class="carousel carousel-slider center" data-indicators="true">
    <div class="carousel-fixed-item hide-on-med-and-down" style="bottom: 0; height: 100%; z-index: unset;">
        <a class="waves-effect waves-light grey-text text-lighten-3 left btn-carousel-left-right" onclick="$('.carousel').carousel('prev');"><i class="material-icons">chevron_left</i></a>
        <a class="waves-effect waves-light grey-text text-lighten-3 right btn-carousel-left-right" onclick="$('.carousel').carousel('next');"><i class="material-icons">chevron_right</i></a>
    </div>
    <?php
        if ($mainSlider['image_1'])
            echo '
                <div class="carousel-item white black-text" href="#three!" style="background: url(\'' . htmlspecialchars_decode(htmlspecialchars_decode($mainSlider['image_1'])) . '\') no-repeat; background-size: cover; background-position: center;">
                    ' . (($mainSlider['image_link_1']) ? '<a href="' . $mainSlider['image_link_1'] . '" class="btn waves-effect white black-text btn-carousel-center">Подробнее</a>' : '') . '
                </div>
            ';
        if ($mainSlider['image_2'])
            echo '
                <div class="carousel-item white black-text" href="#three!" style="background: url(\'' . htmlspecialchars_decode(htmlspecialchars_decode($mainSlider['image_2'])) . '\') no-repeat; background-size: cover; background-position: center;">
                    ' . (($mainSlider['image_link_2']) ? '<a href="' . $mainSlider['image_link_2'] . '" class="btn waves-effect white black-text btn-carousel-center">Подробнее</a>' : '') . '
                </div>
            ';
        if ($mainSlider['image_3'])
            echo '
                <div class="carousel-item white black-text" href="#three!" style="background: url(\'' . htmlspecialchars_decode(htmlspecialchars_decode($mainSlider['image_3'])) . '\') no-repeat; background-size: cover; background-position: center;">
                    ' . (($mainSlider['image_link_3']) ? '<a href="' . $mainSlider['image_link_3'] . '" class="btn waves-effect white black-text btn-carousel-center">Подробнее</a>' : '') . '
                </div>
            ';
        if ($mainSlider['image_4'])
            echo '
                <div class="carousel-item white black-text" href="#three!" style="background: url(\'' . htmlspecialchars_decode(htmlspecialchars_decode($mainSlider['image_4'])) . '\') no-repeat; background-size: cover; background-position: center;">
                    ' . (($mainSlider['image_link_4']) ? '<a href="' . $mainSlider['image_link_4'] . '" class="btn waves-effect white black-text btn-carousel-center">Подробнее</a>' : '') . '
                </div>
            ';
        if ($mainSlider['image_5'])
            echo '
                <div class="carousel-item white black-text" href="#three!" style="background: url(\'' . htmlspecialchars_decode(htmlspecialchars_decode($mainSlider['image_5'])) . '\') no-repeat; background-size: cover; background-position: center;">
                    ' . (($mainSlider['image_link_5']) ? '<a href="' . $mainSlider['image_link_5'] . '" class="btn waves-effect white black-text btn-carousel-center">Подробнее</a>' : '') . '
                </div>
            ';
    ?>
</div>

<div class="container">
    <div class="section">
        <h5>
            <a href="/category-hot" class="black-text">Горящие товары</a>
            <a href="/category-hot" style="margin-top: -2px;" class="hide-on-small-and-down waves-effect waves-light btn btn-small purple darken-2 right">Подробнее</a>
        </h5>
        <div class="row">
            <?php
                foreach ($queryMethods->getHotProduct() as $item)
                    echo '<div class="col s6 m4 l2">' . $blocks->getShopItemBlock($item['id'], $item['name'], $item['image_prev'], $item['price'], $item['discount'], $item['text']) . '</div>'
            ?>
        </div>
        <h5>
            <a href="/category-top" class="black-text">Популярные товары</a>
            <a href="/category-top" style="margin-top: -2px;" class="hide-on-small-and-down waves-effect waves-light btn btn-small green right">Подробнее</a>
        </h5>
        <div class="row">
            <?php
                foreach ($queryMethods->getTopProduct() as $item)
                    echo '<div class="col s6 m3">' . $blocks->getShopItemBlock($item['id'], $item['name'], $item['image_prev'], $item['price'], $item['discount'], $item['text']) . '</div>'
            ?>
        </div>

        <?php

        foreach ($queryMethods->getAllCategoriesNotChild() as $cat) {

            $test = $queryMethods->getChildArrayCategories($cat['id']);
            $test2 = $queryMethods->getRecommendedProduct($test, 3);

            if (empty($test2))
                continue;

            echo '
                <h5>
                    <a href="/category-' . $cat['id'] . '" class="black-text">' . $cat['name'] . '</a>
                    <a href="/category-' . $cat['id'] . '" style="margin-top: -2px;" class="hide-on-small-and-down waves-effect waves-light btn btn-small ' . $blocks->getRandomColor() . ' right">Подробнее</a>
                </h5>
                <div class="row">
            ';

            switch (rand(0, 2)) {
                case 1:
                    foreach ($queryMethods->getRecommendedProduct($queryMethods->getChildArrayCategories($cat['id']), 3) as $item)
                        echo '<div class="col s12 m4">' . $blocks->getShopItemBlock($item['id'], $item['name'], $item['image_prev'], $item['price'], $item['discount'], $item['text']) . '</div>';
                    break;
                case 2:
                    foreach ($queryMethods->getRecommendedProduct($queryMethods->getChildArrayCategories($cat['id']), 4) as $item)
                        echo '<div class="col s6 m3">' . $blocks->getShopItemBlock($item['id'], $item['name'], $item['image_prev'], $item['price'], $item['discount'], $item['text']) . '</div>';
                    break;
                default:
                    foreach ($queryMethods->getRecommendedProduct($queryMethods->getChildArrayCategories($cat['id']), 6) as $item)
                        echo '<div class="col s6 m4 l2">' . $blocks->getShopItemBlock($item['id'], $item['name'], $item['image_prev'], $item['price'], $item['discount'], $item['text']) . '</div>';
                    break;
            }
            echo '</div>';
        }

        ?>
    </div>
</div>