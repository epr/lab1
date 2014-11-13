<?php
require_once "CourseScraper.php";
new CourseScraper("/kurser/");
echo "<a href=\"coursescraper.json\">JSON</a>";