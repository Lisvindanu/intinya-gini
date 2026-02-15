<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Reddit Scraper Configuration
    |--------------------------------------------------------------------------
    */

    'reddit' => [
        'subreddits' => [
            'programming',
            'technology',
            'webdev',
            'javascript',
            'php',
            'learnprogramming',
            'opensource',
        ],
        'limit' => 25,
    ],

    /*
    |--------------------------------------------------------------------------
    | News API Configuration
    |--------------------------------------------------------------------------
    */

    'newsapi' => [
        'categories' => ['technology', 'science', 'business'],
        'page_size' => 20,
    ],

    /*
    |--------------------------------------------------------------------------
    | Scraping Schedule
    |--------------------------------------------------------------------------
    */

    'schedule' => [
        'enabled' => env('SCRAPING_ENABLED', true),
        'interval' => env('SCRAPING_INTERVAL', 3600), // seconds
    ],

];
