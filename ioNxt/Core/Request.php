<?php

namespace ioNxtBase\Core;

class Request{
	public $protocol;
	public $domain;
	public $requested_path;
	public $internal_path;
	protected $route_parameters;

	public function __construct(
		$requested_path
	){
		$this->requested_path = explode( '?', $requested_path )[0];
		$this->route_parameters = new \stdClass();
	}

	public function GetRequestedPath(){
		return $this->requested_path;
	}

	public function SetRouteParameters( $route_parameters ){
		foreach( $route_parameters as $key => $value ){
			$this->route_parameters->$key = $value;
		}
	}
	public function GetRouteParameters(){
		return $this->route_parameters;
	}
	public function GetRouteParameter( $key ){
		if( property_exists( $this->route_parameters, $key ) ){
			return $this->route_parameters->$key;
		} else {
			return null;
		}
	}
}