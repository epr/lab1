<?php

$data = curl_get_request("https://coursepress.lnu.se/kurser/");
$dom = new DOMDocument();
if ($dom->loadHTML($data)){
    $xpath = new DOMXPath($dom);
}
else {
    die("Fel vid inläsning av HTML");
}
$courselist = $xpath->query("//ul[@id = 'blogs-list']//div[@class = 'item-title']/a");
foreach ($courselist as $course) {
    echo "<div>" . $course->nodeValue . " -> " . $course->getAttribute("href") . "</div>";
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
