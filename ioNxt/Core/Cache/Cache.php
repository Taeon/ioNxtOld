<?php

namespace ioNxtBase\Core\Cache;

class Cache{
	public function __construct(
		\ioNxt\Core\Config $config,
		\ioNxt\Core\Factory $factory
	){
		$this->config = $config;
		$this->factory = $factory;
	}
	public function Store( \ioNxt\Core\Cache\Container $container ){
		// Is cache enabled?
		if( !$this->config->cache->enabled ){
			return;
		}

		$target_path = $this->config->cache->path_html . $container->id . (($container->id)?'/':'');
		// Check for path
		if( !file_exists( $target_path ) ){
			// Build path
			mkdir( $target_path, 0777, true );
		}
		file_put_contents( $target_path .'cache.html', $container->GetFormattedData() );
	}
}