<?php
#error_reporting(E_ALL & ~E_WARNING);
#require_once "html5/Parser.php"; /*https://github.com/html5lib/html5lib-php*/
echoHtmlStart();
getCourses("/kurser/");
echoHtmlEnd();
$courseCount = 0;

function getCourses($url) {
    $url = "https://coursepress.lnu.se" . $url; //links are relative
    $xpath = xpathGet($url);
    $courseList = $xpath->query("//ul[@id = 'blogs-list']//div[@class = 'item-title']/a[contains(@href, 'kurs')]");
    foreach ($courseList as $course) {
        doSomethingWithCourses($course);
    }
    /*$nextPage = $xpath->query("//div[@id = 'blog-dir-pag-bottom']//a[contains(@class, 'next')]"); //find next page button
    if ($nextPage->length > 0) { //check for last page
        getCourses($nextPage->item(0)->getAttribute("href"));
    }*/
}

function doSomethingWithCourses($course) {
    $noInfoText = "no information";
    $courseName = $course->nodeValue;
    $courseLink = $course->getAttribute("href");
    $xpath = xpathGet($courseLink);
    $courseCodeList = $xpath->query("//div[@id = 'header-wrapper']//li");
    if ($courseCodeList->length > 0) {
        $courseCode = $courseCodeList->item(2)->nodeValue;
    }
    else {
        $courseCode = $noInfoText;
    }
    $courseSyllabusList = $xpath->query("//ul[@class = 'sub-menu']//a[contains(@href, 'coursesyllabus')]");
    if ($courseSyllabusList->length > 0) {
        $courseSyllabus = $courseSyllabusList->item(0)->getAttribute("href");
    }
    else {
        $courseSyllabus = $noInfoText;
    }
    $courseIntroList = $xpath->query("//article[contains(@class, 'start-page')]");
    if ($courseIntroList->length > 0) {
        $courseIntro = $courseIntroList->item(0)->nodeValue;
    }
    else {
        $courseIntro = $noInfoText;
    }
    $latestEntryList = $xpath->query("//*[@id = 'content']//article[contains(@class, 'type-post')]//header[@class = 'entry-header']");
    if ($latestEntryList->length > 0) {
        $latestEntry = $latestEntryList->item(0)->nodeValue;
    }
    else {
        $latestEntry = $noInfoText;
    }
    /*$latestEntryByLineList = $xpath->query("//*[@id = 'content']//article[contains(@class, 'type-post')]//p[@class = 'entry-byline']");
    if ($latestEntryByLineList->length > 0) {
        $latestEntryByLine = $latestEntryByLineList->item(0)->nodeValue;
    }
    else {
        $latestEntryByLine = "no information";
    }*/

    echo "<p>" . $courseCode . " <a href=\"" . $courseLink . "\">" . $courseName . "</a> <a href=\"" . $courseSyllabus . "\">Syllabus</a></p>
    <p>" . $courseIntro . "</p>" . $latestEntry;

    $courseArray = array("course name"=>$courseName,
                         "course url"=>$courseLink,
                         "course code"=>$courseCode,
                         "syllabus url"=>$courseSyllabus,
                         "course intro"=>$courseIntro,
                         "latest entry"=>$latestEntry);
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
    libxml_use_internal_errors(true);
    $result = $dom->loadHTML($data);
    libxml_clear_errors();
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

function echoHtmlStart() {
    echo "<!doctype html>
<html>
<head>
    <meta charset=\"utf-8\">
</head>
<body>
    ";
}

function echoHtmlEnd() {
    echo "</body>
</html>";
}