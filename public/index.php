<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
   
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    '/Users/tom/Apache/libs/',
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Airy_');

$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'development');
Zend_Registry::set('config', $config);

Zend_Locale::disableCache(true);

$translateValidators = array(
    Zend_Validate_NotEmpty::IS_EMPTY => 'This field is required',
    Zend_Validate_Regex::NOT_MATCH => 'Invalid value entered',
    Zend_Validate_StringLength::TOO_SHORT => 'Use %min% to %max% characters',
    Zend_Validate_StringLength::TOO_LONG => 'Use %min% to %max% characters',
    Zend_Validate_EmailAddress::INVALID => 'Invalid e-mail address',
    Zend_Validate_Alpha::NOT_ALPHA => 'Alpha characters only',
    Zend_Validate_Alnum::NOT_ALNUM => 'Alphanumeric characters only'
);
$translator = new Zend_Translate('array', $translateValidators);
Zend_Validate_Abstract::setDefaultTranslator($translator);

$application->bootstrap()
            ->run();

function xlog($dump) {
    echo "<pre>";
    print_r($dump);
    die;
}