<?php

return array(
    // Site to crawl and create a sitemap for.
    // <Syntax> https://www.your-domain-name.com/ or http://www.your-domain-name.com/
    "SITE_URL" => "https://student-laptop.nl/",

    // Boolean for crawling external links.
    // <Example> *Domain = https://www.student-laptop.nl* , *Link = https://www.google.com* <When false google will not be crawled>
    "ALLOW_EXTERNAL_LINKS" => false,

    // Boolean for crawling element id links.
    // <Example> <a href="#section"></a> will not be crawled when this option is set to false
    "ALLOW_ELEMENT_LINKS" => false,

    // If set the crawler will only index the anchor tags with the given id.
    // If you wish to crawl all links set the value to ""
    // <Example> <a id="internal-link" href="/info"></a> When CRAWL_ANCHORS_WITH_ID is set to "internal-link" this link will be crawled
    // but <a id="external-link" href="https://www.google.com"></a> will not be crawled.
    "CRAWL_ANCHORS_WITH_ID" => "",

    // Array with absolute links or keywords for the pages to skip when crawling the given SITE_URL.
    // <Example> https://student-laptop.nl/info/laptops or you can just input student-laptop.nl/info/ and it will not crawl anything in that directory
    // Try to be as specific as you can so you dont skip 300 pages
    "KEYWORDS_TO_SKIP" => array(),

    // Location + filename where the sitemap will be saved.
    "SAVE_LOC" => "sitemap.xml",

    // Static priority value for sitemap
    "PRIORITY" => 1,

    // Static update frequency
    "CHANGE_FREQUENCY" => "daily",

    // Date changed (today's date)
    "LAST_UPDATED" => date('Y-m-d'),
);
