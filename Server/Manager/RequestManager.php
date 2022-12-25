<?php

namespace Server\Manager;

use Server\Core\QueryBuilder;

if (!defined('AppiEngine')) {
    header( "refresh:0; url=/");
}

/**
 * Request
 */
class RequestManager
{
    protected $qb;

    /**
     * @param QueryBuilder $qb
     */
    public function checkRequests(QueryBuilder $qb) {
        $this->qb = $qb;
        global $user;

        if (isset($_COOKIE['user'])) {
            global $userInfo;

            $userInfoTemp = $user->getUserInfoByToken($_COOKIE['user']);
            if ($userInfoTemp['token'] != $_COOKIE['user'])
                $user->logout();
            else
                $userInfo = $userInfoTemp;
        }

        if(!empty($_POST)) {
            if (isset($_POST['admin-login']))
                $this->adminLogin();
            if (isset($_POST['new-order']))
                $this->newOrder();

            if ($user->isAuthUser()) {
                if (isset($_POST['save-dsg']))
                    $this->saveDesign();
                if (isset($_POST['save-terms']))
                    $this->saveTerms();
                if (isset($_POST['save-about-us']))
                    $this->saveAboutUs();
                if (isset($_POST['save-link']))
                    $this->saveLinks();
                if (isset($_POST['save-cont-link']))
                    $this->saveContLinks();
                if (isset($_POST['save-slider-img']))
                    $this->saveSliderImg();
                if (isset($_POST['add-cat']))
                    $this->addCategory();
                if (isset($_POST['edit-cat']))
                    $this->editCategory();
                if (isset($_POST['del-cat']))
                    $this->deleteCategory();
                if (isset($_POST['add-product']))
                    $this->addProduct();
                if (isset($_POST['edit-product']))
                    $this->editProduct();
                if (isset($_POST['del-product']))
                    $this->deleteProduct();
            }
        }
    }

    private function newOrder() {
        global $modal;
        global $server;
        global $queryMethods;

        $success = false;

        if (!empty($_SESSION)) {
            $sum = 0;
            $prods = '';
            foreach ($_SESSION as $key => $value) {
                $key = preg_replace('/[^\d]/uix', '', $key);
                $product = $queryMethods->getProductById($key);
                $newPrice = $product['price'] * $product['discount'] / 100;
                $newPrice = $product['price'] - $newPrice;
                $sum += $newPrice * $value;
                $newPrice = number_format($newPrice, 0, ',', ',');
                $prods .= '<br><a target="_blank" href="/item-' . $product['id'] . '">' . $product['name'] . ', ' . $newPrice . 'р. (' . $value . 'шт.) </a>';
            }

            $token = md5(time() + $sum + $_POST['name'] + $_POST['email']);

            $success = $queryMethods->addOrder(
                $server->charsString($_POST['name']),
                $server->charsString($_POST['email']),
                $server->charsString($_POST['phone']),
                $server->charsString($_POST['desc']),
                $prods,
                $sum,
                $token
            );
            session_destroy();
            header('location: /order-final?token=' . $token);
        }

        $modal['show'] = true;
        $modal['success'] = $success;

        if ($success) {
            $modal['title'] = 'Ура!!';
            $modal['text'] = 'Заказ был оформлен';
        }
        else {
            $modal['title'] = 'Упс!!';
            $modal['text'] = 'Ошибка оформления заказа';
        }
    }

    private function deleteProduct() {

        global $modal;
        global $queryMethods;

        //TODO fix
        $modal['show'] = true;
        $modal['success'] = true;
        $modal['title'] = 'Ура!!';
        $modal['text'] = 'Это действие не доступно в демо версии';
        return;

        $success = $queryMethods->deleteProduct(
            intval($_POST['id'])
        );

        $modal['show'] = true;
        $modal['success'] = $success;

        if ($success) {
            $modal['title'] = 'Ура!!';
            $modal['text'] = 'Продукт успешно удален';
        }
        else {
            $modal['title'] = 'Упс!!';
            $modal['text'] = 'Произошла ошибка удаления товара';
        }
    }

    private function editProduct() {
        global $modal;
        global $server;
        global $queryMethods;

        $success = $queryMethods->editProduct(
            intval($_POST['id']),
            $server->charsString($_POST['name']),
            intval($_POST['price']),
            intval($_POST['sale']),
            $server->charsString($_POST['brand']),
            intval($_POST['count']),
            $server->charsString($_POST['desc-full']),
            $server->charsString($_POST['desc-1']),
            $server->charsString($_POST['desc-2']),
            $server->charsString($_POST['desc-3']),
            intval($_POST['cat']),
            $server->charsString($_POST['url-prev']),
            $server->charsString($_POST['url-1']),
            $server->charsString($_POST['url-2']),
            $server->charsString($_POST['url-3']),
            $server->charsString($_POST['width-s']),
            $server->charsString($_POST['width-m']),
            $server->charsString($_POST['width-l'])
        );

        $modal['show'] = true;
        $modal['success'] = $success;

        if ($success) {
            $modal['title'] = 'Ура!!';
            $modal['text'] = 'Продукт успешно отредактирован';
        }
        else {
            $modal['title'] = 'Упс!!';
            $modal['text'] = 'Произошла ошибка редактирования товара';
        }
    }

    private function addProduct() {
        global $modal;
        global $server;
        global $queryMethods;

        $success = $queryMethods->addProduct(
            $server->charsString($_POST['name']),
            intval($_POST['price']),
            intval($_POST['sale']),
            $server->charsString($_POST['brand']),
            intval($_POST['count']),
            $server->charsString($_POST['desc-full']),
            $server->charsString($_POST['desc-1']),
            $server->charsString($_POST['desc-2']),
            $server->charsString($_POST['desc-3']),
            intval($_POST['cat']),
            $server->charsString($_POST['url-prev']),
            $server->charsString($_POST['url-1']),
            $server->charsString($_POST['url-2']),
            $server->charsString($_POST['url-3']),
            $server->charsString($_POST['width-s']),
            $server->charsString($_POST['width-m']),
            $server->charsString($_POST['width-l'])
        );

        $modal['show'] = true;
        $modal['success'] = $success;

        if ($success) {
            $modal['title'] = 'Ура!!';
            $modal['text'] = 'Продукт успешно добавлен';
        }
        else {
            $modal['title'] = 'Упс!!';
            $modal['text'] = 'Произошла ошибка добавления товара';
        }
    }

    private function deleteCategory() {
        global $modal;
        global $queryMethods;

        $success = $queryMethods->deleteCategory(intval($_POST['id']));

        $modal['show'] = true;
        $modal['success'] = $success;

        if ($success) {
            $modal['title'] = 'Ура!!';
            $modal['text'] = 'Категория успешна удалена';
        }
        else {
            $modal['title'] = 'Упс!!';
            $modal['text'] = 'Произошла ошибка редактирования удаления';
        }
    }

    private function editCategory() {
        global $server;
        global $modal;
        global $queryMethods;

        $success = $queryMethods->editCategory($server->charsString($_POST['name']), intval($_POST['id']));

        $modal['show'] = true;
        $modal['success'] = $success;

        if ($success) {
            $modal['title'] = 'Ура!!';
            $modal['text'] = 'Категория успешна отредактированна';
        }
        else {
            $modal['title'] = 'Упс!!';
            $modal['text'] = 'Произошла ошибка редактирования категории';
        }
    }

    private function addCategory() {
        global $server;
        global $modal;
        global $queryMethods;

        $success = $queryMethods->addCategory($server->charsString($_POST['name']), intval($_POST['parent']));

        $modal['show'] = true;
        $modal['success'] = $success;

        if ($success) {
            $modal['title'] = 'Ура!!';
            $modal['text'] = 'Категория успешна создана';
        }
        else {
            $modal['title'] = 'Упс!!';
            $modal['text'] = 'Произошла ошибка создания категории';
        }
    }

    private function saveSliderImg() {
        global $server;
        global $qb;
        global $modal;

        $link1 = $server->charsString($_POST['link-1']);
        $linkName1 = $server->charsString($_POST['link-info-1']);

        $link2 = $server->charsString($_POST['link-2']);
        $linkName2 = $server->charsString($_POST['link-info-2']);

        $link3 = $server->charsString($_POST['link-3']);
        $linkName3 = $server->charsString($_POST['link-info-3']);

        $link4 = $server->charsString($_POST['link-4']);
        $linkName4 = $server->charsString($_POST['link-info-4']);

        $link5 = $server->charsString($_POST['link-5']);
        $linkName5 = $server->charsString($_POST['link-info-5']);

        $success = $qb
            ->createQueryBuilder('setting_main_slider')
            ->updateSql(array(
                'image_1',
                'image_link_1',
                'image_2',
                'image_link_2',
                'image_3',
                'image_link_3',
                'image_4',
                'image_link_4',
                'image_5',
                'image_link_5'
            ), array(
                $link1,
                $linkName1,
                $link2,
                $linkName2,
                $link3,
                $linkName3,
                $link4,
                $linkName4,
                $link5,
                $linkName5
            ))
            ->executeQuery()
            ->getResult()
        ;

        $modal['show'] = true;
        $modal['success'] = $success;

        if ($success) {
            $modal['title'] = 'Ура!!';
            $modal['text'] = 'Информация была обновлена';
        }
        else {
            $modal['title'] = 'Упс!!';
            $modal['text'] = 'Ошибка обновления информации';
        }
    }

    private function saveContLinks() {
        global $server;
        global $qb;
        global $modal;

        $link1 = $server->charsString($_POST['link-1']);
        $linkName1 = $server->charsString($_POST['link-name-1']);

        $link2 = $server->charsString($_POST['link-2']);
        $linkName2 = $server->charsString($_POST['link-name-2']);

        $link3 = $server->charsString($_POST['link-3']);
        $linkName3 = $server->charsString($_POST['link-name-3']);

        $link4 = $server->charsString($_POST['link-4']);
        $linkName4 = $server->charsString($_POST['link-name-4']);

        $link5 = $server->charsString($_POST['link-5']);
        $linkName5 = $server->charsString($_POST['link-name-5']);

        $success = $qb
            ->createQueryBuilder('setting_footer')
            ->updateSql(array(
                'cont_link_1',
                'cont_link_name_1',
                'cont_link_2',
                'cont_link_name_2',
                'cont_link_3',
                'cont_link_name_3',
                'cont_link_4',
                'cont_link_name_4',
                'cont_link_5',
                'cont_link_name_5'
            ), array(
                $link1,
                $linkName1,
                $link2,
                $linkName2,
                $link3,
                $linkName3,
                $link4,
                $linkName4,
                $link5,
                $linkName5
            ))
            ->executeQuery()
            ->getResult()
        ;

        $modal['show'] = true;
        $modal['success'] = $success;

        if ($success) {
            $modal['title'] = 'Ура!!';
            $modal['text'] = 'Информация была обновлена';
        }
        else {
            $modal['title'] = 'Упс!!';
            $modal['text'] = 'Ошибка обновления информации';
        }
    }

    private function saveLinks() {
        global $server;
        global $qb;
        global $modal;

        $link1 = $server->charsString($_POST['link-1']);
        $linkName1 = $server->charsString($_POST['link-name-1']);

        $link2 = $server->charsString($_POST['link-2']);
        $linkName2 = $server->charsString($_POST['link-name-2']);

        $link3 = $server->charsString($_POST['link-3']);
        $linkName3 = $server->charsString($_POST['link-name-3']);

        $link4 = $server->charsString($_POST['link-4']);
        $linkName4 = $server->charsString($_POST['link-name-4']);

        $success = $qb
            ->createQueryBuilder('setting_footer')
            ->updateSql(array(
                'link_1',
                'link_name_1',
                'link_2',
                'link_name_2',
                'link_3',
                'link_name_3',
                'link_4',
                'link_name_4'
            ), array(
                $link1,
                $linkName1,
                $link2,
                $linkName2,
                $link3,
                $linkName3,
                $link4,
                $linkName4
            ))
            ->executeQuery()
            ->getResult()
        ;

        $modal['show'] = true;
        $modal['success'] = $success;

        if ($success) {
            $modal['title'] = 'Ура!!';
            $modal['text'] = 'Информация была обновлена';
        }
        else {
            $modal['title'] = 'Упс!!';
            $modal['text'] = 'Ошибка обновления информации';
        }
    }

    private function saveAboutUs() {
        global $server;
        global $qb;
        global $modal;

        $text = $server->charsString($_POST['text']);

        $success = $qb
            ->createQueryBuilder('setting_footer')
            ->updateSql(array(
                'about_us'
            ), array(
                $text,
            ))
            ->executeQuery()
            ->getResult()
        ;

        $modal['show'] = true;
        $modal['success'] = $success;

        if ($success) {
            $modal['title'] = 'Ура!!';
            $modal['text'] = 'Информация была обновлена';
        }
        else {
            $modal['title'] = 'Упс!!';
            $modal['text'] = 'Ошибка обновления информации';
        }
    }

    private function saveTerms() {
        global $server;
        global $qb;
        global $modal;

        $text = $server->charsString($_POST['text']);

        $success = $qb
            ->createQueryBuilder('setting_footer')
            ->updateSql(array(
                'terms'
            ), array(
                $text,
            ))
            ->executeQuery()
            ->getResult()
        ;

        $modal['show'] = true;
        $modal['success'] = $success;

        if ($success) {
            $modal['title'] = 'Ура!!';
            $modal['text'] = 'Правила сайта были обновлены';
        }
        else {
            $modal['title'] = 'Упс!!';
            $modal['text'] = 'Ошибка обновления правил сайта';
        }
    }

    private function saveDesign() {
        global $server;
        global $qb;
        global $modal;

        $colorBtn = $server->charsString($_POST['color-btn']);
        $colorBtnText = 'white-text';
        $colorMain = $server->charsString($_POST['color-main']);
        $colorMainText = 'white-text';

        switch ($colorMain) {
            case 'yellow':
            case 'lime':
            case 'light-green':
            case 'white':
                $colorMainText = 'black-text';
                break;
        }
        switch ($colorBtn) {
            case 'yellow':
            case 'lime':
            case 'light-green':
            case 'white':
                $colorBtnText = 'black-text';
                break;
        }

        $success = $qb
            ->createQueryBuilder('setting_design')
            ->updateSql(array(
                'color_main',
                'color_main_text',
                'color_btn',
                'color_btn_text'
            ), array(
                $colorMain,
                $colorMainText,
                $colorBtn,
                $colorBtnText,
            ))
            ->executeQuery()
            ->getResult()
        ;

        $modal['show'] = true;
        $modal['success'] = $success;

        if ($success) {
            $modal['title'] = 'Ура!!';
            $modal['text'] = 'Дизайн успешно изменён';
        }
        else {
            $modal['title'] = 'Упс!!';
            $modal['text'] = 'Ошибка смены дизайна';
        }
    }

    private function adminLogin() {
        global $server;
        global $user;
        global $modal;

        $login = $server->charsString($_POST['login']);
        $password = $server->charsString($_POST['password']);
        $success = $user->auth($login, $password);

        if (!$success) {
            $modal['show'] = true;
            $modal['success'] = $success;
            $modal['title'] = 'Ура!!';
            $modal['text'] = 'Неверно введен логин или пароль';
        }
    }
}