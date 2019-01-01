<?php

namespace ioNxtBase\Module\Foo\Block\Bar;

class Block {

    public function __construct( \ioNxt\Core\Request $request, \ioNxt\Core\Resource $resource ){

    }

    public function BeforeRender(){}

    public function Render(){
        return '';
    }

    public function AfterRender(){}
}
