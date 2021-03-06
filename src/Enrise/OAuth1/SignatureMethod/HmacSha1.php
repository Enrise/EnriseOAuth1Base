<?php
/**
 * Enrise OAuth1Base  (http://enrise.com/)
 *
 * @link      https://github.com/Enrise/EnriseOAuth1Base for the canonical source repository
 * @copyright Copyright (c) 2012 Dolf Schimmel - Freeaqingme (dolfschimmel@gmail.com)
 * @copyright Copyright (c) 2012 Enrise (www.enrise.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace  Enrise\OAuth1\SignatureMethod;

use Enrise\OAuth1\Request;

class HmacSha1 extends AbstractSignatureMethod
{

    public function buildSignature($request, $consumerSecret, $tokenSecret = '') {
        $base_string = $request->getSignatureBaseString();
        $key_parts = Request::urlEncode(array($consumerSecret, $tokenSecret));

        return base64_encode(hash_hmac('sha1', $base_string, implode('&', $key_parts), true));
    }

}
