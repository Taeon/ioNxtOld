<?php

namespace ioNxt;

class ioNxt{

    protected $config;

    public function __construct(){
        $this->config = new \ioNxt\Core\Config();
    }

    public function Get( $class ){
        return (new \ioNxt\Core\Factory( $this->config ))->Get( $class );
    }
}