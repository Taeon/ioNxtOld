<?php

$start = microtime( true );

// Show all errors
error_reporting(-1);
ini_set('display_errors', 1);
ini_set('default_charset', 'UTF-8');

// Avoid strict errors when timezone not set
date_default_timezone_set( 'Europe/London' );

/**
 * Autoload ioNxt classes
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(
    function ($class) {
        // project-specific namespace prefix
        $prefix = 'ioNxt';

        // We're only interested in ioNxt classes
        if (
            strpos( $class, $prefix . '\\' ) === false
        ) {
            return;
        }

        // base directory for the namespace prefix
        $base_dir = __DIR__ . '';
        $base_dir_custom = realpath( __DIR__ . '/../ioNxt-custom' );

        // get the relative class name
        $class_file = str_replace('\\', '/', substr( $class, strpos( $class, '\\' ) ) ) . '.php';

        $loaded = false;
        // Load base class
        if ( file_exists( $base_dir . $class_file ) ) {
            require_once( $base_dir . $class_file );
            if( class_exists( $class ) ){
                throw new Exception( 'Class' . $class . ' already exists. Did you use ioNxt\ namespace by mistake (instead of ioNxtBase\...)?' );
            }
            $alias_class = 'ioNxtBase\\' . substr( $class, 6 );
            $loaded = true;
        }
        // Load custom class?
        if ( file_exists( $base_dir_custom . $class_file ) ) {
            require_once( $base_dir_custom . $class_file );
            if( class_exists( $class ) ){
                throw new Exception( 'Class' . $class . ' already exists. Did you use ioNxt\ namespace by mistake (instead of ioNxtCustom\...)?' );
            }
            $alias_class = 'ioNxtCustom\\' . substr( $class, 6 );
            $loaded = true;
        }

        class_alias( $alias_class, $class );

        if( !$loaded ){
            echo($base_dir . $class_file);
            throw new \Exception( 'Unable to load class ' . $class );
        }
    }
);

//require_once( __DIR__ . '/vendor/autoload.php' );

require_once( __DIR__ . '/ioNxt.php' );

$ioNxt = new \ioNxt\ioNxt();
$request = new \ioNxt\Core\Request(
    $_SERVER[ 'REQUEST_URI' ]
);
// The routing class decides which publisher will handle the request,
// then returns the result of its Publish() method
$resource = $ioNxt->Get( '\ioNxt\Core\Routing' )->Route( $request );
$output = $resource->Render();

// Should we cache this resource?
if( $resource->IsCacheable() ){
    $ioNxt->Get( '\ioNxt\Core\Cache\Cache' )->Store(
        new \ioNxt\Core\Cache\Container\HTML(
            $request->requested_path,
            $output
        )
    );
}
http_response_code( $resource->http_status );
echo( $output );
echo( microtime(true) - $start );