<?php

namespace ioNxtBase\Core;

/**
 * Handles basic routing
 *
 */
class Routing{

	protected $config;
	protected $factory;

	public function __construct(
		\ioNxt\Core\Config $config,
		\ioNxt\Core\Factory $factory
	){
		$this->config = $config;
		$this->factory = $factory;
	}

	public function Route( \ioNxt\Core\Request $request ){

		// Interate over defined routes to find the publisher class to handle this request
		foreach( $this->config->routing->routes as $route ){
			// Does this route match the requested path?
			if( $this->MatchRoute( $request->GetRequestedPath(), $route ) ){
				// Load the relevant publisher class and call its Publish() method
				return $this->factory->Get( 'ioNxt\Core\Publishers\\' . $route->class )->Publish( $request );
			}
		}
// TODO: Show a suitable error response
		throw new \Exception( 'Route not found' );
	}

	/**
	 * Attempt to match the requested path with a router (as defined in /Config/routing.php)
	 * @param		string		$path
	 * @param 		object 		$route		A route definition object
	 *
	 * @return
	 */
	protected function MatchRoute( $path, $route ){

		// Wildcard match (i.e. might include multiple segments)
		$pattern = preg_replace( '~(\?[^/]*)~','(.+)', $route->path );

		// A single segment
		$pattern = '~^' . preg_replace( '~(:[^/]*)~','([^/]+)', $pattern ) . '$~Ui';

		// Does it match?
		return preg_match( $pattern, $path, $parameters );
	}
}