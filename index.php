<?php
#error_reporting(E_ALL & ~E_WARNING);
#require_once "html5/Parser.php"; /*https://github.com/html5lib/html5lib-php*/
echoHtmlStart();
$cs = new CourseScraper("/kurser/");
echoHtmlEnd();

class CourseScraper {
    private $courseCount = 0;
    private $coursesArray = [];
    private $timeStamp;

    public function __construct($url) {
        $this->getCourses($url);
        $scrapeArray = array("courses"=>$this->coursesArray,
                             "count"=>$this->courseCount,
                             "timestamp"=>"test");
        $jsonData = json_encode($scrapeArray);
        var_dump($jsonData);
    }

    public function getCourses($url) {
        $url = "http://coursepress.lnu.se" . $url; //links are relative
        $xpath = $this->xpathGet($url);
        $courseList = $xpath->query("//ul[@id = 'blogs-list']//div[@class = 'item-title']/a[contains(@href, 'kurs')]");
        foreach ($courseList as $course) {
            $this->courseCount++;
            $this->doSomethingWithCourses($course);
        }
        $nextPage = $xpath->query("//div[@id = 'blog-dir-pag-bottom']//a[contains(@class, 'next')]"); //find next page button
        if ($nextPage->length > 0) { //check for last page
            $this->getCourses($nextPage->item(0)->getAttribute("href"));
        }
    }

    public function doSomethingWithCourses($course) {
        $noInfoText = "no information";
        $courseName = $course->nodeValue;
        $courseLink = $course->getAttribute("href");
        $xpath = $this->xpathGet($courseLink);
        $courseCodeList = $xpath->query("//div[@id = 'header-wrapper']//li");
        $courseCode = $noInfoText;
        if ($courseCodeList->length > 0) {
            $courseCode = $courseCodeList->item(2)->nodeValue;
        }
        $courseSyllabusList = $xpath->query("//ul[@class = 'sub-menu']//a[contains(@href, 'coursesyllabus')]");
        $courseSyllabus = $noInfoText;
        if ($courseSyllabusList->length > 0) {
            $courseSyllabus = $courseSyllabusList->item(0)->getAttribute("href");
        }
        $courseIntroList = $xpath->query("//article[contains(@class, 'start-page')]");
        $courseIntro = $noInfoText;
        if ($courseIntroList->length > 0) {
            $courseIntro = $courseIntroList->item(0)->nodeValue;
        }
        $latestEntryList = $xpath->query("//*[@id = 'content']//article[contains(@class, 'type-post')]//header[@class = 'entry-header']");
        $latestEntry = $noInfoText;
        if ($latestEntryList->length > 0) {
            $latestEntry = $latestEntryList->item(0)->nodeValue;
        }

        #echo "<p>" . $courseCode . " <a href=\"" . $courseLink . "\">" . $courseName . "</a> <a href=\"" . $courseSyllabus . "\">Syllabus</a></p>
    #<p>" . $courseIntro . "</p>" . $latestEntry;

        $courseArray = array("course name"=>$courseName,
            "course url"=>$courseLink,
            "course code"=>$courseCode,
            "syllabus url"=>$courseSyllabus,
            "course intro"=>$courseIntro,
            "latest entry"=>$latestEntry);
        $this->coursesArray[] = $courseArray;
    }

    public function curlGet($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT,"eprcz09");
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function xpathGet($url) {
        $data = $this->curlGet($url);
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

/*function html5($url) {
    $data = curlGet($url);
    $dom = HTML5_Parser::parse($data);
    $result = $dom->loadHTML($data);
    if ($result) {
        return (new DOMXPath($dom));
    }
    else {
        die("Fel vid inläsning av HTML");
    }
}*/