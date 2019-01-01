<?php

namespace ioNxtBase\Core\Cache;

abstract class Container{
	protected $expiry_period = false;
	protected $format = self::FORMAT_SERIALIZED; // Safest but slowest

	const FORMAT_RAW = 'raw';
	const FORMAT_JSON = 'json';
	const FORMAT_SERIALIZED = 'serialized';

	public function __construct(
		$id,
		$data,
		$expiry_period = false
	){
		$this->id = $id;
		$this->data = $data;
	}

	public function GetFormattedData(){
		return $this->data;
	}
}