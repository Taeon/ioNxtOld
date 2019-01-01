<?php

/**
 * These are the basic routing rules for all requests
 *
 * Here you decide what type of router is going to handle the request
 * - path: the path(s) that are handled
 *    :[parameter] for a specific variable path
 *       e.g. /news/:alias would handle /news/first-item, /news/second-item etc
 *       or
 *       e.g. /category/:category_name/news will handle /category/food/news, /category/tv/news etc
 *    ? for any path
 *       e.g. /category/? will handle /category/food, /category/food/news, /category/tv etc
 */
$config->routes = array(
	(object)array(
		'path' => '/api/?', // Anything that starts with /api/
		'class' => 'API'
	),
	// Everything else - the default router
	(object)array(
		'path' => '?', // Everything!
		'class' => 'Core'
	),
);