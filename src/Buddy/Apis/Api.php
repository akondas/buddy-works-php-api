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

namespace Buddy\Apis;

use Buddy\BuddyClient;
use Buddy\Exceptions\BuddySDKException;

class Api
{
    /**
     * @var BuddyClient
     */
    protected $client;

    /**
     * @var array
     */
    protected $options;

    /**
     * Api constructor.
     * @param BuddyClient $client
     * @param array $options
     */
    public function __construct(BuddyClient $client, array $options = [])
    {
        $this->client = $client;
        $this->options = $options;
    }

    /**
     * @param string $accessToken
     * @param string $url
     * @param array $params
     * @param array $query
     * @param string $path
     * @return \Buddy\BuddyResponse
     * @throws BuddySDKException
     */
    protected function getJson($accessToken, $url, array $params = [], array $query = [], $path = '/')
    {
        return $this->client->getJson($this->getAccessToken($accessToken), $this->client->createUrl($url, $params, $query, $path));
    }

    /**
     * @param string $accessToken
     * @param array $patchData
     * @param string $url
     * @param array $params
     * @param array $query
     * @param string $path
     * @return \Buddy\BuddyResponse
     * @throws BuddySDKException
     */
    protected function patchJson($accessToken, array $patchData, $url, array $params = [], array $query = [], $path = '/')
    {
        return $this->client->patchJson($this->getAccessToken($accessToken), $this->client->createUrl($url, $params, $query, $path), $patchData);
    }

    /**
     * @param string $accessToken
     * @param array|null $deleteData
     * @param string $url
     * @param array $params
     * @param array $query
     * @param string $path
     * @return \Buddy\BuddyResponse
     * @throws BuddySDKException
     */
    protected function deleteJson($accessToken, $deleteData, $url, array $params = [], array $query = [], $path = '/')
    {
        return $this->client->deleteJson($this->getAccessToken($accessToken), $this->client->createUrl($url, $params, $query, $path), $deleteData);
    }

    /**
     * @param string $accessToken
     * @param array $postData
     * @param string $url
     * @param array $params
     * @param array $query
     * @param string $path
     * @return \Buddy\BuddyResponse
     * @throws BuddySDKException
     */
    protected function postJson($accessToken, array $postData, $url, array $params = [], array $query = [], $path = '/')
    {
        return $this->client->postJson($this->getAccessToken($accessToken), $this->client->createUrl($url, $params, $query, $path), $postData);
    }

    /**
     * @param string $accessToken
     * @param array $putData
     * @param string $url
     * @param array $params
     * @param array $query
     * @param string $path
     * @return \Buddy\BuddyResponse
     * @throws BuddySDKException
     */
    protected function putJson($accessToken, array $putData, $url, array $params = [], array $query = [], $path = '/')
    {
        return $this->client->putJson($this->getAccessToken($accessToken), $this->client->createUrl($url, $params, $query, $path), $putData);
    }

    /**
     * @param string $accessToken
     * @return string
     * @throws BuddySDKException
     */
    protected function getAccessToken($accessToken)
    {
        if (isset($accessToken)) {
            return $accessToken;
        } else if (isset($this->options['accessToken'])) {
            return $this->options['accessToken'];
        } else {
            throw new BuddySDKException('No access token provided');
        }
    }
}
