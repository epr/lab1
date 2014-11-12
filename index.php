<?php

$req = curl_get_request("http://coursepress.lnu.se/kurser/");
$dom = new DOMDocument();
$dom->loadHTML($req);
$xpath = new DOMXPath($dom);
$courselist = $xpath->query("//ul[@id = 'blogs-list']");
foreach ($courselist as $course) {
    echo($course->nodeValue);
}
function curl_get_request($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT,"eprcz09");
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
