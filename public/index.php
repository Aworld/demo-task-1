<?php
declare(strict_types=1);
/**
 * API
 * @author Aivaras Riskus <aivaras.riskus@gmail.com>
 */

/**
 * Register The Auto Loader
 */
require_once __DIR__ . '/../vendor/autoload.php';

use App\Exception\EnvException;
use App\Model\CredentialModel;
use App\Model\PostModel;
use App\Model\StatisticsModel;
use App\Service\ApiConnectionService;
use App\Service\CollectDataService;
use App\Service\FileCacheService;
use App\Service\HttpWrapperService;
use App\Service\StatisticsService;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Dotenv\Dotenv;

/**
 * As standalone library we want to use annotations, so the annotation registry must be initialized
 * Special thanks to guys at https://jmsyst.com/libs/serializer
 * I wish symfony/serializer library would be a lot easier to start with ;)
 */
AnnotationRegistry::registerLoader('class_exists');
define('JMS_FORMAT', 'json');
$serializer = JMS\Serializer\SerializerBuilder::create()->build();

/**
 * load .env values
 */
$envFile = __DIR__ . '/../.env';

if (!file_exists($envFile)) {
    throw new EnvException("API is missing .env file. Please, read instructions under README.md.");
}

$dotenv = new Dotenv();
$dotenv->load($envFile);

/** Credentials model */
$credentials = new CredentialModel($_ENV['CLIENT_ID'], $_ENV['EMAIL'], $_ENV['NAME']);
/** Client to make http request */
$client = new HttpWrapperService();
/** Our custom file caching to keep sl_token stored locally so we wouldn't have to register new one each time */
$cache = new FileCacheService($_ENV['CLIENT_ID']);

/**
 * Lets start our API
 */
$api = new ApiConnectionService($credentials, $client, $cache);
$collectData = new CollectDataService();

/**
 * Looping through 10 pages, creating each post model and collecting data
 */
for ($page = 1; $page < 11; $page++) {
    $posts = $api->getPosts($page);
    foreach ($posts as $post) {
        /** @var PostModel $post */
        $postModel = $serializer->deserialize(json_encode($post), PostModel::class, JMS_FORMAT);
        $collectData->collect($postModel);
    }
}

/**
 * Now that we have data successfully collected, we can get its statistical information
 */
$statistics = new StatisticsService(new StatisticsModel());
echo $serializer->serialize($statistics->getStatistics($collectData->getData()), JMS_FORMAT);
