<?php
declare(strict_types=1);

namespace App\Service;

use App\Exception\UnsupportedRequestException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class HttpWrapperService
{
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';

    private HttpClientInterface $httpClient;

    public function __construct()
    {
        $this->httpClient = HttpClient::create();
    }

    /**
     * @param string $url
     * @param array $queryParams
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     * @throws UnsupportedRequestException
     */
    public function get(string $url, array $queryParams = []): ResponseInterface
    {
        return $this->request($url,self::METHOD_GET, $queryParams);
    }

    /**
     * @param string $url
     * @param array $payload
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     * @throws UnsupportedRequestException
     */
    public function post(string $url, array $payload = []): ResponseInterface
    {
        return $this->request($url,self::METHOD_POST, $payload);
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $data
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     * @throws UnsupportedRequestException
     */
    private function request(string $url, string $method, array $data): ResponseInterface
    {
        switch ($method) {
            case self::METHOD_GET:
                $options['query'] = $data;
                break;
            case self::METHOD_POST:
                $options['body'] = $data;
                break;
            default:
                throw new UnsupportedRequestException(sprintf('Method %s is not supported', $method));
        }

        return $this->httpClient->request($method, $url, $options);
    }
}
