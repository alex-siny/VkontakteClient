<?php

namespace Vkontakte\Client\API;

use Vkontakte\Client\OAuth\Response\Token,
    Vkontakte\Client\OAuth\Client as OAuthClient,
    Network\Http\Request,
    Vkontakte\Client\API\Response\ResponseFactory;

/**
 * VK API Client
 * @author alxmsl
 * @date 3/30/13
 */
final class Client extends OAuthClient {
    /**
     * API access schemes
     */
    const   SCHEME_HTTP     = 'http',
            SCHEME_HTTPS    = 'https';

    /**
     * API endpoint
     */
    const   ENDPOINT_API        = 'api.vk.com/method/';

    /**
     * @var Token|null token authorization instance
     */
    private $Token = null;

    /**
     * Authorization token setter
     * @param \Vkontakte\Client\OAuth\Response\Token $Token authorization token instance
     * @return Client self
     */
    public function setToken(Token $Token) {
        $this->Token = $Token;
        return $this;
    }

    /**
     * Authorization token getter
     * @return Token authorization token instance
     */
    public function getToken() {
        return $this->Token;
    }

    /**
     * Secure call VK API nethod
     * @param string $method method name
     * @param array $get GET method parameters
     * @param array $post POST method parameters
     * @return \Vkontakte\Client\API\Response\Error|\stdClass error or result instance
     */
    public function callSecure($method, array $get = null, array $post = null) {
        $Request = $this->getRequest(self::SCHEME_HTTPS . '://' . self::ENDPOINT_API . $method);
        $Request->addGetField('access_token', $this->getToken()->getAccessToken());
        $this->addRequestParameters($Request, $get, $post);
        return ResponseFactory::createResponse($Request->send());
    }

    /**
     * Non-secure call VK API method
     * @param string $method method name
     * @param array $get GET method parameters
     * @param array $post POST method parameters
     * @return \Vkontakte\Client\API\Response\Error|\stdClass error or result instance
     */
    public function callNotSecure($method, array $get = null, array $post = null) {
        $Request = $this->getRequest(self::SCHEME_HTTP . '://' . self::ENDPOINT_API . $method);
        $this->addRequestParameters($Request, $get, $post);

        $Token = $this->getToken();
        if (!is_null($Token) && $Token->getSecret()) {
            $Request->addGetField('access_token', $this->getToken()->getAccessToken());
            $Request->getSignature(function (Request $Request) use ($Token) {
                $word = '';
                $get = $Request->getGetData();
                if (!empty($get)) {
                    $word = http_build_query($get);
                }
                $post = $Request->getPostData();
                if (!empty($post)) {
                    $word .= '&' . http_build_query($post);
                }
                $word .= $Token->getSecret();
                return md5($word);
            });
        }
        return ResponseFactory::createResponse($Request->send());
    }

    /**
     * Add parameters for the request
     * @param \Network\Http\Request $Request request instance
     * @param array $get GET method parameters
     * @param array $post POST method parameters
     */
    private function addRequestParameters(Request &$Request, array $get = null, array $post = null) {
        if (!is_null($get)) {
            foreach ($get as $key => $value) {
                $Request->addGetField($key, $value);
            }
        }

        if (!is_null($post)) {
            foreach ($post as $key => $value) {
                $Request->addPostField($key, $value);
            }
        }
    }
}
