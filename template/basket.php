<?php
if (!defined('AppiEngine')) {
    header( "refresh:0; url=/");
}

global $queryMethods;

$sum = 0;
?>
<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12 m8 l5">
                <ul class="collection card" style="border: none">
                    <?php
                    foreach ($_SESSION as $key => $value) {
                        $key = preg_replace('/[^\d]/uix', '', $key);

                        $product = $queryMethods->getProductById($key);

                        $newPrice = $product['price'] * $product['discount'] / 100;
                        $newPrice = $product['price'] - $newPrice;

                        $sum += $newPrice * $value;

                        $price =  number_format($product['price'], 0, ',', ',');
                        $newPrice =  number_format($newPrice, 0, ',', ',');

                        echo '
                            <li id="product-id-' . $product['id'] . '" class="collection-item avatar" style="min-height: 54px;">
                                <img src="' . $product['image_prev'] . '" alt="" class="circle" style="object-fit: cover">
                                <span class="title">' . $product['name'] . '</span><br>
                                <label>Цена: ' . $newPrice . ', ' . $value . ' шт.</label>
                                <a style="cursor: pointer" id="remove-basket" product-id="' . $product['id'] . '" class="remove-basket secondary-content"><i class="material-icons black-text">close</i></a>
                            </li>
                        ';
                    }
                    ?>
                </ul>
            </div>
            <div class="col s12 m0 hide-on-med-and-down l1"></div>
            <div class="col s12 m4 l5">
                <h5>Итого: <span class="green-text" id="product-sum"><?php echo number_format($sum, 0, ',', ','); ?></span> руб.</h5>
                <hr>
                <div class="row">
                    <form method="post" class="col s12">
                        <div class="row">
                            <div class="input-field col s12">
                                <input required id="user-name" name="name" type="text">
                                <label for="user-name">Имя <r class="amber-text text-darken-2">*</r></label>
                            </div>
                            <div class="input-field col s12">
                                <input required id="email" name="email" type="email">
                                <label for="email">Email <r class="amber-text text-darken-2">*</r></label>
                            </div>
                            <div class="input-field col s12">
                                <input id="user-phone" name="phone" type="text">
                                <label for="user-phone">Телефон</label>
                            </div>
                            <div class="input-field col s12">
                                <textarea id="desc" name="desc" class="materialize-textarea"></textarea>
                                <label for="desc">Доп. Информация</label>
                            </div>
                            <div class="col s12">
                                <button name="new-order" class="btn waves-light waves-effect right <?php echo $this->colorBtn . ' lighten-1 ' . $this->colorBtnText ?>">Заказать</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
