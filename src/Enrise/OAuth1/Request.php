<?php
/**
 * Enrise OAuth1Base  (http://enrise.com/)
 *
 * @link      https://github.com/Enrise/EnriseOAuth1Base for the canonical source repository
 * @copyright Copyright (c) 2012 Dolf Schimmel - Freeaqingme (dolfschimmel@gmail.com)
 * @copyright Copyright (c) 2012 Enrise (www.enrise.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace  Enrise\OAuth1;

use Enrise\OAuth1\SignatureMethod;
use Zend\Uri\Http as Url;
use Zend\Stdlib\Parameters as QueryString;

class Request
{

    /**
     * @var string
     */
    protected $version;

    /**
     * @var string
     */
    protected $nonce;

    /**
     * @var string
     */
    protected $timestamp;

    /**
     * @var string
     */
    protected $consumerKey;

    /**
     * @var SignatureMethod\AbstractSignatureMethod
     */
    protected $signatureMethod;

    /**
     * @var string
     */
    protected $signature;

    /**
     * @var string
     */
    protected $httpRequestMethod;

    /**
     * @var \Zend\Uri\Http
     */
    protected $url;

    /**
     * @var \Zend\Stdlib\Parameters
     */
    protected $httpQueryString;



    public function getSignatureBaseString() {
        $parts = array(
            $this->getHttpRequestMethod(),
            $this->getNormalizedUrlString(),
            $this->getSignableParameters()
        );

        $parts = $this->urlEncode($parts);

        return implode('&', $parts);
    }

    public function getNormalizedUrlString()
    {
        $url = clone $this->getUrl();
        return $url->getScheme() . '://' . $url->getHost() . $url->getPath();
    }

    public function getSignableParameters()
    {
        $params = $this->getHttpQueryString();

        if ($params->get('oauth_signature')) {
            $params->offsetUnset('oauth_signature');
        }

        return $this->buildHttpQuery($params);
    }

    protected function buildHttpQuery($params) {
        if (!$params) {
            return '';
        }

        $params = $params->toArray();
        $keys = $this->urlEncode(array_keys($params));
        $values = $this->urlEncode(array_values($params));
        $params = array_combine($keys, $values);

        uksort($params, 'strcmp');

        $pairs = array();
        foreach ($params as $parameter => $value) {
            if (is_array($value)) {
                sort($value, SORT_STRING);
                foreach ($value as $duplicate_value) {
                    $pairs[] = $parameter . '=' . $duplicate_value;
                }
            } else {
                $pairs[] = $parameter . '=' . $value;
            }
        }

        return implode('&', $pairs);
    }

    public static function urlEncode(array $input) {
        $callback = function($input) {
            return str_replace(array('+', '%7E'), array(' ', '~'), rawurlencode($input));
        };

        return array_map($callback, $input);
    }

    /**
     * @param string $consumerKey
     */
    public function setConsumerKey($consumerKey)
    {
        $this->consumerKey = $consumerKey;
    }

    /**
     * @return string
     */
    public function getConsumerKey()
    {
        return $this->consumerKey;
    }

    /**
     * @param QueryString $httpQueryString
     */
    public function setHttpQueryString(QueryString $httpQueryString)
    {
        $this->httpQueryString = $httpQueryString;
    }

    /**
     * @return QueryString
     */
    public function getHttpQueryString()
    {
        return $this->httpQueryString;
    }

    /**
     * @param string $httpRequestMethod
     */
    public function setHttpRequestMethod($httpRequestMethod)
    {
        $this->httpRequestMethod = strtoupper($httpRequestMethod);
    }

    /**
     * @return string
     */
    public function getHttpRequestMethod()
    {
        return $this->httpRequestMethod;
    }

    /**
     * @param string $nonce
     */
    public function setNonce($nonce)
    {
        $this->nonce = $nonce;
    }

    /**
     * @return string
     */
    public function getNonce()
    {
        return $this->nonce;
    }

    /**
     * @param string $signature
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
    }

    /**
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param $signatureMethod
     * @throws \RuntimeException
     * @throws \DomainException
     */
    public function setSignatureMethod($signatureMethod)
    {
        if (is_object($signatureMethod))
        {
            if (!$signatureMethod instanceof SignatureMethod\AbstractSignatureMethod) {
                throw new \RuntimeException('The given argument is not an instance of AbstractSignatureMethod');
            }

            $this->signatureMethod = $signatureMethod;
            return;
        }

        switch($signatureMethod) {
            case 'HMAC-SHA1':
                $class = 'HmacSha1';
                break;
            case 'PLAINTEXT':
                $class = 'PlainText';
                break;
            case 'RSA-SHA1':
                $class = 'RsaSha1';
                break;
            default:
                throw new \DomainException('Invalid Signature Method specified: ' . $signatureMethod);
        }

        $class = __NAMESPACE__ . '\SignatureMethod\\' . $class;
        $this->signatureMethod = new $class();;
    }

    /**
     * @return SignatureMethod\AbstractSignatureMethod
     */
    public function getSignatureMethod()
    {
        return $this->signatureMethod;
    }

    /**
     * @param string $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param \Zend\Uri\Http $url
     */
    public function setUrl(Url $url)
    {
        $this->url = $url;
    }

    /**
     * @return \Zend\Uri\Http
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

}