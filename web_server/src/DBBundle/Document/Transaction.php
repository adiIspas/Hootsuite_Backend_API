<?php
namespace DBBundle\Document;

/**
 * Created by PhpStorm.
 * User: Adrian Ispas
 * Date: 10.04.2017
 * Time: 16:27
 */
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="DBBundle\Repository\TransactionRepository")
 */

class Transaction
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sender_id;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $receiver_id;

    /**
     * @MongoDB\Field(type="timestamp")
     */
    protected $ts;

    /**
     * @MongoDB\Field(type="float")
     */
    protected $sum;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set senderId
     *
     * @param int $senderId
     * @return self
     */
    public function setSenderId($senderId)
    {
        $this->sender_id = $senderId;
        return $this;
    }

    /**
     * Get senderId
     *
     * @return int $senderId
     */
    public function getSenderId()
    {
        return $this->sender_id;
    }

    /**
     * Set receiverId
     *
     * @param int $receiverId
     * @return self
     */
    public function setReceiverId($receiverId)
    {
        $this->receiver_id = $receiverId;
        return $this;
    }

    /**
     * Get receiverId
     *
     * @return int $receiverId
     */
    public function getReceiverId()
    {
        return $this->receiver_id;
    }

    /**
     * Set ts
     *
     * @param timestamp $ts
     * @return self
     */
    public function setTs($ts)
    {
        $this->ts = $ts;
        return $this;
    }

    /**
     * Get ts
     *
     * @return timestamp $ts
     */
    public function getTs()
    {
        return $this->ts;
    }

    /**
     * Set sum
     *
     * @param float $sum
     * @return self
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Get sum
     *
     * @return float $sum
     */
    public function getSum()
    {
        return $this->sum;
    }
}
