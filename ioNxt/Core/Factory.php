<?php

namespace ioNxtBase\Core;

class Factory{

	// Allows you to override specific parameters when instantiating a class
	// So for example
	// ioNxt\Foo\Bar => array(
	//		'myparam' => 'myvalue'
	// )
	protected $class_parameters = array();
	// Allows you to return specific values for a particular class
	// So for example
	// ioNxt\Foo\Bar => function(){return new ioNxt\Something}
	protected $class_overrides = array();

	protected $cache;

	public function __construct( \ioNxt\Core\Config $config ){

		$this->cache = (object)array(
			'ioNxt\Core\Factory' => $this,
			'ioNxt\Core\Config' => $config
		);
    }

	/**
	 * Return an instance of a class. Will cache the result
	 * ...and return the same instance for later requests
	 *
	 * @param		string		The full name of the class
	 *
	 * @return		mixed
	 */
	public function Get( $class ){

		// Make sure it doesn't start with a backslash
        if(
            strpos( $class, '\\' ) === 0
        ){
            $class = substr( $class, 1 );
        }

		if( !property_exists( $this->cache, $class ) ){
			$this->cache->$class = $this->Generate( $class );
		}

		return $this->cache->$class;
	}

	/**
	 * Return a new instance of a class and pass constructor parameters automatically
	 *
	 * @param		string		The full name of the class
	 *
	 * @return		mixed
	 */
	public function Generate( $class ){
        // Make sure it doesn't start with a backslash
        if(
            strpos( $class, '\\' ) === 0
        ){
            $class = substr( $class, 1 );
        }

		if( array_key_exists( $class, $this->class_overrides ) ){
			return $this->class_overrides[ $class ];
		}

		// Create reflection instance
        $reflection = new \ReflectionClass( $class );

        // Look for definitions for custom class
        $named_params = array();

        if( array_key_exists( $class, $this->class_parameters ) ){
            $named_params = $this->class_parameters[ $class ];
        }

        // Is there a constructor?
        if( $constructor = $reflection->getConstructor() ){
            // Enumerate the parameters
            $params = array();
            foreach ( $constructor->getParameters() as $param ) {
                // Is there a definition for this parameter?
                if( array_key_exists( $param->name, $named_params ) ){
                    // Yes
                    if( is_callable( $named_params[ $param->name ] ) ){
                        // Function
                        $params[] = $named_params[ $param->name ]();
                    } else {
                        $params[] = $named_params[ $param->name ];
                    }
                } else {
                    // Is it a class (i.e. is it type-hinted)?
                    if( $param_class = $param->getClass() ){
                        // Yes it's a class
                        if( $param->isDefaultValueAvailable() ){
                            // Use the default value
                            // Default value for a type-hinted object can only ever be null
                            $params[] = null;
                        } else {
                            // No default value, so...
                            // ...try using internal method to instantiate
							$params[] = $this->Get( $param_class->name );
                        }
                    } else {
                        // No, check for default value
                        if( $param->isDefaultValueAvailable() ){
                            $params[] = $param->getDefaultValue();
                        } else {
                            throw new \Exception( 'Failed to set non-type-hinted parameter: $' . $param->name . ' in constructor of ' . $class );
                        }
                    }
                }
            }
            return $reflection->newInstanceArgs($params);
        } else {
            // No constructor, just instantiate it
            return new $class();
        }
    }
}