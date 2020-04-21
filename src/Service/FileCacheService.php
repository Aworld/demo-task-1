<?php
declare(strict_types=1);

namespace App\Service;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

class FileCacheService
{
    private const CACHE_DIR = '/var/www/html/cache';

    private string $storageName;
    private SerializerInterface $serializer;
    private array $content = [];
    private string $cacheFile = '';

    /**
     * FileCacheService constructor.
     * @param string $storageName
     */
    public function __construct(string $storageName)
    {
        $this->storageName = $storageName;
        $this->cacheFile = self::CACHE_DIR . '/' . $this->storageName;
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setKey(string $key, string $value): void
    {
        $this->content[$key] = $value;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getValue(string $key): ?string
    {
        if (!file_exists($this->cacheFile)) {
            return '';
        }
        $cache = json_decode(file_get_contents($this->cacheFile), true);

        return $cache[$key] ?? '';
    }

    /**
     * @return null
     */
    public function store()
    {
        if ($this->storageName === '' || count($this->content) < 1) {
            return null;
        }

        $json = $this->serializer->serialize($this->content, 'json');
        file_put_contents($this->cacheFile, $json);
    }
}
