<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\DataStoreModel;
use App\Model\PostModel;

/**
 * Class CollectDataService
 * @package App\Service
 */
class CollectDataService
{
    private DataStoreModel $dataStoreModel;

    public function __construct()
    {
        $this->dataStoreModel = new DataStoreModel();
    }

    /**
     * @param PostModel $postModel
     */
    public function collect(PostModel $postModel): void
    {
        $this->dataStoreModel->setLongestMessage($postModel);
        $this->dataStoreModel->setAverageMessageLength($postModel);
        $this->dataStoreModel->setAverageUserPosts($postModel);
        $this->dataStoreModel->setPostsWeekly($postModel);
    }

    /**
     * @return DataStoreModel dataStoreModel
     */
    public function getData(): DataStoreModel
    {
        return $this->dataStoreModel;
    }
}
