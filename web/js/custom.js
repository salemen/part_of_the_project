// открытие модальных окон
function showSearchsearch(cart){
    $('#searchsearch .modal-body').html(cart);
    $('#searchsearch').modal();
}

function showHelp(cart){
    $('#help .modal-body').html(cart);
    $('#help').modal();
}

function showMod(cart){
    $('#mod .modal-body').html(cart);
    $('#mod').modal();
}
// custom alert
alert = function(message) {
    $.alert({
        buttons: {
            ok: {
                btnClass: "btn-primary",
                text: "ok"
            }
        },
        content: message,
        theme: "modern",
        title: false
    });
};

// перезагрузка страницы после закрытия модального окна
// $('#modal-form').on('hidden.bs.modal', function() {
//     location.reload();
// })

// custom confirm
confirm = function(message, ok, cancel) {
    $.confirm({
        buttons: {
            ok: {
                action: function() {
                    !ok || ok();
                },
                btnClass: "btn-primary",
                text: "да"
            },
            cancel: {
                action: function() {
                    !cancel || cancel();
                },
                btnClass: "btn-default",
                text: "нет"
            }
        },
        content: message,
        theme: "modern",
        title: "Внимание!"
    });
};

// custom yii2 confirm
yii.confirm = function(message, ok, cancel) {
    $.confirm({
        buttons: {
            ok: {
                action: function() {
                    !ok || ok();
                },
                btnClass: "btn-primary",
                text: "да"
            },
            cancel: {
                action: function() {
                    !cancel || cancel();
                },
                btnClass: "btn-default",
                text: "нет"
            }
        },
        content: message,
        theme: "modern",
        title: "Внимание!"
    });
};

// clear modal on close
$("#modal-form").on("hidden.bs.modal", function() {
    $(".modal-body").html("");
});

// toggle aside div
$(document).on("click", ".btn-aside-toggle, .btn-aside-toggle-mobile", function(e) {
    $("body").toggleClass("aside-column-active");
    $(".aside-column").toggleClass("active");
    e.preventDefault();
});

// show fake message
$(document).on("click", ".btn-fake", function(e) {
    $("#modal-form").modal();
    $(".modal-body").html('<div style="padding: 40px; text-align: center;"><i class="fa fa-spinner fa-spin fa-3x fa-fw" style="color: #193e85;"></i></div>');
    e.preventDefault();
});

// call login action on click
$(document).on("click", ".btn-login", function(e) {
    var loginBtn = $("#btn-login");
    var redirectUrl = $(this).attr("href");

    loginBtn.attr("url-redirect", redirectUrl);
    loginBtn.click();
    e.preventDefault();
});

// show magnific popup
$(document).on("click", ".btn-magnific", function(e) {
    $(this).magnificPopup({ type: "image" }).magnificPopup("open");
    e.preventDefault();
});

// show modal popup
$(document).on("click", ".btn-modal", function(e) {
    var redirectUrl = (typeof $(this).attr("url-redirect") !== typeof undefined) ? $(this).attr("url-redirect") : true;
    var url = $(this).attr("href");

    if (redirectUrl != false) {
        url = setRedirectUrl(url, redirectUrl);
    }

    $("#modal-form").modal();
    $(".modal-body").html('<div style="padding: 40px; text-align: center;"><i class="fa fa-spinner fa-spin fa-3x fa-fw" style="color: #193e85;"></i></div>');
    $(".modal-body").load(url, { params: $(this).data() }, function(response, status, xhr) {
        if (status === 'error') {
            $(this).html(response);
        }
    });

    e.preventDefault();
});

// show or hide password in input
$(document).on("click", ".btn-pass-reveal", function() {
    var input = $(this).parents("div.input-group").find("input");
    var type = input.attr('type');

    input.attr("type", (type === "text") ? "password" : "text");
});

// scroll page to top
$(document).on("click", ".btn-scrolltop", function(e) {
    $("html, body").animate({ scrollTop: 0 }, 500);
    e.preventDefault();
});

// mailto email
$(document).on("click", ".mail-to-link", function(e) {
    var el = $(this);
    var param = el.attr("param");

    if (param) {
        $.ajax({
            data: { param: param },
            method: "post",
            success: function(res) {
                el.parent().html("<a href='mailto:" + res + "'>" + res + "</a>");
            },
            url: "/site/app-params"
        });
    }
    e.preventDefault();
});

$("#affixBlock").affix({
    offset: {
        bottom: function() {
            return $('.main-footer').outerHeight(true);
        },
        top: function() {
            return $('.main-header').outerHeight(true);
        }
    }
}).on('affix.bs.affix', function() {
    $(this).css({ 'width': $(this).outerWidth() });
}).on('affix-bottom.bs.affix', function() {
    $(this).css('bottom', 'auto');
    $(this).css('position', 'fixed');
});

// end preload
$(document).ready(function() {
    $('.fa').each(function() {
        $(this).addClass('fa-fw');
    });

    if ($('.easyzoom').length > 0) {
        $('.easyzoom').easyZoom({
            errorNotice: 'Изображение не может быть загружено',
            loadingNotice: 'Загрузка изображения'
        });
    }

    $("#preloader").addClass("loaded");
});

$(document).ready(function() {

});

$(window).on("scroll", function() {
    var display = (document.body.scrollTop > 60 || document.documentElement.scrollTop > 60) ? "block" : "none";
    $(".btn-scrolltop").css("display", display);
});

function ajaxAction(url, pjax_id = false) {
    $.ajax({
        method: "post",
        url: url
    }).done(function(res) {
        if (res) {
            if (pjax_id) {
                pjaxReload(pjax_id);
            } else {
                window.location.reload();
            }
        } else {
            alertError("Действие не было выполнено");
        }
    }).fail(function() {
        alertError("Возникла внутренняя ошибка сервера. Пожалуйста, обратитесь к разработчикам");
    });
}

function alertError(message) {
    $.alert({
        content: message,
        theme: "modern",
        title: "Ошибка!",
        type: "red"
    });
}

function iCheckInit() {
    $("input").iCheck({
        checkboxClass: "icheckbox_square-grey",
        radioClass: "iradio_square-grey"
    });
}

function pjaxReload(id) {
    if (typeof id !== typeof undefined && id !== false) {
        var container_id = (id.indexOf("#") >= 0) ? id : "#" + id;

        $.pjax.reload({ container: container_id });
    }

    return;
}

function setRedirectUrl(url, redirectUrl) {
    var delim;

    delim = (url.length > 1 && url.includes('?')) ? '&' : '?';

    if (redirectUrl == true) {
        url = url + delim + 'redirect=' + window.location.pathname;
    } else {
        url = url + delim + 'redirect=' + redirectUrl;
    }

    return url;
}

function unsupportedBrowser() {
    ua = navigator.userAgent;
    var result = ua.indexOf("MSIE ") > -1 || ua.indexOf("Trident/") > -1 || ua.indexOf('safari') > -1;

    return result;
}

//arrow-analysis
$('.btn-arrow__toggle').click(function() {
    $(this).toggleClass('arw-turn');
});