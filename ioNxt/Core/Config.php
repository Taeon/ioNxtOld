<?php

namespace ioNxtBase\Core;

class Config{

	protected $namespaces;

	public function __construct(){

		$this->namespaces = new \stdClass();
	}

	public function __get( $namespace ){
		// Namespace already loaded?
		if( !property_exists( $this->namespaces, $namespace ) ){
			// Try to load it
			$path = realpath( dirname( __FILE__ ) . '/../Config' ) . '/' . $namespace . '.php' ;
			$config = new \stdClass();
			$loaded = false;
			if( file_exists( $path ) ){
				$loaded = true;
				include( $path );
			}
			$path = realpath( dirname( __FILE__ ) . '/../../ioNxt-custom/Config' ) . '/' . $namespace . '.php' ;
			if( file_exists( $path ) ){
				$loaded = true;
				include( $path );
			}
			if( !$loaded ){
				throw new \Exception( 'Config file ' . $path . ' not found' );
			}
			$this->namespaces->$namespace = $config;
		}
		return $this->namespaces->$namespace;
	}
}