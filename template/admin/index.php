<?php
if (!defined('AppiEngine')) {
    header( "refresh:0; url=/");
}

global $server;
global $webSetting;
global $queryMethods;
global $blocks;
global $tmp;

$footerInfo = $webSetting->getFooter();
$mainSlider = $webSetting->getMainSlider();
$search = (isset($_GET['q']) ? $_GET['q'] : '');
$isEmptyProd = (isset($_GET['empty']) ? $_GET['empty'] : null);
?>

<div class="container" style="margin-top: 80px">
    <div class="section">
        <div id="order">
            <ul class="collapsible white" style="border: 0" data-collapsible="expandable">
                <?php

                foreach ($queryMethods->getAllOrder() as $item) {
                    echo '
                        <li>
                            <div class="collapsible-header">
                                <table>
                                    <tbody>
                                    <tr>
                                        <td style="padding: 0;" width="100">#' . $item['id'] . '</td>
                                        <td style="padding: 0;">' . $item['email'] . '</td>
                                        <td style="padding: 0;">' . number_format($item['sum'], 0, ',', ',') . ' руб.</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="collapsible-body">
                                <b>Номер: </b>' . $item['id'] . '<br>
                                <b>Сумма: </b>' . number_format($item['sum'], 0, ',', ',') . ' руб.<br>
                                <b>Имя: </b>' . $item['name'] . '<br>
                                <b>Телефон: </b>' . $item['phone'] . '<br>
                                <b>Почта: </b>' . $item['email'] . '<br>
                                <b>Дата заказа: </b>' . gmdate('H:i, d/m/Y', $item['timestamp']) . ' (UTC / Гринвич)<br>
                                <b>Товары: </b>' . htmlspecialchars_decode(htmlspecialchars_decode($item['prods'])) . '<br><br>
                                <b>Доп. комменатрий: </b><br>
                               
                                ' . htmlspecialchars_decode(nl2br($item['text'])) . '
                            </div>
                        </li>
                    ';
                }

                ?>
            </ul>
        </div>
        <div id="product">
            <div class="row">

            <?php

                if (isset($_GET['item-edit'])) {

                    $item = $queryMethods->getProductById(intval($_GET['item-edit']));
                    
                    if (!empty($item)) {
                        echo '
                            <form method="post">
                                <h5>Товар - ' . $item['name'] . ' <a href="/admin#product" class="btn right ' . $this->colorBtn . ' lighten-1 ' . $this->colorBtnText . '">Назад</a></h5>
                                <div class="row card-panel" style="margin-top: 30px">
                                    <div class="input-field col s12 m6">
                                        <input required id="name" value="' . $item['name'] . '" name="name" type="text" class="validate">
                                        <label for="name">Имя товара</label>
                                    </div>
                                    <div class="input-field col s12 m4">
                                        <input required id="price" value="' . $item['price'] . '" name="price" min="0" type="number" class="validate">
                                        <label for="price">Цена</label>
                                    </div>
                                    <div class="input-field col s12 m2">
                                        <input required id="sale" value="' . $item['discount'] . '" name="sale" min="0" max="99" type="number" class="validate">
                                        <label for="sale">Скидка</label>
                                    </div>
                                    <div class="input-field col s12 m10">
                                        <input required type="text" id="autocomplete-input" value="' . $item['brand'] . '" name="brand" class="autocomplete">
                                        <label for="autocomplete-input">Бренд</label>
                                    </div>
                                    <div class="input-field col s12 m2">
                                        <input required id="count" name="count" value="' . $item['count'] . '" min="0" type="number" class="validate">
                                        <label for="count">Количество</label>
                                    </div>
                                    <div class="input-field col s12">
                                        <textarea required id="desc-full" name="desc-full" class="materialize-textarea">' . str_replace('<br />', '', htmlspecialchars_decode(nl2br($item['text']))) . '</textarea>
                                        <label for="desc-full">Описание товара</label>
                                    </div>
                                    <div class="input-field col s12 l4">
                                        <textarea id="desc-1" name="desc-1" class="materialize-textarea">' . str_replace('<br />', '', htmlspecialchars_decode(nl2br($item['sub_desc_1']))) . '</textarea>
                                        <label for="desc-1">Доп. инф. #1</label>
                                    </div>
                                    <div class="input-field col s12 l4">
                                        <textarea id="desc-2" name="desc-2" class="materialize-textarea">' . str_replace('<br />', '', htmlspecialchars_decode(nl2br($item['sub_desc_2']))) . '</textarea>
                                        <label for="desc-2">Доп. инф. #2</label>
                                    </div>
                                    <div class="input-field col s12 l4">
                                        <textarea id="desc-3" name="desc-3" class="materialize-textarea">' . str_replace('<br />', '', htmlspecialchars_decode(nl2br($item['sub_desc_3']))) . '</textarea>
                                        <label for="desc-3">Доп. инф. #3</label>
                                    </div>
                                    <div class="input-field hide col s12 l4">
                                        <input required value="' . $item['width_s'] . '" id="width-s" min="1" max="12" type="number" name="width-s">
                                        <label for="width-s">Ширина блока (Телефон)</label>
                                    </div>
                                    <div class="input-field hide col s12 l4">
                                        <input required value="' . $item['width_m'] . '" id="width-m" min="1" max="12" type="number" name="width-m">
                                        <label for="width-m">Ширина блока (Планшет)</label>
                                    </div>
                                    <div class="input-field hide col s12 l4">
                                        <input required value="' . $item['width_l'] . '" id="width-l" min="1" max="12" type="number" name="width-l">
                                        <label for="width-l">Ширина блока (PC)</label>
                                    </div>
                                    <div class="input-field col s12 m6">
                                        <select name="cat">
                                            <option value="0" selected>Нет</option>';
    
                                                foreach ($queryMethods->getAllCategories() as $subItem) {
                        
                                                    if ($subItem['parent_id'] != 0) {
                                                        $parent = $queryMethods->getParentCategory($subItem['parent_id']);
                                                        echo '<option ' . ($subItem['id'] == $item['category_id'] ? 'selected' : '') . ' value="' . $subItem['id'] . '">' . $parent['name'] . ' - ' . $subItem['name'] . '</option>';
                                                    }
                                                    else
                                                        echo '<option ' . ($subItem['id'] == $item['category_id'] ? 'selected' : '') . ' value="' . $subItem['id'] . '">' . $subItem['name'] . '</option>';
                                                }
    
                                        echo '</select>
                                        <label>Категория</label>
                                    </div>
                                    <div class="input-field col s12">
                                        <input required id="name" name="url-prev" type="text" value="' . $item['image_prev'] . '" class="validate">
                                        <label for="name">URL картинки (Превью)</label>
                                    </div>
                                    <div class="input-field col s12">
                                        <input required id="name" name="url-1" type="text" value="' . $item['image_1'] . '" class="validate">
                                        <label for="name">URL картинки #1</label>
                                    </div>
                                    <div class="input-field col s12">
                                        <input required id="name" name="url-2" type="text" value="' . $item['image_2'] . '" class="validate">
                                        <label for="name">URL картинки #2</label>
                                    </div>
                                    <div class="input-field col s12">
                                        <input required id="name" name="url-3" type="text" value="' . $item['image_3'] . '" class="validate">
                                        <label for="name">URL картинки #3</label>
                                    </div>
                                    <input type="hidden" name="id" value="' . $item['id'] . '">
                                    <div class="input-field col s12">
                                        <button class="modal-action waves-effect btn z-depth-0 red" type="submit" name="del-product">Удалить</button>
                                        <button class="modal-action waves-effect btn z-depth-0 right" type="submit" name="edit-product">Сохранить</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        ';
                    }
                    else {
                        echo '
                        <h4 class="center-align">Товар не найден<br><br>
                            <a href="/admin#product" class="btn ' . $this->colorBtn . ' lighten-1 ' . $this->colorBtnText . '">Назад</a>
                        </h4>';
                    }
                }
                else {

                    $products = $queryMethods->getAllProduct($search);

                    if (!empty($products)) {
                        foreach ($products as $item)
                            echo $blocks->getColBlock($blocks->getShopItemBlock($item['id'], $item['name'], $item['image_prev'], $item['price'], $item['discount'], $item['text'], true), $item['width_s'], $item['width_m'], $item['width_l']);
                    }
                    else {
                        $tmp->showBlockPage('errors/product-404');
                    }

                    echo '
                        </div>
                    ';
                }
            ?>

        </div>
        <div id="cat">
            <div class="row">
                <div class="col s12 m6 l5">
                    Категории
                    <ul class="collapsible white" style="border: none" data-collapsible="accordion">
                        <?php
                        foreach ($queryMethods->getCategories(0) as $item)
                            echo '
                                <li>
                                    <div class="collapsible-header">' . $item['name'] . '</div>
                                    <div class="collapsible-body">
                                        <form method="post" class="row" style="margin-bottom: 0;">
                                            <div class="input-field col s12">
                                                <input id="name" name="name" type="text" value="' . $item['name'] . '" class="validate">
                                                <label for="name">Название категории</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <input name="id" value="' . $item['id'] . '" type="hidden">
                                                <button name="del-cat" class="btn red btn-flat white-text tooltipped" data-position="top" data-delay="50" data-tooltip="ВНИМАНИЕ! Все товары этой категории и подкатегории будут удалены!">Удалить</button>
                                                <button name="edit-cat" class="btn blue btn-flat white-text">Редактировать</button>
                                            </div>
                                        </form>
                                    </div>
                                </li>
                            ';
                        ?>
                    </ul>
                </div>
                <div class="col s12 m6 l5">
                    <?php
                    foreach ($queryMethods->getCategories(0) as $item) {

                        $showSubCat = '';

                        foreach ($queryMethods->getCategories($item['id']) as $subItem)
                            $showSubCat .= '
                                <li>
                                    <div class="collapsible-header">' . $subItem['name'] . '</div>
                                    <div class="collapsible-body">
                                        <form method="post" class="row" style="margin-bottom: 0;">
                                            <div class="input-field col s12">
                                                <input id="name" name="name" type="text" value="' . $subItem['name'] . '" class="validate">
                                                <label for="name">Название подкатегории</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <input name="id" value="' . $subItem['id'] . '" type="hidden">
                                                <button name="del-cat" class="btn red btn-flat white-text tooltipped" data-position="top" data-delay="50" data-tooltip="ВНИМАНИЕ! Все товары этой подкатегории будут удалены!">Удалить</button>
                                                <button name="edit-cat" class="btn blue btn-flat white-text">Редактировать</button>
                                            </div>
                                        </form>
                                    </div>
                                </li>
                            ';

                        if (!empty($showSubCat))
                            echo '
                                Подкатегория - ' . $item['name'] . '
                                <ul class="collapsible white" style="border: none" data-collapsible="accordion">
                                ' . $showSubCat . '
                                </ul>';

                    }
                    ?>
                </div>
            </div>
        </div>
        <div id="stock">
            <div class="row">
                <div class="col s12 l9">
                    <ul class="collection card" style="border: none;">
                        <?php

                        $prods = $queryMethods->getAllProduct($search, 100, $isEmptyProd);

                        if (!empty($prods))
                            foreach ($prods as $item)
                                echo '
                                <li class="collection-item avatar" style="max-height: 54px">
                                    <div class="left">
                                        <img style="object-fit: cover;" src="' . $item['image_prev'] . '" alt="" class="circle">
                                        <span class="title"><b>' . $item['name'] . '</b></span>
                                        <p>' . number_format($item['price'], 0, ',', ',') . ' руб.</p>
                                    </div>
                                    <div class="card z-depth-0 row right" style="margin: 0; position: absolute; right: 10px;">
                                        <div class="col s2">
                                            <a onclick="$(\'#count-item-' . $item['id'] . '\').val(($(\'#count-item-' . $item['id'] . '\').val() - 1 < 0) ? 0 : $(\'#count-item-' . $item['id'] . '\').val() - 1 ); $(\'#count-item-' . $item['id'] . '\').change();" class="btn-flat grey lighten-5 waves-effect" style="margin-top: 12px; padding: 0;"><i style="font-size: 2.3rem;" class="material-icons">arrow_left</i></a>
                                        </div>
                                        <div class="input-field col s8" style="margin: 0;">
                                            <input id="count-item-' . $item['id'] . '" item-id="' . $item['id'] . '" class="count-item" min="0" type="number" value="' . $item['count'] . '">
                                        </div>
                                        <div class="col s2">
                                            <a onclick="$(\'#count-item-' . $item['id'] . '\').val(parseInt($(\'#count-item-' . $item['id'] . '\').val()) + 1); $(\'#count-item-' . $item['id'] . '\').change();" class="btn-flat grey lighten-5 waves-effect" style="margin-top: 12px; padding: 0;"><i style="font-size: 2.3rem;" class="material-icons">arrow_right</i></a>
                                        </div>
                                    </div>
                                </li>
                                ';
                        else
                            echo '<h4 style="margin: 30px; text-align: center;">Список пуст</h4>';

                        ?>
                    </ul>
                </div>
                <form class="col s12 l3">
                    <p>
                        <input <?php echo ($isEmptyProd != null ? 'checked' : '') ?> name="empty" value="0" type="checkbox" id="hot" />
                        <label for="hot">Только пустые товары</label>
                    </p>
                    <button class="btn waves-effect waves-light <?php echo $this->colorBtn . ' ' . $this->colorBtnText ?> lighten-1 z-depth-1" type="submit">
                        Применить
                    </button>
                </form>
            </div>
        </div>
        <div id="settings">
            <div class="row">
                <div class="col s12 m6 l4">
                    Коротко о нас
                    <form method="post" class="card-panel">
                        <div style="margin-bottom: 0;" class="row">
                            <div style="margin: 0;" class="input-field col s12">
                                <textarea style="margin-bottom: 0;" id="desc" name="text" class="materialize-textarea"><?php echo htmlspecialchars_decode(nl2br($webSetting->getAboutUs())) ?></textarea>
                                <label for="desc">Текст</label>
                            </div>
                            <div class="input-field col s12">
                                <button class="right btn waves-effect waves-light <?php echo $this->colorBtn . ' ' . $this->colorBtnText ?> lighten-1 z-depth-0" type="submit" name="save-about-us">
                                    Сохранить
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col s12 m6 l4">
                    Ссылки
                    <div class="card-panel">
                        <form method="post" class="row">
                            <div class="input-field col s6">
                                <input id="link-1" name="link-1" value="<?php echo htmlspecialchars_decode($footerInfo['link_1']) ?>" type="text" class="validate">
                                <label for="link-1">Ссылка #1</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="link-name-1" name="link-name-1" value="<?php echo htmlspecialchars_decode($footerInfo['link_name_1']) ?>" type="text" class="validate">
                                <label for="link-name-1">Название ссылки #1</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="link-2" name="link-2" value="<?php echo htmlspecialchars_decode($footerInfo['link_2']) ?>" type="text" class="validate">
                                <label for="link-2">Ссылка #2</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="link-name-2" name="link-name-2" value="<?php echo htmlspecialchars_decode($footerInfo['link_name_2']) ?>" type="text" class="validate">
                                <label for="link-name-2">Название ссылки #2</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="link-3" name="link-3" value="<?php echo htmlspecialchars_decode($footerInfo['link_3']) ?>" type="text" class="validate">
                                <label for="link-3">Ссылка #3</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="link-name-3" name="link-name-3" value="<?php echo htmlspecialchars_decode($footerInfo['link_name_3']) ?>" type="text" class="validate">
                                <label for="link-name-3">Название ссылки #3</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="link-4" name="link-4" value="<?php echo htmlspecialchars_decode($footerInfo['link_4']) ?>" type="text" class="validate">
                                <label for="link-4">Ссылка #4</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="link-name-4" name="link-name-4" value="<?php echo htmlspecialchars_decode($footerInfo['link_name_4']) ?>" type="text" class="validate">
                                <label for="link-name-4">Название ссылки #4</label>
                            </div>
                            <div class="input-field col s12">
                                <button class="right btn waves-effect waves-light <?php echo $this->colorBtn . ' ' . $this->colorBtnText ?> lighten-1 z-depth-0" type="submit" name="save-link">
                                    Сохранить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col s12 m6 l4">
                    Контакты
                    <div class="card-panel">
                        <form method="post" class="row">
                            <div class="input-field col s6">
                                <input id="link-1" name="link-1" value="<?php echo htmlspecialchars_decode($footerInfo['cont_link_1']) ?>" type="text" class="validate">
                                <label for="link-1">Ссылка #1</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="link-name-1" name="link-name-1" value="<?php echo htmlspecialchars_decode($footerInfo['cont_link_name_1']) ?>" type="text" class="validate">
                                <label for="link-name-1">Название ссылки #1</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="link-2" name="link-2" value="<?php echo htmlspecialchars_decode($footerInfo['cont_link_2']) ?>" type="text" class="validate">
                                <label for="link-2">Ссылка #2</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="link-name-2" name="link-name-2" value="<?php echo htmlspecialchars_decode($footerInfo['cont_link_name_2']) ?>" type="text" class="validate">
                                <label for="link-name-2">Название ссылки #2</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="link-3" name="link-3" value="<?php echo htmlspecialchars_decode($footerInfo['cont_link_3']) ?>" type="text" class="validate">
                                <label for="link-3">Ссылка #3</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="link-name-3" name="link-name-3" value="<?php echo htmlspecialchars_decode($footerInfo['cont_link_name_3']) ?>" type="text" class="validate">
                                <label for="link-name-3">Название ссылки #3</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="link-4" name="link-4" value="<?php echo htmlspecialchars_decode($footerInfo['cont_link_4']) ?>" type="text" class="validate">
                                <label for="link-4">Ссылка #4</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="link-name-4" name="link-name-4" value="<?php echo htmlspecialchars_decode($footerInfo['cont_link_name_4']) ?>" type="text" class="validate">
                                <label for="link-name-4">Название ссылки #4</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="link-5" name="link-5" value="<?php echo htmlspecialchars_decode($footerInfo['cont_link_5']) ?>" type="text" class="validate">
                                <label for="link-5">Ссылка #5</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="link-name-5" name="link-name-5" value="<?php echo htmlspecialchars_decode($footerInfo['cont_link_name_5']) ?>" type="text" class="validate">
                                <label for="link-name-5">Название ссылки #5</label>
                            </div>
                            <div class="input-field col s12">
                                <button class="right btn waves-effect waves-light <?php echo $this->colorBtn . ' ' . $this->colorBtnText ?> lighten-1 z-depth-0" type="submit" name="save-cont-link">
                                    Сохранить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m8">
                    Слайдер на гл. странице
                    <div class="card-panel">
                        <form method="post" class="row">
                            <div class="input-field col s12 m7">
                                <input id="link-1" name="link-1" value="<?php echo htmlspecialchars_decode($mainSlider['image_1']) ?>" type="text" class="validate">
                                <label for="link-1">URL картинки #1</label>
                            </div>
                            <div class="input-field col s12 m5">
                                <input id="link-1" name="link-info-1" value="<?php echo htmlspecialchars_decode($mainSlider['image_link_1']) ?>" type="text" class="validate">
                                <label for="link-1">Ссылка на кнопку "Подробнее" #1</label>
                            </div>
                            <div class="input-field col s12 m7">
                                <input id="link-2" name="link-2" value="<?php echo htmlspecialchars_decode($mainSlider['image_2']) ?>" type="text" class="validate">
                                <label for="link-2">URL картинки #2</label>
                            </div>
                            <div class="input-field col s12 m5">
                                <input id="link-1" name="link-info-2" value="<?php echo htmlspecialchars_decode($mainSlider['image_link_2']) ?>" type="text" class="validate">
                                <label for="link-1">Ссылка на кнопку "Подробнее" #2</label>
                            </div>
                            <div class="input-field col s12 m7">
                                <input id="link-3" name="link-3" value="<?php echo htmlspecialchars_decode($mainSlider['image_3']) ?>" type="text" class="validate">
                                <label for="link-3">URL картинки #3</label>
                            </div>
                            <div class="input-field col s12 m5">
                                <input id="link-1" name="link-info-3" value="<?php echo htmlspecialchars_decode($mainSlider['image_link_3']) ?>" type="text" class="validate">
                                <label for="link-1">Ссылка на кнопку "Подробнее" #3</label>
                            </div>
                            <div class="input-field col s12 m7">
                                <input id="link-4" name="link-4" value="<?php echo htmlspecialchars_decode($mainSlider['image_4']) ?>" type="text" class="validate">
                                <label for="link-4">URL картинки #4</label>
                            </div>
                            <div class="input-field col s12 m5">
                                <input id="link-1" name="link-info-4" value="<?php echo htmlspecialchars_decode($mainSlider['image_link_4']) ?>" type="text" class="validate">
                                <label for="link-1">Ссылка на кнопку "Подробнее" #4</label>
                            </div>
                            <div class="input-field col s12 m7">
                                <input id="link-5" name="link-5" value="<?php echo htmlspecialchars_decode($mainSlider['image_5']) ?>" type="text" class="validate">
                                <label for="link-5">URL картинки #5</label>
                            </div>
                            <div class="input-field col s12 m5">
                                <input id="link-1" name="link-info-5" value="<?php echo htmlspecialchars_decode($mainSlider['image_link_5']) ?>" type="text" class="validate">
                                <label for="link-1">Ссылка на кнопку "Подробнее" #5</label>
                            </div>
                            <div class="input-field col s12">
                                <button class="right btn waves-effect waves-light <?php echo $this->colorBtn . ' ' . $this->colorBtnText ?> lighten-1 z-depth-0" type="submit" name="save-slider-img">
                                    Сохранить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col s12 m6 l4">
                    Дизайн
                    <div class="card-panel">
                        <form method="post" class="row">
                            <div class="input-field col s12">
                                <select name="color-main">
                                    <option value="white" selected="">По умолчанию</option>
                                    <option value="red">Красный</option>
                                    <option value="pink">Розовый</option>
                                    <option value="purple">Фиолетовый</option>
                                    <option value="deep-purple">Тёмно-Фиолетовый</option>
                                    <option value="indigo">Индиго</option>
                                    <option value="blue">Синий</option>
                                    <option value="light-blue">Голубой</option>
                                    <option value="cyan">Светло-Голубой</option>
                                    <option value="teal">Караловый</option>
                                    <option value="green">Зеленый</option>
                                    <option value="light-green">Светло-Зеленый</option>
                                    <option value="lime">Лаймовый</option>
                                    <option value="yellow">Желтый</option>
                                    <option value="amber">Тёмно-Желтый</option>
                                    <option value="orange">Оранжевый</option>
                                    <option value="deep-orange">Темно-Оранжевый</option>
                                    <option value="brown">Коричневый</option>
                                    <option value="grey">Серый</option>
                                    <option value="blue-grey">Серо-Голубой</option>
                                    <option value="black">Черный</option>
                                    <option value="white">Белый</option>
                                </select>
                                <label>Основной цвет</label>
                            </div>
                            <div class="input-field col s12">
                                <select name="color-btn">
                                    <option value="blue" selected="">По умолчанию</option>
                                    <option value="red">Красный</option>
                                    <option value="pink">Розовый</option>
                                    <option value="purple">Фиолетовый</option>
                                    <option value="deep-purple">Тёмно-Фиолетовый</option>
                                    <option value="indigo">Индиго</option>
                                    <option value="blue">Синий</option>
                                    <option value="light-blue">Голубой</option>
                                    <option value="cyan">Светло-Голубой</option>
                                    <option value="teal">Караловый</option>
                                    <option value="green">Зеленый</option>
                                    <option value="light-green">Светло-Зеленый</option>
                                    <option value="lime">Лаймовый</option>
                                    <option value="yellow">Желтый</option>
                                    <option value="amber">Тёмно-Желтый</option>
                                    <option value="orange">Оранжевый</option>
                                    <option value="deep-orange">Темно-Оранжевый</option>
                                    <option value="brown">Коричневый</option>
                                    <option value="grey">Серый</option>
                                    <option value="blue-grey">Серо-Голубой</option>
                                    <option value="white">Белый</option>
                                    <option value="black">Черный</option>
                                </select>
                                <label>Цвет кнопок</label>
                            </div>
                            <div class="input-field col s12">
                                <button class="right btn waves-effect waves-light <?php echo $this->colorBtn . ' ' . $this->colorBtnText ?> lighten-1 z-depth-0" type="submit" name="save-dsg">
                                    Сохранить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col s12 m6 l4" style="display: none">
                    Главная страница
                    <div class="card-panel">
                        <form method="post" class="row">
                            <div class="col s12">
                                <p>
                                    <input name="hot-product" type="checkbox" id="hot" />
                                    <label for="hot">Вкладка "Горячие товары"</label>
                                </p>
                                <p>
                                    <input name="top-product" type="checkbox" id="top" />
                                    <label for="top">Вкладка "Популярные товары"</label>
                                </p>
                                <p>
                                    <input name="last-product" type="checkbox" id="last" />
                                    <label for="last">Вкладка "Последние заказы"</label>
                                </p>
                                <br>
                                <button class="btn waves-effect waves-light <?php echo $this->colorBtn . ' ' . $this->colorBtnText ?> lighten-1 z-depth-0" type="submit" name="save-dsg-main-page">
                                    Сохранить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    Пользовательское соглашение
                    <div class="card-panel">
                        <form method="post" style="margin-bottom: 0;" class="row">
                            <div style="margin: 0;" class="input-field col s12">
                                <textarea style="margin-bottom: 0;" required id="terms-full" name="text" class="materialize-textarea"><?php echo str_replace('<br />', '', htmlspecialchars_decode(nl2br($webSetting->getTerms()))); ?></textarea>
                                <label for="terms-full">Пользовательское соглашение</label>
                            </div>
                            <div class="input-field col s12">
                                <button class="right btn waves-effect waves-light <?php echo $this->colorBtn . ' ' . $this->colorBtnText ?> lighten-1 z-depth-0" type="submit" name="save-terms">
                                    Сохранить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form method="post" id="modal-product" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Товар</h4>
        <div class="row">
            <div class="input-field col s12 m6">
                <input required id="name" name="name" type="text" class="validate">
                <label for="name">Имя товара</label>
            </div>
            <div class="input-field col s12 m4">
                <input required id="price" name="price" min="0" type="number" class="validate">
                <label for="price">Цена</label>
            </div>
            <div class="input-field col s12 m2">
                <input required value="0" id="sale" name="sale" min="0" max="99" type="number" class="validate">
                <label for="sale">Скидка</label>
            </div>
            <div class="input-field col s12 m10">
                <input required type="text" id="autocomplete-input" name="brand" class="autocomplete">
                <label for="autocomplete-input">Бренд</label>
            </div>
            <div class="input-field col s12 m2">
                <input required value="0" id="count" name="count" min="0" type="number" class="validate">
                <label for="count">Количество</label>
            </div>
            <div class="input-field col s12">
                <textarea required id="desc-full" name="desc-full" class="materialize-textarea"></textarea>
                <label for="desc-full">Описание товара</label>
            </div>
            <div class="input-field col s12 l4">
                <textarea id="desc-1" name="desc-1" class="materialize-textarea"></textarea>
                <label for="desc-1">Доп. инф. #1</label>
            </div>
            <div class="input-field col s12 l4">
                <textarea id="desc-2" name="desc-2" class="materialize-textarea"></textarea>
                <label for="desc-2">Доп. инф. #2</label>
            </div>
            <div class="input-field col s12 l4">
                <textarea id="desc-3" name="desc-3" class="materialize-textarea"></textarea>
                <label for="desc-3">Доп. инф. #3</label>
            </div>
            <div class="input-field hide col s12 l4">
                <input required value="6" id="width-s" min="1" max="12" type="number" name="width-s">
                <label for="width-s">Ширина блока (Телефон)</label>
            </div>
            <div class="input-field hide col s12 l4">
                <input required value="4" id="width-m" min="1" max="12" type="number" name="width-m">
                <label for="width-m">Ширина блока (Планшет)</label>
            </div>
            <div class="input-field hide col s12 l4">
                <input required value="3" id="width-l" min="1" max="12" type="number" name="width-l">
                <label for="width-l">Ширина блока (PC)</label>
            </div>
            <div class="input-field col s12 m6">
                <select name="cat">
                    <option value="0" selected>Нет</option>
                    <?php
                        foreach ($queryMethods->getAllCategories() as $item) {

                            if ($item['parent_id'] != 0) {
                                $parent = $queryMethods->getParentCategory($item['parent_id']);
                                echo '<option value="' . $item['id'] . '">' . $parent['name'] . ' - ' . $item['name'] . '</option>';
                            }
                            else
                                echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
                        }
                    ?>
                </select>
                <label>Категория</label>
            </div>
            <div class="input-field col s12">
                <input required id="name" name="url-prev" type="text" class="validate">
                <label for="name">URL картинки (Превью)</label>
            </div>
            <div class="input-field col s12">
                <input required id="name" name="url-1" type="text" class="validate">
                <label for="name">URL картинки #1</label>
            </div>
            <div class="input-field col s12">
                <input required id="name" name="url-2" type="text" class="validate">
                <label for="name">URL картинки #2</label>
            </div>
            <div class="input-field col s12">
                <input required id="name" name="url-3" type="text" class="validate">
                <label for="name">URL картинки #3</label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a style="cursor: pointer" class="modal-action modal-close waves-effect btn-flat">Закрыть</a>
        <button class="modal-action waves-effect btn-flat" type="submit" name="add-product">Добавить</button>
    </div>
</form>

<form method="post" id="modal-cat" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Категория</h4>
        <div class="row">
            <div class="input-field col s12">
                <input id="name" name="name" type="text" class="validate">
                <label for="name">Имя категории</label>
            </div>
            <div class="input-field col s12">
                <select name="parent">
                    <option value="0" selected>Нет</option>
                    <?php
                    foreach ($queryMethods->getCategories(0) as $item)
                        echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
                    ?>
                </select>
                <label>Родительская категория</label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a style="cursor: pointer" class="modal-action modal-close waves-effect btn-flat">Закрыть</a>
        <button class="modal-action waves-effect btn-flat" type="submit" name="add-cat">Добавить</button>
    </div>
</form>
