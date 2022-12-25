<?php
if (!defined('AppiEngine')) {
    header( "refresh:0; url=/");
}

global $webSetting;
global $qb;

$footerInfo = $webSetting->getFooter();

$isShowLinks = !empty($footerInfo['link_name_1']) || !empty($footerInfo['link_name_2']) || !empty($footerInfo['link_name_3']) || !empty($footerInfo['link_name_4']) || !empty($footerInfo['link_name_5']);
$isShowContLinks = !empty($footerInfo['cont_link_name_1']) || !empty($footerInfo['cont_link_name_2']) || !empty($footerInfo['cont_link_name_3']) || !empty($footerInfo['cont_link_name_4']) || !empty($footerInfo['cont_link_name_5']);

?>

<footer class="page-footer grey lighten-4">
    <div class="container">
        <div class="row">
            <?php
                $about = $webSetting->getAboutUs();
                if (!empty($about)) {
                    echo '
                        <div class="col l6 s12">
                            <h5 class="grey-text text-darken-1">Коротко о нас</h5>
                            <p class="grey-text text-darken-1">' . htmlspecialchars_decode(nl2br($about)) . '</p>
                        </div>
                    ';
                }
                if ($isShowLinks) {
                    echo '
                        <div class="col l3 s12">
                            <h5 class="grey-text text-darken-1">Ссылки</h5>
                            <ul>
                                ' . (($footerInfo['link_name_1']) ? '<li><a class="grey-text text-darken-1" href="' . $footerInfo['link_1'] . '">' . $footerInfo['link_name_1'] . '</a></li>' : '' ) . '
                                ' . (($footerInfo['link_name_2']) ? '<li><a class="grey-text text-darken-1" href="' . $footerInfo['link_2'] . '">' . $footerInfo['link_name_2'] . '</a></li>' : '' ) . '
                                ' . (($footerInfo['link_name_3']) ? '<li><a class="grey-text text-darken-1" href="' . $footerInfo['link_3'] . '">' . $footerInfo['link_name_3'] . '</a></li>' : '' ) . '
                                ' . (($footerInfo['link_name_4']) ? '<li><a class="grey-text text-darken-1" href="' . $footerInfo['link_4'] . '">' . $footerInfo['link_name_4'] . '</a></li>' : '' ) . '
                                <li><a class="grey-text text-darken-1 modal-trigger" href="#modal-terms">Пользовательское соглашение</a></li>
                            </ul>
                        </div>
                    ';
                }
                if ($isShowContLinks) {
                    echo '
                        <div class="col l3 s12">
                            <h5 class="grey-text text-darken-1">Контакты</h5>
                            <ul>
                                ' . (($footerInfo['cont_link_name_1']) ? '<li><a class="grey-text text-darken-1" href="' . $footerInfo['cont_link_1'] . '">' . $footerInfo['cont_link_name_1'] . '</a></li>' : '' ) . '
                                ' . (($footerInfo['cont_link_name_2']) ? '<li><a class="grey-text text-darken-1" href="' . $footerInfo['cont_link_2'] . '">' . $footerInfo['cont_link_name_2'] . '</a></li>' : '' ) . '
                                ' . (($footerInfo['cont_link_name_3']) ? '<li><a class="grey-text text-darken-1" href="' . $footerInfo['cont_link_3'] . '">' . $footerInfo['cont_link_name_3'] . '</a></li>' : '' ) . '
                                ' . (($footerInfo['cont_link_name_4']) ? '<li><a class="grey-text text-darken-1" href="' . $footerInfo['cont_link_4'] . '">' . $footerInfo['cont_link_name_4'] . '</a></li>' : '' ) . '
                            </ul>
                        </div>
                    ';
                }
            ?>
        </div>
    </div>
    <div class="footer-copyright grey lighten-4">
        <div class="container grey-text">
            Copyright © <?php echo gmdate('Y'); ?>  <a class="grey-text" target="_blank" href="https://vk.com/lo1ka">Alexander Pozharov <i class="material-icons red-text" style="font-size: 14px;">favorite</i></a>
            <?php
                if(!$isShowLinks)
                    echo '<a class="grey-text modal-trigger right" href="#modal-terms">Пользовательское соглашение</a>';
            ?>
        </div>
    </div>
</footer>

<!--  Scripts-->
<script src="/client/js/materialize.js"></script>
<script src="/client/js/material-appi.js"></script>
<script src="/client/js/main.js?v=2"></script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter46888713 = new Ya.Metrika({
                    id:46888713,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/46888713" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<div id="modal-terms" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Пользовательское соглашение</h4>
        <p><?php echo htmlspecialchars_decode(htmlspecialchars_decode(nl2br($webSetting->getTerms()))); ?></p>
        <hr>
        <p>
            Copyright (c) <?php echo gmdate('Y'); ?> <a target="_blank" href="https://vk.com/lo1ka">Alexander Pozharov <i class="material-icons red-text" style="font-size: 14px;">favorite</i></a>
            <br>
            <br>Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, слияние копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:
            <br>
            <br>Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.
            <br>
            <br>ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ.
        </p>
    </div>
    <div class="modal-footer">
        <a style="cursor: pointer" class="modal-action modal-close waves-effect btn-flat">Закрыть</a>
    </div>
</div>


<a id="scrollup" class="z-depth-4 animated btn-floating btn-large waves-effect <?php echo $this->colorBtn; ?> lighten-1"><i class="material-icons <?php echo $this->colorBtnText; ?>" style="font-size: 56px;">keyboard_arrow_up</i></a>

<?php
if($this->modal['show']) {
    //echo '<script type="text/javascript">$(document).ready(function(){ $("#modalInfo").openModal(); });</script>';
    echo '<script type="text/javascript">Materialize.toast(\'' . $this->modal['text'] . '\', 4000)</script>';
}
if($this->showAdminTab == true) {

    $qb
        ->createQueryBuilder('')
        ->otherSql("set session sql_mode=''", false)
        ->executeQuery()
        ->getResult()
    ;

    $brands = $qb
        ->createQueryBuilder('products')
        ->selectSql('brand')
        ->groupBy('brand')
        ->orderBy('brand ASC')
        ->executeQuery()
        ->getResult()
    ;

    $resultBrand = '';
    foreach ($brands as $brandItem)
        $resultBrand .= '"' . $brandItem['brand'] . '": null,';

    echo '
    <script>
        $(document).ready(function() {
            $(\'input.autocomplete\').autocomplete({
                data: {
                  ' . $resultBrand . '
                },
                limit: 100, // The max amount of results that can be shown at once. Default: Infinity.
                minLength: 1 // The minimum length of the input for the autocomplete to start. Default: 1.
            });
        });
    </script>
    ';
}
?>

</body>
</html>