<?php
/**
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Buddy;

use Buddy\Exceptions\BuddySDKException;
use Buddy\Exceptions\BuddyResponseException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class BuddyClient
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $guzzle;

    /**
     * BuddyClient constructor.
     */
    public function __construct()
    {
        $this->guzzle = new Client();
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $options
     * @return string
     * @throws BuddyResponseException
     * @throws BuddySDKException
     */
    private function request($url, $method, $options = [])
    {
        array_merge($options, [
            'timeout' => 60,
            'connect_timeout' => 30
        ]);
        $request = $this->guzzle->createRequest($method, $url, $options);
        try {
            $rawResponse = $this->guzzle->send($request);

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $rawResponse = $e->getResponse();
            } else {
                throw new BuddySDKException($e->getMessage(), $e->getCode());
            }
        }
        $httpStatusCode = $rawResponse->getStatusCode();
        if ($httpStatusCode >= 200 && $httpStatusCode < 300) {
            return (string)$rawResponse->getBody();
        } else {
            throw new BuddyResponseException($rawResponse);
        }
    }

    /**
     * @param string $accessToken
     * @param string $url
     * @param string $method
     * @param null|array $body
     * @return string
     * @throws BuddyResponseException
     * @throws BuddySDKException
     */
    private function requestJson($accessToken, $url, $method, $body = null)
    {
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ];
        if (isset($body)) {
            $options['json'] = $body;
        }
        return $this->request($url, $method, $options);
    }

    /**
     * @param string $accessToken
     * @param string $url
     * @return string
     */
    public function getJson($accessToken, $url)
    {
        return $this->requestJson($accessToken, $url, 'GET');
    }

    /**
     * @param string $accessToken
     * @param string $url
     * @param null|array $body
     * @return string
     */
    public function deleteJson($accessToken, $url, $body = null)
    {
        return $this->requestJson($accessToken, $url, 'DELETE', $body);
    }

    /**
     * @param string $accessToken
     * @param string $url
     * @param array $body
     * @return string
     */
    public function postJson($accessToken, $url, $body)
    {
        return $this->requestJson($accessToken, $url, 'POST', $body);
    }

    /**
     * @param string $accessToken
     * @param string $url
     * @param array $body
     * @return string
     */
    public function putJson($accessToken, $url, $body)
    {
        return $this->requestJson($accessToken, $url, 'PUT', $body);
    }

    /**
     * @param string $accessToken
     * @param string $url
     * @param array $body
     * @return string
     */
    public function patchJson($accessToken, $url, $body)
    {
        return $this->requestJson($accessToken, $url, 'PATCH', $body);
    }
}