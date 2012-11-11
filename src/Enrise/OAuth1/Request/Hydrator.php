<?php
/**
 * Enrise OAuth1Base  (http://enrise.com/)
 *
 * @link      https://github.com/Enrise/EnriseOAuth1Base for the canonical source repository
 * @copyright Copyright (c) 2012 Dolf Schimmel - Freeaqingme (dolfschimmel@gmail.com)
 * @copyright Copyright (c) 2012 Enrise (www.enrise.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace  Enrise\OAuth1\Request;

use Enrise\OAuth1\Request as OAuthRequest;
use Zend\Stdlib\Hydrator\HydratorInterface;

class Hydrator implements HydratorInterface
{
    protected $keys = array('oauth_version'          => 'version',
                            'oauth_nonce'            => 'nonce',
                            'oauth_timestamp'        => 'timestamp',
                            'oauth_consumer_key'     => 'consumerKey',
                            'oauth_signature_method' => 'signatureMethod',
                            'oauth_signature'        => 'signature',
                            'request_method'         => 'httpRequestMethod',
                            'url'                    => 'url',
                            'query_string'           => 'httpQueryString');

    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     */
    public function extract($object) {

    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Enrise\OAuth1\Request $object
     * @return \Enrise\OAuth1\Request
     * @throws \RuntimeException
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof OAuthRequest) {
            throw new \RuntimeException(__METHOD__ .' expects an instance of Enrise\OAuth1\Request');
        }

        $diffKeys = array_diff_key($this->keys, $data);
        if (count($diffKeys) > 0) {
            throw new \RuntimeException('Some required keys were not supplied: ' . print_r($diffKeys,1));
        }

        foreach($this->keys as $key => $value) {
            $object->{'set' . ucfirst($value)}($data[$key]);
        }

        return $object;
    }

}
