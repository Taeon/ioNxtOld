<?php

namespace ioNxtBase\Core;

abstract class Resource{

	protected $definition;
	protected $request;
	protected $factory;

	public $cacheable = true;

	public $mime_type = 'text/plain';
	public $http_status = self::HTTP_STATUS_OK;

	public function __construct( $factory, $definition, \ioNxt\Core\Request $request ){
		$this->factory = $factory;
		$this->definition = $definition;
		$this->request = $request;
	}

	abstract public function Render();

	const HTTP_STATUS_OK = 200;
	const HTTP_STATUS_NOT_FOUND = 404;

	public function IsCacheable(){
		return $this->cacheable;
	}
}