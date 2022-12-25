<?php
if (!defined('AppiEngine')) {
    header( "refresh:0; url=/");
}

global $blocks;
global $queryMethods;
global $page;
global $product;

$logo = isset($page['item-']) ? $product['image_prev'] : 'http://icons.iconarchive.com/icons/graphicloads/100-flat/256/cart-add-icon.png';
?>
<!--

Фея Винкс всегда на страже вашей страницы

``````````{\
````````{\{*\
````````{*\~\__&&&
```````{```\`&&&&&&.
``````{~`*`\((((((^^^)
`````{`*`~((((((( ♛ ♛
````{`*`~`)))))))). _' )
````{*```*`((((((('\ ~
`````{~`*``*)))))`.&
``````{.*~``*((((`\`\)) ?
````````{``~* ))) `\_.-'``
``````````{.__ ((`-*.*
````````````.*```~``*.
``````````.*.``*```~`*.
`````````.*````.````.`*.
````````.*``~`````*````*.
```````.*``````*`````~``*.
`````.*````~``````.`````*.
```.*```*```.``~```*``~ *.¤´҉ .

-->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
    <title><?php echo $this->titleHtml; ?></title>

    <meta property="og:title" content="<?php echo $this->titleHtml; ?>" />
    <meta property="og:description" content="Desc" />
    <meta property="og:site_name" content="<?php echo $this->title; ?>">
    <meta property="og:type" content="top">
    <meta property="og:url" content="<?php echo $this->siteName ?>">
    <meta property="og:image" content="<?php echo $logo; ?>">

    <meta name="description" content="Test">
    <meta name="keywords" content="Key" />
    <meta name="generator" content="Appi <?php echo $this->version ?>">
    <meta name="theme-color" content="#ffffff">

    <link rel="shortcut icon" href="http://icons.iconarchive.com/icons/graphicloads/100-flat/256/cart-add-icon.png" type="image/x-icon" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- CSS  -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="/client/css/material.min.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <link href="/client/css/material-charts.css" rel="stylesheet" media="screen,projection">
    <link href="/client/css/material-appi.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <link href="/client/css/style.css?v=3" type="text/css" rel="stylesheet" media="screen,projection"/>
    <link href="/client/css/animate.css" type="text/css" rel="stylesheet" media="screen,projection"/>

    <?php
        if ($this->colorMain == 'white' || $this->colorMain == 'yellow' || $this->colorMain == 'lime' || $this->colorMain == 'light-green') {
            echo '
                <style>
                    .indicator {
                        background-color: #000 !important;
                    }
                </style>
            ';
        }
    ?>

</head>
<body class="grey lighten-4">
<div class="navbar-fixed">
    <nav class="<?php echo $this->colorMain; ?> z-depth-0 nav-extended" role="navigation">
        <div class="nav-wrapper container">
            <a id="logo-container" href="/" class="brand-logo black-text hide-on-med-and-down">
                <?php //TODO fix ?>
                <img src="http://icons.iconarchive.com/icons/graphicloads/100-flat/256/cart-add-icon.png" class="logo">
            </a>
            <vr class="hide-on-med-and-down" style="margin-left: 70px"></vr>
            <ul class="hide-on-med-and-down">
                <li><a href="/" class="<?php echo $this->colorMainText; ?>">Главная</a></li>
                <li><a href="/" class="<?php echo $this->colorMainText; ?> dropdown-button" data-activates="dropdown-category">Категории<i class="material-icons right">arrow_drop_down</i></a></li>
                <li>
                    <a href="/basket" class="<?php echo $this->colorMainText; ?>">
                        <i class="material-icons">shopping_cart</i>
                        <?php
                            if(!empty($_SESSION))
                                echo '<div class="basket-count z-depth-1">' . count($_SESSION) . '</div>';
                        ?>
                    </a>
                </li>
            </ul>

            <form <?php echo ($page['p'] == '/' || $page['p'] == 'index' ? 'action="/category-all"' : '') ?> class="right grey lighten-4 search-input hoverable-z1 focusable-z1">
                <div class="input-field">
                    <input id="search" placeholder="Поиск" type="search" name="q" value="<?php echo (isset($_GET['q'])) ? $_GET['q'] : ''; ?>">
                    <label style="top: -12px;" class="label-icon active" for="search"><i class="search-i material-icons grey-text">search</i></label>
                </div>
            </form>
            <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="<?php echo $this->colorMainText; ?> material-icons">menu</i></a>
        </div>
        <?php
        if($this->showAdminTab == true) {
            echo '     
                <div class="nav-content container">
                    <ul class="tabs tabs-transparent">
                        <li class="tab"><a class="' . $this->colorMainText . '" id="btn-add-hide" href="#order">Заказы</a></li>
                        <li class="tab"><a class="' . $this->colorMainText . '" id="btn-add-show" href="#product">Товары</a></li>
                        <li class="tab"><a class="' . $this->colorMainText . '" id="btn-add-show" href="#cat">Категории</a></li>
                        <li class="tab"><a class="' . $this->colorMainText . '" id="btn-add-hide" href="#stock">Склад</a></li>
                        <li class="tab"><a class="' . $this->colorMainText . '" id="btn-add-hide" href="#settings">Настройки</a></li>
                    </ul>
                    <a style="transition: .3s all !important;" href="#modal-product" id="product-or-cat-add" class="modal-trigger btn-floating scale-transition scale-out btn-large halfway-fab waves-effect waves-light z-depth-4 ' . $this->colorBtn . '  lighten-1 ' . $this->colorBtnText . '">
                        <i class="material-icons">add</i>
                    </a>
                </div>
                
            ';
        }
        ?>
    </nav>
</div>
<ul id="nav-mobile" class="side-nav">
    <li>
        <div class="userView" style="height: 150px;">
            <div class="background">
                <?php //TODO fix ?>
                <img src="http://2.bp.blogspot.com/-Us3nTEJ_--w/VDFtduOSNjI/AAAAAAAAb6Y/joiz7UPkV38/s1600/image_new-103.jpg" style="width: 100%;">
            </div>
            <a href="/"><img class="circle" style="border-radius: 0;" src="http://icons.iconarchive.com/icons/graphicloads/100-flat/256/cart-add-icon.png"></a>
        </div>
    </li>
    <li><a href="/" class="black-text">Главная</a></li>
    <li><div class="divider"></div></li>
    <li><a class="subheader">Категории</a></li>
    <li><a href="/category-hot" class="black-text">Горящие товары</a></li>
    <li><a href="/category-top" class="black-text">Популярные товары</a></li>
    <?php
    foreach ($queryMethods->getCategories(0) as $item)
        echo '<li><a class="black-text" href="/category-' . $item['id'] . '">' . $item['name'] . '</a></li>';
    ?>
</ul>
<ul id="dropdown-category" class="dropdown-content">
    <?php
    foreach ($queryMethods->getCategories(0) as $item)
        echo '<li class="' . $this->colorMain . '"><a class="' . $this->colorMainText . '" href="/category-' . $item['id'] . '">' . $item['name'] . '</a></li>';
    ?>
</ul>