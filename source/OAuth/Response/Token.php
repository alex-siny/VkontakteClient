<?php

namespace Vkontakte\Client\OAuth\Response;

use Vkontakte\Client\InitializationInterface;

/**
 * Authorization token
 * @author alxmsl
 * @date 3/30/13
 */
final class Token implements InitializationInterface {
    /**
     * @var string access token
     */
    private $accessToken = '';

    /**
     * @var int access token expires in
     */
    private $expiresIn = 0;

    /**
     * @var string authorized user id
     */
    private $userId = '';

    /**
     * @var string secret code for no https requests
     */
    private $secret = '';

    /**
     * Setter for access token
     * @param string $accessToken access token
     * @return Token self
     */
    public function setAccessToken($accessToken) {
        $this->accessToken = (string) $accessToken;
        return $this;
    }

    /**
     * Getter for access token
     * @return string access token
     */
    public function getAccessToken() {
        return $this->accessToken;
    }

    /**
     * Setter for access token expires in
     * @param int $expiresIn access token expires in
     * @return Token self
     */
    public function setExpiresIn($expiresIn) {
        $this->expiresIn = (int) $expiresIn;
        return $this;
    }

    /**
     * Getter for access token expires in
     * @return int access token expires in
     */
    public function getExpiresIn() {
        return $this->expiresIn;
    }

    /**
     * Setter for id token
     * @param string $idToken id token
     * @return Token self
     */
    public function setUserId($idToken) {
        $this->userId = (string) $idToken;
        return $this;
    }

    /**
     * Getter for id token
     * @return string id token
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * No https secret code setter
     * @param string $secret secret code string
     * @return Token self
     */
    public function setSecret($secret) {
        $this->secret = (string) $secret;
        return $this;
    }

    /**
     * Getter for no https secret code
     * @return string no https secret code
     */
    public function getSecret() {
        return $this->secret;
    }

    /**
     * Method for object initialization by the string
     * @param string $string redirect string with access token data
     * @return Token response object
     */
    public static function initializeByString($string) {
        $data = array();
        parse_str($string, $data);
        $Response = new self();
        $Response->setAccessToken($data['access_token'])
            ->setExpiresIn($data['expires_in']);
        if (isset($data['user_id'])) {
            $Response->setUserId($data['user_id']);
        }
        if (isset($data['secret'])) {
            $Response->setSecret($data['secret']);
        }
        return $Response;
    }
}
