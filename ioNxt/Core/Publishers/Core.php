<?php

namespace ioNxtBase\Core\Publishers;

class Core extends \ioNxt\Core\Publisher{
	public function __construct( \ioNxt\Core\Factory $factory ){
		$this->factory = $factory;
	}

	public function Publish( \ioNxt\Core\Request $request ){

		// We need to find the route that matches the requested path
		foreach( $this->GetRoutes() as $route ){
			// Wildcard match (i.e. might include multiple segments), such as a cartegory path
			// e.g. /foo/?category_path/:uid ... /foo/category/subcategory/bar
			// -> category_path => category/subcategory
			// -> uid => bar
			$pattern = preg_replace( '~(\?[^/]*)~','(.+)', $route->path );
			// An optional parameter
			// e.g. /foo/~bar
			$pattern = preg_replace( '~(\/\~[^/]*)~','((/|)[^/]*)', $pattern );
			// A single segment
			// e.g. /foo/:bar
			$pattern = preg_replace( '~(:[^/]*)~','([^/]+)', $pattern );
			$pattern = '~^' . $pattern . '$~Ui';

			// Any parameters that are passed in the request path
			$route_parameters = (object)array();
			if ( preg_match( $pattern, $request->GetRequestedPath(), $parameters ) ){
				// Grab anything that's not the base path and break it up into 'parameters'
				preg_match_all( '~(\:|\?|\~)([^/]*)~i', $route->path, $keys );
				foreach( $keys[ 2 ] as $index => $key ){
					$value = $parameters[ $index + 1 ];
					// Optional parameters will have a / at the start
					if( substr( $value, 0, 1 ) == '/' ){
						$value = substr( $value, 1 );
					}
					// Optional parameters might not be present...
					if( $parameters[ $index + 1 ] ){
						$route_parameters->$key = $value;
					} else {
						// ...so pass null
						$route_parameters->$key = null;
					}
				}

				$request->SetRouteParameters( $route_parameters );

				return $this->GetResource( $request, $route->uid );
			}
		}

// TODO: Handle errors in a more generic manner (i.e. higher up the chain somewhere)
$definition = (object)array(
	'blocks' => array()
);
$resource = new \ioNxt\Core\Resource\HTML( $this->factory, $definition, $request );
$resource->http_status = \ioNxt\Core\Resource::HTTP_STATUS_NOT_FOUND;
$resource->content = 'Page not found';
return $resource;
	}

	protected function GetResource( \ioNxt\Core\Request $request, $resource_uid ){
		$definition = $this->GetResourceDefinition( $resource_uid );
		$resource_class = '\ioNxt\Core\Resource\\' . $definition->resource_type;
		return new $resource_class( $this->factory, $definition, $request );
	}
	protected function GetResourceDefinition( $uid ){
		return (object)array(
			'uid' => $uid,
			'resource_type' => 'HTML',
			'blocks' => array(
				(object)array(
					'area' => 'main',
					'module' => 'Foo',
					'block' => 'Bar'
				)
			)
		);
	}
	protected function GetRoutes(){

		//

		return $routes = array(
			(object)array(
				'path' => '/',
				'uid' => '1'
			),
			(object)array(
				'path' => '/foo',
				'uid' => '2'
			),
			(object)array(
				'path' => '/foo/:uid',
				'uid' => '3',
			),
			(object)array(
				'path' => '/bar/?uid/:bar',
				'uid' => '4'
			),
		);
	}
}