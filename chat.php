<?php
$apiKey = "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"; 

$userMessage = $_POST['message'];

$data = [
    "model" => "openai/gpt-3.5-turbo",
    "messages" => [
        ["role" => "system", "content" => "You are a helpful math assistant. Solve math problems with clear answers."],
        ["role" => "user", "content" => $userMessage]
    ]
];

$ch = curl_init("https://openrouter.ai/api/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json",
    "HTTP-Referer: localhost", 
    "X-Title: Math Chatbot"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode !== 200) {
    echo "HTTP Error Code: $httpCode";
    exit;
}

$responseData = json_decode($response, true);
echo $responseData['choices'][0]['message']['content'];
