<?php

namespace ioNxtBase\Core;

/**
 * Base class for Publishers -- classes that handle a request for a particular route (or collection of routes) and return a resource
 */
abstract class Publisher{
    /**
     * Attempt to handle a request for a particular route
     *
     * @param       \ioNxt\Core\Request     $request
     *
     * @return      \inNxt\Core\Resource
     */
    public abstract function Publish( \ioNxt\Core\Request $request );
}