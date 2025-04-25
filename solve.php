<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = $_POST['query'];

    $apiKey = "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";  // 🔐 Replace this with your actual OpenAI key

    $postData = [
        "model" => "gpt-3.5-turbo",
        "messages" => [
            ["role" => "system", "content" => "You are a helpful assistant that solves any kind of math problem."],
            ["role" => "user", "content" => $query]
        ],
        "temperature" => 0.3
    ];

    $ch = curl_init("https://api.openai.com/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        echo json_encode(["response" => "cURL Error: " . curl_error($ch)]);
    } elseif ($httpCode !== 200) {
        echo json_encode(["response" => "HTTP Error Code: $httpCode"]);
    } else {
        $responseData = json_decode($result, true);
        if (isset($responseData['choices'][0]['message']['content'])) {
            $answer = trim($responseData['choices'][0]['message']['content']);
            echo json_encode(["response" => $answer]);
        } else {
            echo json_encode(["response" => "API Error: Response structure not recognized."]);
        }
    }

    curl_close($ch);
}
?>
