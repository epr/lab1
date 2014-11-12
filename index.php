<?php
require_once "html5/Parser.php";
getCourses("/kurser/");

function getCourses($url) {
    $url = "https://coursepress.lnu.se" . $url; //links are relative
    $xpath = xpathGet($url);
    $courselist = $xpath->query("//ul[@id = 'blogs-list']//div[@class = 'item-title']/a[contains(@href, 'kurs')]");
    foreach ($courselist as $course) {
        doSomethingWithCourses($course);
    }
    $nextpage = $xpath->query("//div[@id = 'blog-dir-pag-bottom']//a[contains(@class, 'next')]"); //find next page button
    if ($nextpage->length > 0) { //check for last page
        getCourses($nextpage->item(0)->getAttribute("href"));
    }
}

function doSomethingWithCourses($course) {
    $courseName = $course->nodeValue;
    $courseLink = $course->getAttribute("href");
    $xpath = html5($courseLink);

    echo "<div>" . $courseName . " -> " . $courseLink . "</div>";
}

function curlGet($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT,"eprcz09");
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function xpathGet($url) {
    $data = curlGet($url);
    $dom = new DOMDocument();
    #libxml_use_internal_errors(true);
    $result = $dom->loadHTML($data);
    #libxml_clear_errors();
    if ($result) {
        return (new DOMXPath($dom));
    }
    else {
        die("Fel vid inläsning av HTML");
    }
}

function html5($url) {
    $data = curlGet($url);
    $dom = HTML5_Parser::parse($data);
    $result = $dom->loadHTML($data);
    if ($result) {
        return (new DOMXPath($dom));
    }
    else {
        die("Fel vid inläsning av HTML");
    }
}