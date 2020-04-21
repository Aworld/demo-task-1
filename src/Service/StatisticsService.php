<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\DataStoreModel;
use App\Model\StatisticsModel;

class StatisticsService
{
    private StatisticsModel $statisticsModel;

    /**
     * StatisticsService constructor.
     * @param StatisticsModel $statisticsModel
     */
    public function __construct(StatisticsModel $statisticsModel)
    {
        $this->statisticsModel = $statisticsModel;
    }

    /**
     * @param DataStoreModel $dataStoreModel
     * @return $this
     */
    public function getStatistics(DataStoreModel $dataStoreModel): self
    {
        $this->statisticsModel->setAverageMessageLength($dataStoreModel);
        $this->statisticsModel->setAverageUserPosts($dataStoreModel);
        $this->statisticsModel->setPostsWeekly($dataStoreModel);
        $this->statisticsModel->setLongestMessage($dataStoreModel);

        return $this;
    }
}
