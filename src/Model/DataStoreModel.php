<?php
declare(strict_types=1);

namespace App\Model;

use JMS\Serializer\Annotation\Type;

/**
 * Class DataStoreModel
 * @package App\Model
 */
class DataStoreModel
{
    /**
     * Array of year-month and list of message lengths
     * @var array
     * @Type("array")
     */
    public array $averageMessageLength = [];

    /**
     * Array of year month and post model with longest post message
     * @var array
     * @Type("array")
     */
    public array $longestMessage = [];

    /**
     * Array of year week and number of posts per that week
     * @var array
     * @Type("array")
     */
    public array $postsWeekly = [];

    /**
     * Array of year month with user list and each user post count
     * @var array
     * @Type("array")
     */
    public array $averageUserPosts = [];

    /**
     * @return array
     */
    public function getLongestMessage(): array
    {
        return $this->longestMessage;
    }

    /**
     * @param PostModel $postModel
     */
    public function setLongestMessage(PostModel $postModel): void
    {
        $longestMessageFound = null;
        $yearMonth = $postModel->getYearMonth();
        if (array_key_exists($yearMonth, $this->longestMessage)) {
            /** @var PostModel $longestMessageFound */
            $longestMessageFound = $this->longestMessage[$yearMonth];
            if ($postModel->getMessageLength() > $longestMessageFound->getMessageLength()) {
                $this->longestMessage[$yearMonth] = $postModel;
            }
        }

        if ($longestMessageFound === null) {
            $this->longestMessage[$yearMonth] = $postModel;
        }
    }

    /**
     * @return array
     */
    public function getAverageMessageLength(): array
    {
        return $this->averageMessageLength;
    }

    /**
     * @param PostModel $postModel
     */
    public function setAverageMessageLength(PostModel $postModel): void
    {
        $yearMonth = $postModel->getYearMonth();
        $this->averageMessageLength[$yearMonth][] = $postModel->getMessageLength();
    }

    /**
     * @return array
     */
    public function getAverageUserPosts(): array
    {
        return $this->averageUserPosts;
    }

    /**
     * @param PostModel $postModel
     */
    public function setAverageUserPosts(PostModel $postModel): void
    {
        $yearMonth = $postModel->getYearMonth();
        $fromId = $postModel->getFromId();
        if (!array_key_exists($fromId, $this->averageUserPosts)
            || !array_key_exists($yearMonth, $this->averageUserPosts[$fromId])) {
            $this->averageUserPosts[$fromId][$yearMonth] = 0;
        }
        $this->averageUserPosts[$fromId][$yearMonth] += 1;
    }

    /**
     * @return array
     */
    public function getPostsWeekly(): array
    {
        return $this->postsWeekly;
    }

    /**
     * @param PostModel $postModel
     */
    public function setPostsWeekly(PostModel $postModel): void
    {
        $yearWeek = $postModel->getYearWeek();
        if (!array_key_exists($yearWeek, $this->postsWeekly)) {
            $this->postsWeekly[$yearWeek] = 0;
        }
        $this->postsWeekly[$yearWeek] += 1;
    }
}
