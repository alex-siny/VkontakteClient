<?php

namespace Vkontakte\Client\OAuth\Response;

use Vkontakte\Client\InitializationInterface;

/**
 * Authorization error
 * @author alxmsl
 * @date 3/30/13
 */
final class Error implements InitializationInterface {
    /**
     * @var string authorization error
     */
    private $error = '';

    /**
     * @var string authorization error description
     */
    private $description = '';

    /**
     * Locker
     */
    private function __construct() {}

    /**
     * Setter for authorization error description
     * @param string $description authorization error description
     * @return Error self
     */
    public function setDescription($description) {
        $this->description = (string) $description;
        return $this;
    }

    /**
     * Authorization error description getter
     * @return string authorization error description
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Setter for authorization error code
     * @param string $error error code
     * @return Error self
     */
    public function setError($error) {
        $this->error = (string) $error;
        return $this;
    }

    /**
     * Authorization error code getter
     * @return string error code
     */
    public function getError() {
        return $this->error;
    }

    /**
     * Method for object initialization by the string
     * @param string $string response string with error data
     * @return Error response object
     */
    public static function initializeByString($string) {
        $data = array();
        parse_str($string, $data);
        $Error = new self();
        $Error->setError($data['error'])
            ->setDescription($data['error_description']);
        return $Error;
    }
}
