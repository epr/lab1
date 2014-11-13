<?php

class CourseScraper {
    private $courseCount = 0;
    private $coursesArray = [];
    private $jsonFileName = "coursescraper.json";
    private $cacheSeconds = 300;

    public function __construct($url) {
        if (file_exists($this->jsonFileName)) {
            $jsonFile = file_get_contents($this->jsonFileName);
            $decodedData = json_decode($jsonFile, true); //decode into associative array
            $timestamp = $decodedData["timestamp"]; //get time stamp
            if (time() - $timestamp <= $this->cacheSeconds) { //cache hasn't expired
                return; //don't scrape
            }
        }
        $this->getCourses($url);
        $scrapeArray = array("courses"=>$this->coursesArray,
            "count"=>$this->courseCount,
            "timestamp"=>time()); //seconds since 1970
        $jsonData = json_encode($scrapeArray);
        file_put_contents($this->jsonFileName, $jsonData);
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
        curl_setopt($ch, CURLOPT_USERAGENT,"eprcz09"); //identification
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