<?php

curl_get_request("https://coursepress.lnu.se/kurser/");
function curl_get_request($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    #var_dump($data);
}