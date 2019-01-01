<?php

namespace ioNxtBase\Core\Resource;

class HTML extends \ioNxt\Core\Resource{

	public $mime_type = 'text/html';
	protected $uid;

	public function SetContent( $content ){
		$this->content = $content;
	}

	public function Render(){
		// Load definition
		$content = '';

		// Get template
		// Find areas in template
		$areas = array(
			'main'
		);
		// Process blocks
		foreach( $this->definition->blocks as $block_definition ){
// Check if area exists in template
			if( in_array( $block_definition->area, $areas ) ){
// This should be loaded by blocks class?
				$block_class = '\ioNxt\Module\\' . $block_definition->module . '\Block\\' . $block_definition->block . '\Block';
				$block = new $block_class( $this->request, $this );
				$content.= $block->Render();
			}
		}

 		$this->SetContent( $content );
$this->cacheable = true;

		return '<!DOCTYPE html><html lang="en-GB">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta charset="utf-8">
			</head>
			<body>
				' . $this->content . '
			</body>
		</html>';
	}
}