var bang = new Audio("/notify/good.mp3");
var lastActivity = 0;
var roomId = $("#consultMain").data("consult_id");
var socket = io(hostUrl);
var typingInterval = 2000;
var typingTimer = 3000;


// var app = require('express')();
// var http = require('http').Server(app);
// var io = require('socket.io')(http);


socket.emit("join", roomId);
goFoot();

$(document).mouseover(function () {
    if (lastActivity < dateNow() - 60) {
        socket.emit("messageCheck", roomId);
        lastActivity = dateNow();
    }
});

$(document).on("click", ".consult-hide", function(e) {
    var url = $(this).attr("href");

    $.confirm({
        title: "Внимание!",
        content: "Скрыть консультацию?",
        theme: "modern",
        buttons: {
            confirm: {
                action: function () {
                    $.ajax({
                        data: {consult_id: roomId},
                        method: "post",
                        url: url
                    });
                },
                btnClass: "btn-primary",
                text: "Да"
            },
            cancel: {
                text: "Нет"
            }
        }
    });

    e.preventDefault();
});

$(document).on("click", ".consult-end", function(e) {
    var url = $(this).attr("href");

    $.confirm({
        title: "Внимание!",
        content: "Консультация будет завершена и Вы больше не сможете обмениваться сообщениями. Продолжить?",
        theme: "modern",
        buttons: {
            confirm: {
                action: function () {
                    $.ajax({
                        data: {consult_id: roomId},
                        method: "post",
                        success: function () {
                            socket.emit("consultEnd", roomId);
                            showConsultEndAlert();
                        },
                        url: url
                    });
                },
                btnClass: "btn-primary",
                text: "Да"
            },
            cancel: {
                text: "Нет"
            }
        }
    });

    e.preventDefault();
});

$(document).on("click", ".delete-message", function(e) {
    var msg_id = $(this).data("message_id");
    var url = $(this).attr("href");

    $.confirm({
        title: "Внимание!",
        content: "Удалить сообщение?",
        theme: "modern",
        buttons: {
            confirm: {
                action: function () {
                    $.ajax({
                        data: {msg_id: msg_id},
                        method: "post",
                        success: function ({msg_id}) {
                            socket.emit("messageDelete", roomId, msg_id);
                            $("#msg-" + msg_id).remove();
                        },
                        error: function (response) {
                            throwError(response.responseJSON.message);
                        },
                        url: url
                    });
                },
                btnClass: "btn-primary",
                text: "Да"
            },
            cancel: {
                text: "Нет"
            }
        }
    });

    e.preventDefault();
});

$(document).on("click", ".send-file", function() {
    $(".send-file-input").click();
});

$(document).on("click", ".send-message", function() {
    saveMessage($(".message-input").val(), 10);
});

$(document).on("click", ".update-message", function(e) {
    var msg_id = $(this).data("message_id");
    var message = $("#msg-" + msg_id).find(".msg-content").text();

    var updateInput = '<span class="msg-content"><div class="input-group input-group-sm">' +
        '<input class="form-control update-input" type="text" value="' + message +'">' +
        '<span class="input-group-btn">' +
        '<button class="btn btn-default btn-flat update-btn" data-message_id="' + msg_id + '"title="Сохранить" type="button"><i class="fa fa-floppy-o"></i></button>' +
        '<button class="btn btn-default btn-flat cancel-update-btn" data-message_id="' + msg_id + '"title="Отменить" type="button"><i class="fa fa-remove"></button>' +
        '</span></div></span>';

    $("#msg-" + msg_id).find(".msg-content").replaceWith(updateInput);
    e.preventDefault();
});

$(document).on("click", ".update-btn", function(e) {
    var msg_id = $(this).data("message_id");
    var message = $("#msg-" + msg_id).find(".update-input").val();

    $.ajax({
        data: {consult_id: roomId, message: message, msg_id: msg_id},
        method: "post",
        success: function ({msg_id, message}) {
            socket.emit("messageUpdate", roomId, msg_id, message);
            $("#msg-" + msg_id).find(".msg-content").replaceWith('<span class="msg-content">' + message + '</span>');
        },
        url: $("form").data("action-message-update")
    });

    e.preventDefault();
});

$(document).on("click", ".cancel-update-btn", function(e) {
    var msg_id = $(this).data("message_id");
    var message = $("#msg-" + msg_id).find(".update-input").val();

    $("#msg-" + msg_id).find(".msg-content").replaceWith('<span class="msg-content">' + message + '</span>');
    e.preventDefault();
});

$(document).on("change", ".send-file-input", function() {
    $.ajax({
        contentType: false,
        data: new FormData($("form")[0]),
        method: "post",
        processData: false,
        success: function ({file}) {
            $(this).val(null);
            saveMessage(file, 20);
        },
        error: function (response) {
            throwError(response.responseJSON.message);
        },
        url: $("form").data("action-message-file")
    });
});

$(document).on("keydown", ".message-input", function(e) {
    if (e.keyCode == 13) {
        socket.emit("userTyping", false);
        saveMessage($(this).val(), 10);
        e.preventDefault();
    } else {
        clearTimeout(typingTimer);
        socket.emit("userTyping", true);
    }
});

$(document).on("keyup", ".message-input", function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(function () {
        socket.emit("messageCheck", roomId);
        socket.emit("userTyping", false);
    }, typingInterval);
});


// socket events
socket.on("consult-end", function () {
    showConsultEndAlert();
});

socket.on("message-check", function () {
    checkMessage();
});

socket.on("message-delete", function (msg_id) {
    deleteMessage(msg_id);
});

socket.on("message-get", function (msg_id) {
    getMessage(msg_id);
    bang.play();
});

socket.on("message-update", function (msg_id, message) {
    $("#msg-" + msg_id).find(".msg-content").replaceWith('<span class="msg-content">' + message + '</span>');
});

socket.on("user-typing", function (status) {
    $(".typing").css("visibility", (status) ? "visible" : "hidden");
});


// main functions
function dateNow() {
    return Math.round(new Date().getTime() / 1000);
}

function goFoot() {
    var msgDiv = $(".direct-chat-messages");
    msgDiv.animate({ scrollTop: msgDiv.height() + 10000 }, 0);
};

function checkMessage() {
    $.ajax({
        data: {consult_id: roomId},
        method: "post",
        success: function (response) {
            if (response.success) {
                $(".check-message").css("color", "green");
            }
        },
        url: $("form").data("action-message-check")
    });
};

function deleteMessage(msg_id) {
    $("#msg-" + msg_id).remove();
}

function getMessage(msg_id) {
    $.ajax({
        data: {msg_id: msg_id},
        method: "post",
        success: function (response) {
            $(".direct-chat-messages").append(response);
            goFoot();
        },
        url: $("form").data("action-message-render")
    });
}

function saveMessage(message, message_type) {
    if (message) {
        $.ajax({
            data: {consult_id: roomId, message: message, message_type: message_type},
            method: "post",
            success: function ({msg_id, message}) {
                socket.emit("messageCheck", roomId);
                socket.emit("messageGet", msg_id);
                $(".direct-chat-messages").append(message);
                $(".message-input").val("");
                goFoot();
            },
            error: function (response) {
                throwError(response.responseJSON.message);
            },
            url: $("form").data("action-message-save")
        });
    }
}

function showConsultEndAlert() {
    $.alert({
        buttons: {
            ok: {
                action: function () {
                    window.location.reload();
                },
                btnClass: "btn-primary",
                text: "ok"
            }
        },
        content: "Консультация завершена.",
        theme: "modern",
        title: false
    });
}

function throwError(content) {
    $.alert({
        buttons: {
            ok: {
                btnClass: "btn-danger",
                text: "ok"
            }
        },
        content: content,
        theme: "modern",
        title: "Ошибка"
    });
}