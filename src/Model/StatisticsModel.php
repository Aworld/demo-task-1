<?php
declare(strict_types=1);

namespace App\Model;

use JMS\Serializer\Annotation\AccessorOrder;
use JMS\Serializer\Annotation\Type;

/**
 * Class StatisticsModel
 * @package App\Model
 *
 * @AccessorOrder("alphabetical")
 */
class StatisticsModel
{
    /**
     * Average character length of a post / month
     * @var array
     * @Type("array")
     */
    public array $averageMessageLength = [];

    /**
     * Longest post by character length / month
     * @var array
     * @Type("array")
     */
    public array $longestMessage = [];

    /**
     * Total posts split by week
     * @var array
     * @Type("array")
     */
    public array $postsWeekly = [];

    /**
     * Average number of posts per user / month
     * @var array
     * @Type("array")
     */
    public array $averageUserPosts = [];

    /**
     * @param DataStoreModel $dataStoreModel
     */
    public function setAverageMessageLength(DataStoreModel $dataStoreModel): void
    {
        foreach ($dataStoreModel->getAverageMessageLength() as $yearMonth => $data) {
            $this->averageMessageLength[$yearMonth] = round(
                array_sum($data)/count($data),
                0,
                PHP_ROUND_HALF_UP
            );
        }
    }

    /**
     * @param DataStoreModel $dataStoreModel
     */
    public function setLongestMessage(DataStoreModel $dataStoreModel): void
    {
        foreach ($dataStoreModel->getLongestMessage() as $yearMonth => $post) {
            /** @var PostModel $post */
            $messageLength = $post->getMessageLength();
            $this->longestMessage[$yearMonth] = [
                'message_length' => $messageLength,
            ];
        }
    }

    /**
     * @param DataStoreModel $dataStoreModel
     */
    public function setPostsWeekly(DataStoreModel $dataStoreModel): void
    {
        $this->postsWeekly = $dataStoreModel->getPostsWeekly();
    }

    /**
     * @param DataStoreModel $dataStoreModel
     */
    public function setAverageUserPosts(DataStoreModel $dataStoreModel): void
    {
        foreach ($dataStoreModel->getAverageUserPosts() as $fromId => $data) {
            $this->averageUserPosts[$fromId] = round(
                array_sum($data)/count($data),
                0,
                PHP_ROUND_HALF_UP
            );
        }
    }
}
