<?php

// Enable/disable caching
// Note that disabling this won't affect direct static cache access via Apache...
// ...if the cache has already been created
$config->enabled = true;

// Path to cache
// By default, it's in web root
$config->path = realpath( __DIR__ . '/../../cache' ) . '/';

// Where HTML files are stored for direct static cache access.
// This can be different to the location for storing cached data
// Make sure you edit the rewrite rules if you move it
$config->path_html = $config->path . 'HTML';