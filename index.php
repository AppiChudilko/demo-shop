<?php
define("AppiEngine", true);

header('Powered: Alexander Pozharov');
header("Cache-control: public");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + 60 * 60 * 24) . " GMT");

spl_autoload_register(function($class) {
    include_once str_replace('\\', '/', $class) . '.php';
});

$langType = 'en';
if (isset($_COOKIE['lang']))
    if ($_COOKIE['lang'] == 'ru')
        $langType = 'ru';

include_once 'globals.php';
include_once 'lang/' . $langType . '.php';

use Server\Core\Init;
use Server\Core\EnumConst;
use Server\Core\QueryBuilder;
use Server\Core\Request;
use Server\Core\Template;
use Server\Core\Server;
use Server\Core\Settings;
use Server\Manager\PermissionManager;
use Server\Manager\RequestManager;
use Server\Manager\TemplateManager;
use Server\Blocks;
use Server\User;
use Server\Setting;
use Server\QueryMethods;

global $modal;
global $lang;
global $UTC_TO_TIME;
global $sections;
global $subSections;
global $userInfo;

$UTC = 0;
if (isset($_COOKIE['UTC']))
    $UTC = $_COOKIE['UTC'];
$UTC_TO_TIME = $UTC * 3600;

$init = new Init;
$init->initAppi();

$qb = new QueryBuilder();
$qb->connectDataBase(EnumConst::DB_HOST, EnumConst::DB_NAME, EnumConst::DB_USER, EnumConst::DB_PASS);

$queryMethods = new QueryMethods($qb);
$view = new Template('/template/');
$requests = new RequestManager();
$permissionManager = new PermissionManager();
$tmp = new TemplateManager($view, $init);
$request = new Request();
$server = new Server();
$blocks = new Blocks();
$settings = new Settings();
$user = new User($qb);
$requests->checkRequests($qb);
$webSetting = new Setting($qb);

if (isset($_POST['ajax'])) {
    switch ($_POST['action']) {
        case 'add-basket':
            if (isset($_SESSION['product' . $_POST['id']]))
                $_SESSION['product' . $_POST['id']] += 1;
            else
                $_SESSION['product' . $_POST['id']] = 1;
            echo 'success';
            break;
        case 'remove-basket':
            unset($_SESSION['product' . $_POST['id']]);
            $sum = 0;
            foreach ($_SESSION as $key => $value) {
                $product = $queryMethods->getProductById(preg_replace('/[^\d]/uix', '', $key));
                $newPrice = $product['price'] - ($product['price'] * $product['discount'] / 100);
                $sum += $newPrice * $value;
            }
            echo number_format($sum, 0, ',', ',');
            break;
        case 'update-count-product':
            if (!$user->isAuthUser())
                die(false);

            $ct = intval($_POST['count']);

            echo $qb
                ->createQueryBuilder('products')
                ->updateSql(array('count'), array($ct))
                ->where('id = \'' . intval($_POST['id']) . '\'')
                ->executeQuery()
                ->getResult()
            ;
            break;
        case 'show-more':
            $getChildCat = $queryMethods->getChildCategories($_POST['cat-id']);
            $getChildCatArr = $queryMethods->getChildArrayCategories($_POST['cat-id']);

            $maxPrice = $queryMethods->getMaxPriceByCategories($getChildCatArr);
            $maxPrice = $maxPrice['price'];
            $search = (isset($_POST['q']) ? $_POST['q'] : '');

            $filterPrice = (isset($_POST['max-price']) ? $_POST['max-price'] : $maxPrice);
            $filterSort = (isset($_POST['psort']) ? $_POST['psort'] : 0);
            $filterBrand = (isset($_POST['brand']) && is_array($_POST['brand']) ? $_POST['brand'] : array());
            $filterDiscount = (isset($_POST['discount']) ? 1 : 0);

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

            $products = $queryMethods->getAllFilterProductByCatId($getChildCatArr, $search, $filterPrice, $filterSort, $filterBrand, $filterDiscount, $_POST['page']);

            if (!empty($products)) {
                foreach ($products as $item)
                    echo $blocks->getColBlock($blocks->getShopItemBlock($item['id'], $item['name'], $item['image_prev'], $item['price'], $item['discount'], $item['text']), $item['width_s'], $item['width_m'], $item['width_l']);
            }
            else {
                echo '404';
            }
            break;
        default:
            echo '<h4 class="center">404 - Not found</h4>';
            break;
    }
    die;
}

$page = $request->getRequest(array('/'));
$view->set('siteName', $settings->getSiteName());
$view->set('version', $settings->getVersion());
$view->set('langType', $langType);
$view->set('metaImg', '/images/logoBG.png');
$view->set('title', $settings->getTitle());
$view->set('titleHtml', 'NotFound 404 | ' . $settings->getTitle());
$view->set('modal', $modal);
$view->set('error404', false);
$view->set('showAdminTab', false);

$designSetting = $webSetting->getDesign();

$view->set('colorMain', $designSetting['color_main']);
$view->set('colorBtn', $designSetting['color_btn']);
$view->set('colorMainText', $designSetting['color_main_text']);
$view->set('colorBtnText', $designSetting['color_btn_text']);

if (isset($page['p'])) {
    switch ($page['p']) {
        case 'debug':
            $tmp->setTitle('Debug');
            $tmp->showBlockPage('debug');
            break;
        case 'image':

            $width = 0;
            $height = 0;
            $isRes = false;

            $path = 'http://read.byappi.com/client/images/error-404.png';

            if (isset($page['width-']) && is_numeric($page['width-']))
                $width = $page['width-'];

            if (isset($page['height-']) && is_numeric($page['height-']))
                $height = $page['height-'];

            if (isset($_GET['url']))
                $path = $_GET['url'];

            if (isset($_GET['res']))
                $isRes = ($_GET['res'] == 'true') ? true : false;

            header('Content-type: image/png');

            try {
                $image = new Imagick($path);
            }
            catch (Exception $e) {
                header('Content-type: image/png');
                $image = new Imagick('http://read.byappi.com/client/images/error-404.png');
            }

            if($isRes == true)
                $image->cropImage(480, 260, 0, 50);
            elseif($width != 0 || $height != 0)
                $image->thumbnailImage($width, $height);

            echo $image;
            break;
        case 'basket':
            if (!empty($_SESSION))
                $tmp->showPage('basket', 'Корзина');
            else
                $tmp->showPage('basket-empty', 'Корзина');
            break;
        case 'order-final':
            $tmp->showPage('order-final', 'Завершение заказа');
            break;
        case 'admin':
            if ($user->isAuthUser()) {
                $view->set('showAdminTab', true);
                $tmp->showPage('admin/index', 'Главная | Админ Панель');
            }
            else
                $user->logout();
            break;
        case 'admin/login':
            if ($user->isAuthUser()) {
                $view->set('showAdminTab', true);
                $tmp->showPage('admin/index', 'Главная | Админ Панель');
            }
            else
                $tmp->showPage('admin/login', 'Авторизация | Админ Панель');
            break;
        case 'info':
            $tmp->setTitle('Info');
            $tmp->showBlockPage('info');
            break;
        case 'index':
            $tmp->showPage('index', $lang['main']);
            break;
        case 'category-all':
            $tmp->showPage('category-all', 'Список товаров');
            break;
        case 'category-top':
            $tmp->showPage('category-top', 'Топ 50 популярных товаров');
            break;
        case 'category-hot':
            $tmp->showPage('category-hot', 'Топ 50 горящих товаров');
            break;
        default:

            if ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/index.php' || $_SERVER['REQUEST_URI'] == 'index.php' || $_SERVER['REQUEST_URI'] == 'index') {
                $tmp->showPage('index', $lang['main']);
            } else {
                if (isset($page['category-'])) {
                    $catName = $queryMethods->getCategoryById($page['category-']);
                    $tmp->showPage('category',  $catName['name'] . ' | Каталог');
                } else if (isset($page['item-'])) {
                    $product = $queryMethods->getProductById($page['item-']);
                    if (!empty($product))
                        $tmp->showPage('item',  $product['name'] . ' | Товар');
                    else
                        $tmp->showError404Page();
                } else {
                    $tmp->showError404Page();
                    //$tmp->showPage('index', $lang['main']);
                }
            }
    }
}