<?php
if (isset($_GET['question'])) {
    $question           = $_GET['question'];
    $url                = 'http://157.230.182.93:8000/rag_chain/invoke';
    $ch                 = curl_init($url);
    curl_setopt         ($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt         ($ch, CURLOPT_POST, true);
    curl_setopt         ($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $postData           = json_encode(['question' => $question]);
    curl_setopt         ($ch, CURLOPT_POSTFIELDS, $postData);
    $response           = curl_exec($ch);
    $httpCode           = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close          ($ch);
    $data               = json_decode($response, true);
    echo strval($data['response']);
}