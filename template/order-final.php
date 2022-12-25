<?php
if (!defined('AppiEngine')) {
    header( "refresh:0; url=/");
}

global $queryMethods;
$id = -1;
if (isset($_GET['token'])) {
    $id = $queryMethods->getOrderByToken($_GET['token'])['id'];
}
?>
<div class="row" style="margin-top: 50px">
    <div class="col m3 l4"></div>
    <div class="col s6 l4">
        <div class="card-panel center">
            <h4>Заказ оформлен <i class="material-icons">sentiment_very_satisfied</i></h4>
            <h6>Спасибо за покупку <i class="material-icons red-text" style="font-size: 1rem;">favorite</i></h6>
            <div>
                Ваш номер заказа #<?php echo $id; ?>
            </div>
        </div>
    </div>
</div>
