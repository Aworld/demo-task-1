<?php
declare(strict_types=1);

namespace App\Model;

use DateTime;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

/**
 * Class PostModel
 * @package App\Model
 */
class PostModel
{
    private const YEAR_MONTH_FORMAT = 'Y-m';
    private const YEAR_WEEK_FORMAT = 'Y-W';

    /**
     * @var string
     * @Type("string")
     * @SerializedName("id")
     */
    public string $id;

    /**
     * @var string
     * @Type("string")
     * @SerializedName("from_name")
     */
    public string $fromName = '';

    /**
     * @var string
     * @Type("string")
     * @SerializedName("from_id")
     */
    public string $fromId = '';

    /**
     * @var string
     * @Type("string")
     * @SerializedName("message")
     */
    public string $message = '';

    /**
     * @var string
     * @Type("string")
     * @SerializedName("type")
     */
    public string $type = '';

    /**
     * @var DateTime
     * @Type("DateTime")
     * @SerializedName("created_time")
     */
    public DateTime $createdTime;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return $this->fromName;
    }

    /**
     * @param string $fromName
     */
    public function setFromName(string $fromName): void
    {
        $this->fromName = $fromName;
    }

    /**
     * @return string
     */
    public function getFromId(): string
    {
        return $this->fromId;
    }

    /**
     * @param string $fromId
     */
    public function setFromId(string $fromId): void
    {
        $this->fromId = $fromId;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return DateTime
     */
    public function getCreatedTime(): DateTime
    {
        return $this->createdTime;
    }

    /**
     * @param DateTime $createdTime
     */
    public function setCreatedTime(DateTime $createdTime): void
    {
        $this->createdTime = $createdTime;
    }

    /**
     * @return int
     */
    public function getMessageLength()
    {
        return mb_strlen($this->message);
    }

    /**
     * @return string
     */
    public function getYearMonth()
    {
        return $this->getCreatedTime()->format(self::YEAR_MONTH_FORMAT);
    }

    /**
     * @return string
     */
    public function getYearWeek()
    {
        return $this->getCreatedTime()->format(self::YEAR_WEEK_FORMAT);
    }
}
