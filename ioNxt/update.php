<?php

class Requirement{
    public $title;
    public $description;
    public $status = self::STATUS_ABSENT;
    public $error;

    const STATUS_PRESENT = 1;
    const STATUS_ABSENT = 0;
    public function __construct( $title, $description ){
        $this->title = $title;
        $this->description = $description;
    }
    public function SetError( $error ){
        $this->error = $error;
    }
}

// Settings
$minimum_php_version = '5.4';
$minimum_apache_version = '2.2';

// Store results here
$required = new ArrayObject();

// PHP version
$requirement = new Requirement( 'PHP v' . $minimum_php_version, 'This is the minimum version of PHP required for ioNxt to run' );
if( version_compare( PHP_VERSION, $minimum_php_version, '>=' ) ){
    $requirement->status = Requirement::STATUS_PRESENT;
} else {
    $requirement->error = 'Minimum version of PHP is ' . (string)$minimum_php_version . ', current version is ' . PHP_VERSION;
}
$required[] = $requirement;

// Apache version
$requirement = new Requirement( 'Apache v' . $minimum_apache_version, 'This is the minimum version of Apache required for ioNxt to run' );
preg_match( '~Apache/([0-9\.]*)~', apache_get_version(), $matches );
if( ($matches[1] * 1) >= $minimum_apache_version ){
    $requirement->status = Requirement::STATUS_PRESENT;
} else {
    $requirement->error = 'Minimum version of Apache is ' . (string)$minimum_apache_version . ', current version is ' . $matches[1];
}
$required[] = $requirement;


// Compile results
new ArrayIterator($required);
$iterator = $required->getIterator();
$requirements_html = '';
for( $iterator->rewind(); $iterator->valid(); $iterator->next() ){
    $requirement = $iterator->current();
    $requirements_html .= '<li class="' . (($requirement->status == Requirement::STATUS_ABSENT)?'error':'') . '">
        <div>' . $requirement->title . '</div>
        <div>' . $requirement->description . '</div>';
    if( $requirement->status == Requirement::STATUS_ABSENT ){
        $requirements_html .= '<div>' . $requirement->error . '</div>';
    }
    $requirements_html .= '</li>';
}
?>

<?=$requirements_html?>