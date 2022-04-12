'use strict';

const callButton = document.getElementById('callButton');
const hangupButton = document.getElementById('hangupButton');

var isChannelReady = false;
var isInitiator = false;
var isStarted = false;
var peer;
var streamLocal;
var streamRemove;

var options = {
    'iceServers': iceServers
};

var sdpConstraints = {
    offerToReceiveAudio: true,
    offerToReceiveVideo: true
};

callButton.disabled = true;
hangupButton.disabled = true;

callButton.addEventListener('click', doCall);
hangupButton.addEventListener('click', hangup);

var socket = io.connect(hostUrl);

if (room !== '') {
    socket.emit('create or join', room);
    console.log('Attempted to create or join room', room);
}

socket.on('created', function(room) {
    console.log('Created room ' + room);
});

socket.on('joined', function(room) {
    console.log('Joined: ' + room);
});

socket.on('full', function(room) {
    console.log('Room ' + room + ' is full');
});

socket.on('ready', function(room) {
    isChannelReady = true;
    callButton.disabled = false;
    console.log('Channel ready');
});

socket.on('message', function(message) {
    console.log('Client received message:', message);
  
    if (message === 'got user media') {
        maybeStart();
    } else if (message === 'bye' && isStarted) {        
        handleRemoteHangup();
    } else if (message.type === 'offer') {
        if (!isStarted) {
            maybeStart();
        }
        peer.setRemoteDescription(new RTCSessionDescription(message));
        doAnswer();
    } else if (message.type === 'answer' && isStarted) {
        peer.setRemoteDescription(new RTCSessionDescription(message));
    } else if (message.type === 'candidate' && isStarted) {
        var candidate = new RTCIceCandidate({
            sdpMLineIndex: message.label,
            candidate: message.candidate
        });
        peer.addIceCandidate(candidate);
    }
});

function sendMessage(message) {
    console.log('Client sending message: ', message);
    socket.emit('message', message);
}

////////////////////////////////////////////////////

var localVideo = document.querySelector('#localVideo');
var remoteVideo = document.querySelector('#remoteVideo');

navigator.mediaDevices.getUserMedia({
    audio: true,
    video: true
})
.then(gotStream)
.catch(function(e) {
    alert('getUserMedia() error: ' + e.name);
});

function gotStream(stream) {
    console.log('Adding local stream.');
    streamLocal = stream;
    localVideo.srcObject = stream;
    sendMessage('got user media');
}

var constraints = {
    video: true
};

console.log('Getting user media with constraints', constraints);

function maybeStart() {
    if (!isStarted && typeof streamLocal !== 'undefined' && isChannelReady) {
        console.log('>>>>>> creating peer connection');
        createPeerConnection();
        peer.addStream(streamLocal);
        isStarted = true;
        sendMessage('got user media');
    }
}

window.onbeforeunload = function() {
    sendMessage('bye');
};

function createPeerConnection() {
    try {
        peer = new RTCPeerConnection(options);
        peer.onicecandidate = handleIceCandidate;
        peer.onaddstream = handleRemoteStreamAdded;
        peer.onremovestream = handleRemoteStreamRemoved;
        console.log('Created RTCPeerConnnection');
    } catch (e) {
        console.log('Failed to create PeerConnection, exception: ' + e.message);
        alert('Cannot create RTCPeerConnection object.');
        return;
    }
}

function handleIceCandidate(event) {
    console.log('icecandidate event: ', event);
    if (event.candidate) {
        sendMessage({
          type: 'candidate',
          label: event.candidate.sdpMLineIndex,
          id: event.candidate.sdpMid,
          candidate: event.candidate.candidate
        });
    } else {
        console.log('End of candidates.');
    }
}

function handleCreateOfferError(event) {
    console.log('createOffer() error: ', event);
}

function doCall() {
    console.log('Sending offer to peer');    
    peer.createOffer(setLocalAndSendMessage, handleCreateOfferError);    
}

function doAnswer() {
    console.log('Sending answer to peer.');
    peer.createAnswer().then(
        setLocalAndSendMessage,
        onCreateSessionDescriptionError
    );
}

function setLocalAndSendMessage(sessionDescription) {
    peer.setLocalDescription(sessionDescription);
    callButton.disabled = true;
    hangupButton.disabled = false;
    console.log('setLocalAndSendMessage sending message', sessionDescription);
    sendMessage(sessionDescription);
}

function onCreateSessionDescriptionError(error) {
    trace('Failed to create session description: ' + error.toString());
}

function handleRemoteStreamAdded(event) {
    console.log('Remote stream added.');
    streamRemove = event.stream;
    remoteVideo.srcObject = streamRemove;
}

function handleRemoteStreamRemoved(event) {
    console.log('Remote stream removed. Event: ', event);
}

function hangup() {
    location.reload();
}

function handleRemoteHangup() {
    callButton.disabled = true;
    hangupButton.disabled = true;
    stop();
}

function stop() {
    isStarted = false;
    peer.close();
    peer = null;
}