<?php
if (!defined('AppiEngine')) {
    header( "refresh:0; url=/");
}

global $blocks;
global $product;
global $queryMethods;
global $qb;

$qb
    ->createQueryBuilder('products')
    ->updateSql(array(
        'views'
    ), array(
        ++$product['views']
    ))
    ->where('id = \'' . $product['id'] . '\'')
    ->executeQuery()
    ->getSingleResult()
;

$oldPrice = $product['price'];
$newPrice = $product['price'] * $product['discount'] / 100;
$newPrice = $product['price'] - $newPrice;
$price =  number_format($product['price'], 0, ',', ',');
$newPrice =  number_format($newPrice, 0, ',', ',');

?>

<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12 m8 l9">
                <div class="card">
                    <div class="carousel carousel-slider center" data-indicators="true">
                        <div class="carousel-fixed-item hide-on-med-and-down" style="bottom: 0; height: 100%; z-index: unset;">
                            <a class="waves-effect waves-light grey-text text-lighten-3 left btn-carousel-left-right" onclick="$('.carousel').carousel('prev');"><i class="material-icons">chevron_left</i></a>
                            <a class="waves-effect waves-light grey-text text-lighten-3 right btn-carousel-left-right" onclick="$('.carousel').carousel('next');"><i class="material-icons">chevron_right</i></a>
                        </div>
                        <div class="carousel-item white black-text" href="#three!" style="background: url('<?php echo $product['image_1']; ?>') no-repeat; background-size: cover; background-position: center;"></div>
                        <div class="carousel-item white black-text" href="#four!" style="background: url('<?php echo $product['image_2']; ?>') no-repeat; background-size: cover; background-position: center;"></div>
                        <div class="carousel-item white black-text" href="#one!" style="background: url('<?php echo $product['image_3']; ?>') no-repeat; background-size: cover; background-position: center;"></div>
                    </div>
                </div>
                <div itemscope itemtype="http://schema.org/Product" style="margin: 40px 0;">
                    <h5>
                        <name itemprop="name"><?php echo $product['name']; ?></name> <label><?php echo ($product['count'] > 0 ? 'В наличии' : 'Нет в наличии'); ?></label>

                        <div style="margin-top: -2px;" class="right hide-on-small-and-down">
                            <?php echo ($product['discount'] > 0 ? '<label class="old-price">' . $price . ' руб.</label>' : ''); ?>
                            <a class="waves-effect waves-light btn btn-small green right" id="add-basket" product-id="<?php echo $product['id'] ?>">Купить <?php echo $newPrice; ?> руб.</a>
                        </div>
                        <div style="margin-top: 10px; display: none" class="show-on-small">
                            <?php echo ($product['discount'] > 0 ? '<label class="old-price">' . $price . ' руб.</label>' : ''); ?>
                            <a class="waves-effect waves-light btn btn-small green" id="add-basket" product-id="<?php echo $product['id'] ?>">Купить <?php echo $newPrice; ?> руб.</a>
                        </div>
                    </h5>
                    <div class="hide" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                        <span itemprop="price"><?php echo $oldPrice; ?></span>
                        <span itemprop="priceCurrency">RUB</span>
                    </div>
                    <img class="hide" src="<?php echo $product['image_prev']; ?>" itemprop="image">
                    <hr>
                    <div itemprop="description">
                        <?php echo htmlspecialchars_decode(nl2br($product['text'])); ?>
                    </div>
                    <?php

                    if(!empty($product['sub_desc_1']) || !empty($product['sub_desc_2']) || !empty($product['sub_desc_3'])) {

                        $count = 0;

                        if (empty($product['sub_desc_1']))
                            $count++;
                        if (empty($product['sub_desc_2']))
                            $count++;
                        if (empty($product['sub_desc_3']))
                            $count++;

                        echo '
                        <hr>
                        <div style="margin-bottom: 10px">
                            <b>Дополнительная информация</b>
                        </div>
                        <div class="row">';

                            if ($count == 1) {
                                echo '
                                    <div class="col s12 l6">
                                        ' . htmlspecialchars_decode(nl2br((empty($product['sub_desc_1']) ? $product['sub_desc_3'] : $product['sub_desc_1']))) . '
                                    </div>
                                    <hr style="display: none" class="show-on-medium-and-down">
                                    <div class="col s12 l6">
                                        ' . htmlspecialchars_decode(nl2br((empty($product['sub_desc_2']) ? $product['sub_desc_3'] : $product['sub_desc_2']))) . '
                                    </div>
                                ';
                            }
                            else if ($count == 2) {
                                echo '
                                    <div class="col s12">
                                        ' . htmlspecialchars_decode(nl2br((empty($product['sub_desc_1']) ? (empty($product['sub_desc_3']) ? $product['sub_desc_2'] : $product['sub_desc_3']) : $product['sub_desc_1']))) . '
                                    </div>
                                ';
                            }
                            else if ($count == 3) {
                                echo '
                                    <div class="col s12 l4">
                                        ' . htmlspecialchars_decode(nl2br($product['sub_desc_1'])) . '
                                    </div>
                                    <hr style="display: none" class="show-on-medium-and-down">
                                    <div class="col s12 l4">
                                        ' . htmlspecialchars_decode(nl2br($product['sub_desc_2'])) . '
                                    </div>
                                    <hr style="display: none" class="show-on-medium-and-down">
                                    <div class="col s12 l4">
                                        ' . htmlspecialchars_decode(nl2br($product['sub_desc_3'])) . '
                                    </div>
                                ';
                            }

                            /*<div class="col s12 l4">
                                <?php echo htmlspecialchars_decode(nl2br($product[\'sub_desc_1\'])); ?>
                            </div>
                            <hr style="display: none" class="show-on-medium-and-down">
                            <div class="col s12 l4">
                                <?php echo htmlspecialchars_decode(nl2br($product[\'sub_desc_2\'])); ?>
                            </div>
                            <hr style="display: none" class="show-on-medium-and-down">
                            <div class="col s12 l4">
                                <?php echo htmlspecialchars_decode(nl2br($product[\'sub_desc_3\'])); ?>
                            </div>*/
                        echo '</div>';

                    }

                    ?>
                    <hr style="margin: 20px 0;">
                    <div class="center-align">
                        <a target="_blank" href="https://twitter.com/share?url=http://<?php echo $_SERVER['SERVER_NAME']; ?>/item-<?php echo $product['id'] ?>"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAACrUlEQVRYR+2XzXEaQRCF35t1oXKByigC4QhMBkYRCF2MdLIUATgCkYGlDOQToAs4AuMMUATGGeACSmUVO+2aVUFhaWd3huHAAa5093zz+md6iR3/ccf5sAcMzdBewb2CQQr0pRw9zU8FrJg4mvIdn0ojn5jZNdiXMs448Qm4tFW9aZvg9UtfEYy1yBUuDofJf51pTSle6kbpMu0cO2BvXleQa10onvhCRr3pEODH7IvJQIAawXIMnqFRHHgBqu7shkRTgJEPpE05G6wIbrVEN8CislJ1zdiq4LoKSVrIL7Zbrh+uetMJwXcuZSGQCYQTUI60flPFxdvxSz87YHc6AHn6v4MM4kLpypryzrQWKf5wgVvaCOSPJmu25rECqu6sReLrqyJ/vvVAK9y+Ctqb1yNI3wcw1tH7NOWWMexN0peyepqNs9Jl6hPggJBxzGiMeEFfBeNGKXOSZI+ZDRTxUQ+Qn3HjsJblY09xb3YH8BdEKiRSZ5QfTJp1ICCBz+EQ9ggCfLMN6Pwa3KAjfS+TNaDzAQFEqaPGF8NuHxeKR3mvVO5brP7OhyQ+bA9rNQFzG8RYOi2s5vmC8JLE8bZAYy0naU+b80uyMjSjRqQpRJlAdTuA+d3rVIOJkcPA9oV2Vc85xbifVZXI0HUJyAJOtpfzUsv1Uk41mATrPFYitTDDO2fPy5h7ggd9XvIqE3fA5bmdx4piXE9bJHKUe9AHxVreWPFvknUPA6fiJgHnFBl3McptAOdWg88vyjEgdYB119pZTTtTcwfFtq9y2V2cQKEdUm/JpkK2fL/i/FKcdK9Jp9RdOlgEvwGYZfYuFMx9Di4t72fVCKiKSPKNS0FVaBZWQEONQBlvC2pdRf8u9i3CQPs9YKCAbttM6CEh/vsUh6jn9pKEnhDov/Mp/gev7gk4HVoR2wAAAABJRU5ErkJggg==" width="40" height="40"></a>
                        <a target="_blank" href="https://plus.google.com/share?url=http://<?php echo $_SERVER['SERVER_NAME']; ?>/item-<?php echo $product['id'] ?>"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAEJUlEQVRYR+2YTWwbRRTH/8+mThN/EA6tOMQhOdIUwYlGolKdCwgJiTVCCgektu4BLpWacgEJmoRwbRvErVXd5IYBySsFwQGJuCc+TiCRXoninMqB1HYSsIkfepM42l3vznhtV2qrziVRdubNL//3NTOEh3zQQ86Hxx9wNztypsmRjHiCwWPyk0Dr8jNCzdJgcfNOL17qSsFadvQsM1sAZYgwrANgxhbAJSKyE8WN5bCwoQCr1kiGQLMgUoqFHswlBs8n7c1Sp2s7AmRreHgbyesgOtepYe085qU4qjNkb22Z7BkBd61nx/YQK4LwkslYqO+M3+KoTJkgtYCiXA2pP01xFgrMMVniM4HKuA4yEHDfranVvivn/W8MSgYC1qz07b7FnEli5qWEXT7vN80XUGUrRVZNdvv5nbk55ZfdvoA1K72qKyV0bATRiVOg42nF2Fxfw96vP/TGy1xK2OUpr5E2wJqVPgei275yHxvBQO4Koqdea/+8fR/1lTwaXy12D8p8PmGXl5wG2gCr2bRNoDe9u0QnJnH0wxtA/GktwH+r3+DfLz7oDpJ5OWGXXbW2HdAa/dtbViJjJzC48OUhnEA0Vm6huX5XgURffhWxd2bU77sfTwM7lUBAsYV4Es21X9rmSNlJ2hvPBCoYlByDCwVEJibVOlFHAH3HUEoLJ2tatrbfes7XhDdZXApWrdE5Isw6Vyr1rn2v/tQoLKJeuN6d+w5WmQExn7Q35lqbGAFj0zM4Mn1Jzd9+9wWXQgIfy10JBK7nP1Vh4JwXHZ8A4ins/fGTWifecHqEWQNYs9JLIDrr3HHg4lU8NfU2+K9N7Lz3igtGJc5CIRDwn0+msbf2M3Tz2rziSRSXglrAe2XsvH/aBSPKDFxwRYRSRyUCgBagc5HJxeDm5wl7c99l6vDrGH4xqHOxn3RH3sghltuH3r38+mGmt+aaALUu7jlJhlIYuvad6jB+ISGQEi6R4+nAZDMA+vfgTstMK14FpJ6fR+PbfOiM15YZsVbNjm4R4GoXqtR8VgCkzknm/fi12lwyVPpyZPx5DORmHb35rnJv2MHA/WRxw3XHCdfqPrp5CBm0uUCbukkgeCetznhYuDCrWlvb2KmgsZJHfeWWsZsEARJzNm6Xbed3//NgNl0i0JlAQ3LcOjmpgl2GFF2pd70MBt9JFsttt8VH88AqSvgV7V4U0q71ib3WfP2liZIlgF58YGDKMP8e52om6GZnvnZSat1bdvoFLGUlwZWxrq6dLQh1/XwgSuqVM7rYqdLB08ei96TTtZLMy3FUL5leFcS+8enDCSEnbhDN6UqQDlpKCZjn+v545N1UFXMgw0SWKT4lzohZiq9cK103tk48EEpBP4NKVew/YNLBAyYfPGACzVIYtfzs9wzYiQq9zHkC2It6svZ/UnnbODle1ZwAAAAASUVORK5CYII=" width="40" height="40"></a>
                        <a target="_blank" href="http://vk.com/share.php?url=http://<?php echo $_SERVER['SERVER_NAME']; ?>/item-<?php echo $product['id'] ?>"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAACvklEQVRYR+2YQUwTURCG/1eDhZjQGhWMRlpESTQmLcqRRILeOGi86QX1pCfEE1w0esGTxZOeqHjQm0dvKk30ZqSNGpUEaSExKTHaGokthB0za3bd3b62292l0qRz6773Zr73z8zb7hPY4ia2OB+agG4z1MAKxuaCvqKIETAoIMJulZCtJ1BaALOKn8Yw1peTzZErGJsLiqJYFBDBzQCz+iRQjvzULYOUAvomUw8hMFIPOD0GYUaZiFy0xpQCislkWggRqicgp5vGo922AH13UlRPOC2WMh4pEUye4iagPD9NBd3WrecKRjpacWNgL4L+bTrbo/ffMfPuhyNWR4Cj/btx99Q+PWA6v4bj8XnkiwoCfh9ip/dj5NhOfTyxvIqhxwv671CgBW8v9Zo2kStuYNfUh5JNOALkAF+uHDE5u/78K+69+aY+O9m1Ay/O95QFnB4+YNoAT+S17MNqjgDZiTUIK3Dw/kdVxUqA1jH2lfm5jr7pz+pazwBlKt5+ncWtV9mKgJxarlOjcXmkVgrSGnWsoExFfsbBwoHteHru35+d5EoBJ+LzJarzfGNpyAhdAXJDLF49qjaGZpzqXGFDhaxm1ubxHJAdnjncblKrGpRxvFxjGOe4UlBzxOlkUCd2+dlyxTPSE0BO8csLh0qK3wjMnRpqb5HuoRKkJ4ActRIkdyg3D6scH+4y1axGPPRkAYmlVe+OmXLpvDnQidH+PSYIYzPwEcNqGxuLffHbqOfBp80H1NQ82xtApKMN0c42JJZ+qeejZnyG8iZ4jC2Z/Y1Mfk1/E3neJE6aw+4az2rQbsBa5zUBa1XMOt+2gv/ls5MoQxPRkhuMxvxwx9+rj7SACLhNm531BMqTn8K2rz5Up3x5VPBNEWhws24ZiCgjIGaVVuVabZdHdrZdpzkNfD9YJ4WqhWkqWE2hauN/AAezTzgdHLaZAAAAAElFTkSuQmCC" width="40" height="40"></a>
                        <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http://<?php echo $_SERVER['SERVER_NAME']; ?>/item-<?php echo $product['id'] ?>"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAIPSURBVGhD7ZnLSwJRGMWl/ojqj9GpVtEDZgTtvWghVKsWQcuQWkUPCAqEoNBdrcqZygKF3mEPMip30SIqjKDIIiJvc/VzpPwEnSHvTNwDv5V3jufMfSzm2ri4uLj+l1yuxUpBCrY5JCXgEJXNMuO3O5VW2/BwBcTRJ7u0VuMQ5aggKYQxh4JLroZYpYnOhDoLB4gpK/Z0zQxdTogZU9LLrFTRPYGZsYTuGYhXvARRCWNmTFEzQbzi5ZDkbdSMITQTxCtef12koz9CRiZPyfTcBfH54xp9Q7voeIqpirg9YXIceySF5AvE0ecopinS2Bkit/dJiIzLEkVm5y8hbmFZosjxWf6S+vpKkfOrJ3J0lkjjnThFn6WYpsjdwxvEz4nOEjYWwzRFnl8+IH5OQ6NRdCyGaYq8Jj8hfk6WKFLnXCXtvRGN5Ft+kbGZ2I8xTV0bqBeFWRF63Jaq0SkTbnY9RTyDO6gXxTJFUilCGtrXUS+KZYo8JN5RnyzMitQ6FdLcvaGBnVre8RPtd1oc88nCrMhvLHv8/oYXAXgRzMwIvAjAi2BmRuBFAF4EMzMCkyJ/8cnUaBF9n0xFxY+aGWApeE1WQjc/6BnYQsdi6PuIbcJrBUGSXRCveGWu3JS9fDNm6LvooYKrt33EtNzs1rcsV0EsnVLfglrGrZotwAVl2cj8p+w2fBnKxcXFZTLZbN9S86sn1aGunQAAAABJRU5ErkJggg==" width="40" height="40"></a>
                    </div>
                </div>
            </div>
            <div class="col s12 m4 l3">
                Рекомендуем
                <?php
                    foreach ($queryMethods->getRecommendedProduct($queryMethods->getChildArrayCategories($product['category_id'])) as $item)
                        echo $blocks->getShopItemBlock($item['id'], $item['name'], $item['image_prev'], $item['price'], $item['discount'], $item['text']);
                ?>
            </div>
        </div>
    </div>
</div>
