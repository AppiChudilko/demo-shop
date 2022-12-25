<?php

namespace Server;

use Server\Core\EnumConst;
use Server\Core\QueryBuilder;
use Server\Core\Server;

if (!defined('AppiEngine')) {
    header( "refresh:0; url=/");
}

/**
 * Blocks
 */
class Blocks
{
    /**
     * @param $id
     * @param $name
     * @param $img
     * @param $price
     * @param int $sale
     * @param string $desc
     * @param bool $canEdit
     * @return string
     * @internal param $item
     */
    public function getShopItemBlock($id, $name, $img, $price, $sale = 0, $desc = 'Лучшее качество товара', $canEdit = false) {

        global $designSetting;

        $oldPrice = $price;
        $newPrice = $price * $sale / 100;
        $newPrice = $price - $newPrice;
        $price =  number_format($price, 0, ',', ',');
        $newPrice =  number_format($newPrice, 0, ',', ',');
        return '
            <div itemscope itemtype="http://schema.org/Product" class="card card-very-small hoverable-z3">
                ' . (($canEdit) ? '
                    <div style="display: block" class="card-image black">
                        <img itemprop="image" src="' . $img . '">
                        ' . ($sale != 0 ? '<div class="sale" title="Скидка ' . $sale . '%">' . $sale . '%</div>' : '') . '
                        ' . ($canEdit ? '<a style="right: 8px;" href="?item-edit=' . $id . '#product" class="modal-trigger btn-floating halfway-fab waves-effect waves-light z-depth-1 ' . $designSetting['color_btn'] . ' lighten-1 ' . $designSetting['color_btn_text'] . '"><i class="material-icons">edit</i></a>' : '') . '
                    </div>
                ' : '
                    <a href="/item-' . $id . '" style="display: block" class="card-image hoverable-black black">
                        <img itemprop="image" src="' . $img . '">
                        ' . ($sale != 0 ? '<div class="sale" title="Скидка ' . $sale . '%">' . $sale . '%</div>' : '') . '
                    </a>
                '). '
                <div class="card-content">
                    <div class="hide" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                        <span itemprop="price">' . $oldPrice . '</span>
                        <span itemprop="priceCurrency">RUB</span>
                    </div>
                    <div class="no-wrap">
                        <div class="shadow-block"></div>
                        <span itemprop="name" title="' . $name . '">' . $name . '</span><br>
                        <label class="black-text">Цена: ' . ($sale != 0 ? '<label style="text-decoration: line-through;">' . $price . 'p.</label> ' . $newPrice . 'p' : $price . 'p' ) . '. </label>
                    </div>
                    <div class="hide" itemprop="description">' . $desc . '</div>
                </div>
            </div>
        ';
    }

    /**
     * @param $content
     * @param $s
     * @param $m
     * @param $l
     * @return string
     */
    public function getColBlock($content, $s = 12, $m = 6, $l = 4) {
        return '<div class="col s' . $s . ' m' . $m . ' l' . $l . '">' . $content . '</div>';
    }

    /**
     * @return string
     */
    public function getRandomColor() {
        $colors = array(
            'red',
            'blue',
            'light-blue',
            'indigo',
            'purple',
            'deep-orange',
            'deep-purple',
            'brown',
            'black',
            'teal',
            'green',
            'pink',
            'amber',
            'grey',
            'lime',
            'cyan',
            'yellow',
            'orange',
            'teal',
            'light-green',
        );
        return $colors[rand(0, 19)];
    }

    public function getPlayerComments($serverId) {

        global $qb;
        global $server;

        $result = $qb->createQueryBuilder('comments')->selectSql()->limit(50)->orderBy('id DESC')->where('server_id = \'' . $serverId . '\'')->executeQuery()->getResult();

        if (!empty($result)) {
            $comments = '<ul class="card collection" style="border: 0">';

            foreach ($result as $item) {

                $colors = array(
                    'red',
                    'blue',
                    'indigo',
                    'purple',
                    'deep-orange',
                    'deep-purple',
                    'brown',
                    'black',
                    'teal',
                    'green',
                );

                $comments .= '
                    <li class="collection-item avatar" style="min-height: 64px;">
                        <i class="material-icons circle ' . $colors[rand(0, 9)] . '">account_circle</i>
                        <b class="title">' . $item['nick'] . '</b>
                        <p>' . htmlspecialchars_decode(nl2br($item['text'])) . '
                        <br>
                        <label>' . $server->timeStampToTime($item['datetime']) . ' ' . $server->timeStampToDate($item['datetime']) . ' (UTC)</label>
                        </p>
                        ';

                        if ((isset($_COOKIE['_ym_uid']) && $_COOKIE['_ym_uid'] == $item['cookie']) || $server->getClientIp() == $item['ip']) {
                            $comments .= '<form method="post">
                                <input type="hidden" name="id" value="' . $item['id'] . '">
                                <button name="delete-comment" class="secondary-content white z-depth-0 btn" style="padding: 0 10px; border-radius: 50%;"><i class="material-icons black-text">close</i></button>
                            </form>';
                        }

                  $comments .= '</li>';
            }

            $comments .= '</ul>';

            return $comments;
        }

        return '';
    }
}