<?php
declare(strict_types=1);

namespace App\Service;

use App\Exception\ApiException;
use App\Exception\UnsupportedRequestException;
use App\Model\CredentialModel;
use DateTime;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiConnectionService
{
    public const BASE_URL = 'https://api.domain.com';
    public const REGISTER_ENDPOINT = '/task/register';
    public const POSTS_ENDPOINT = '/task/posts';

    private string $clientId = '';
    private string $email = '';
    private string $name = '';
    private HttpWrapperService $httpWrapper;
    private FileCacheService $cacheService;

    /**
     * Connector constructor.
     * @param CredentialModel $credential
     * @param HttpWrapperService $client
     * @param FileCacheService $cacheService
     */
    public function __construct(
        CredentialModel $credential,
        HttpWrapperService $client,
        FileCacheService $cacheService
    ) {
        $this->clientId = $credential->getClientId();
        $this->email = $credential->getEmail();
        $this->name = $credential->getName();
        $this->httpWrapper = $client;
        $this->cacheService = $cacheService;
    }

    /**
     * @param int $page
     * @return array|null
     * @throws ApiException
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws UnsupportedRequestException
     */
    public function getPosts(int $page = 1): ?array
    {
        $url = self::BASE_URL . self::POSTS_ENDPOINT;
        $getParams = [
            'sl_token' => $this->getToken(),
            'page' => $page,
        ];

        $response = $this->httpWrapper->get($url, $getParams);

        if ($response->getStatusCode() !== 200) {
            $this->handleError($response);
        }

        $data = json_decode($response->getContent(), true);
        if (!array_key_exists('data', $data)) {
            throw new ApiException('Missing data, status code: ' . $response->getStatusCode());
        }

        return $data['data']['posts'];
    }

    /**
     * @return string
     * @throws ApiException
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws UnsupportedRequestException
     */
    private function getToken(): string
    {
        $slToken = $this->cacheService->getValue('sl_token');
        $keyDateTime = $this->cacheService->getValue('datetime');

        $dateTimeNow = new DateTime();
        $dateTimeNow->modify('-1 hour');

        if ($slToken === '' || strtotime($keyDateTime) < strtotime($dateTimeNow->format('Y-m-d H:m:s'))) {
            return $this->registerToken();
        }

        return $slToken;
    }

    /**
     * @return string
     * @throws ApiException
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws UnsupportedRequestException
     */
    public function registerToken(): string
    {
        $url = self::BASE_URL . self::REGISTER_ENDPOINT;
        $postParams = [
            'client_id' => $this->clientId,
            'email' => $this->email,
            'name' => $this->name,
        ];

        $response = $this->httpWrapper->post($url, $postParams);
        if ($response->getStatusCode() !== 200) {
            $this->handleError($response);
        }

        $json = json_decode($response->getContent(), true);
        if (!array_key_exists('data', $json)) {
            throw new ApiException('Missing data, status code: ' . $response->getStatusCode());
        }

        // local api file caching... anything else could be used here - e.g. Redis key-value caching
        $dateTimeNow = new DateTime();
        $slToken = $json['data']['sl_token'];
        $this->cacheService->setKey('sl_token', $slToken);
        $this->cacheService->setKey('datetime', $dateTimeNow->format('Y-m-d H:m:s'));
        $this->cacheService->store();

        return $slToken;
    }

    /**
     * Generic error handling method
     * Deals with cases when response code is not 200
     *
     * @param ResponseInterface $response
     * @return void
     * @throws ApiException
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function handleError(ResponseInterface $response): void
    {
        $json = json_decode($response->getContent(false), true);
        $code = $response->getStatusCode();

        if (!array_key_exists('error', $json) || !array_key_exists('message', $json['error'])) {
            throw new ApiException('Unknown error, status code: ' . $code);
        }

        throw new ApiException($json['error']['message'] . ', status code ' . $code);
    }
}
