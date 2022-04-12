var $play = document.querySelector('#btn-play'),
    $pause = document.querySelector('#btn-pause'),
    $stop = document.querySelector('#btn-stop'),
    $textContent = document.querySelector(selector).textContent,
    paused = false,
    to_speak;
    
if ('speechSynthesis' in window) {
    to_speak = new SpeechSynthesisUtterance($textContent);
    to_speak.rate = 1.1;
    to_speak.lang = 'ru-RU';
    to_speak.pitch = 0.2;

    to_speak.onpause = function () {
        paused = true;
    };

    function play() {
        if (paused) {
            paused = false;
            window.speechSynthesis.resume();
        } else {
            window.speechSynthesis.speak(to_speak);
        }
    }

    function pause() {
        window.speechSynthesis.pause();
    }

    function cancel() {
        speechSynthesis.cancel();
    }
    
    $play.addEventListener('click', play, false);
    $pause.addEventListener('click', pause, false);
    $stop.addEventListener('click', cancel, false);
} else {
    alert('Ваш браузер не поддерживает эту функцию, попробуйте другой браузер');
}

window.onbeforeunload = function() {
    $stop.click();
};