var $document = $(document);
var $window = $(window);
var $page = 50;

$document.ready(function() {

    $('.button-collapse').sideNav();
    $('.carousel.carousel-slider').carousel({fullWidth: true});
    $('.tooltipped').tooltip({delay: 50});
    $('.modal').modal();
    $('select').material_select();
    $.buttonScroll();

    setTimeout($.sliderNext, 10000);

    $(".tabs .tab a").click(function() {
        redirectPage($(this).attr('href'));

        if ($(this).attr('href') === '#product')
            $('#product-or-cat-add').attr('href', '#modal-product');

        if ($(this).attr('href') === '#cat')
            $('#product-or-cat-add').attr('href', '#modal-cat');

        if ($(this).attr('id') === 'btn-add-show') {
            if ($('#product-or-cat-add').hasClass('scale-out')) {
                $('#product-or-cat-add').removeClass('scale-out');
                $('#product-or-cat-add').addClass('scale-in');
            }
        }

        if ($(this).attr('id') === 'btn-add-hide') {
            if ($('#product-or-cat-add').hasClass('scale-in')) {
                $('#product-or-cat-add').removeClass('scale-in');
                $('#product-or-cat-add').addClass('scale-out');
            }
        }
    });

    $("#add-basket").click(function() {
        $.ajax({
            type: 'POST',
            url: '/index.php',
            data: 'ajax=true&action=add-basket&id=' + $("#add-basket").attr('product-id'),
            success: function(data) {
                if (data == 'success')
                    Materialize.toast('Товар был добавлен в корзину', 4000);
                else
                    Materialize.toast('Произошла ошибка, попробуйте еще раз', 4000);
            }
        });
    });

    $(".remove-basket").click(function() {
        $product = $("#product-id-" + $(this).attr('product-id'));
        $.ajax({
            type: 'POST',
            url: '/index.php',
            data: 'ajax=true&action=remove-basket&id=' + $(this).attr('product-id'),
            success: function(data) {
                Materialize.toast('Товар был удалён из корзины', 4000);
                $product.hide("fast");
                $('#product-sum').html(data);
            }
        });
    });

    $("#prod-more-btn").click(function() {

        var search = (location.search !== "" ? location.search.slice(1) : '');
        $('#prod-more-btn').hide();
        $('#prod-more-progress').show();

        $.ajax({
            type: 'POST',
            url: '/index.php',
            data: 'ajax=true&action=show-more&page=' + $page + '&cat-id=' + $("#cat-id").val() + '&' + search,
            success: function(data) {
                console.log(data);
                if (data != '404') {
                    $('#prod-more-blocks').html($('#prod-more-blocks').html() + data);
                    $page += 50;
                    $('#prod-more-btn').show();
                }
                else {
                    $('#prod-more').html('<br><h5 class="center-align grey-text">Товаров больше не найдено</h5><br><br>');
                }
                $('#prod-more-progress').hide();
            }
        });
    });

    $(".count-item").change(function() {
        $.ajax({
            type: 'POST',
            url: '/index.php',
            data: 'ajax=true&action=update-count-product&id=' + $(this).attr('item-id') + '&count=' + $(this).val(),
            success: function(data) {
                Materialize.toast('Кол-во товара было обновлено', 4000);
            }
        });
    });
});

$window.scroll(function() {
    $.buttonCheckScroll();
    $.navCheckScroll();
});

$.sliderNext = function() {
    $('.carousel').carousel('next');
    setTimeout($.sliderNext, 10000);
};

$.buttonAddBasket = function($id) {
    $.ajax({
        type: 'POST',
        url: '/index.php',
        data: 'ajax=true&action=add-basket&id=' + $id,
        success: function(data) {
            if (data == 'success')
                Materialize.toast('Товар был добавлен в корзину', 4000);
        }
    });
};

$.buttonScroll = function() {
    $('#scrollup').click( function() {
        $('html, body').animate({scrollTop: 0}, '500', 'swing');
        return false;
    });
};

$.buttonCheckScroll = function() {

    if ($document.scrollTop() > 100 ) {
        $('#scrollup').css('opacity', 1);
        $('#scrollup').removeClass('bounceOutDown');
        $('#scrollup').addClass('bounceInUp');
    } else {
        $('#scrollup').removeClass('bounceInUp');
        $('#scrollup').addClass('bounceOutDown');
    }
};

$.navCheckScroll = function() {

    if ($document.scrollTop() > 1 ) {
        $('nav').removeClass('z-depth-0');
        $('nav').addClass('z-depth-4');
    } else {
        $('nav').removeClass('z-depth-4');
        $('nav').addClass('z-depth-0');
    }
};

function redirectPage(url){
    var newUrlParts = url.split("#");
    var currentUrlParts = window.location.href.split("#");
    window.location.href = url;
    if(newUrlParts[0]==currentUrlParts[0])
        window.location.reload(true);
}