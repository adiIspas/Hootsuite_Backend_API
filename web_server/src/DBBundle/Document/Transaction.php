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
     * @MongoDB\Field(type="integer")
     */
    protected $sender_id;

    /**
     * @MongoDB\Field(type="integer")
     */
    protected $receiver_id;

    /**
     * @MongoDB\Field(type="integer")
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
     * @param integer $senderId
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
     * @return integer $senderId
     */
    public function getSenderId()
    {
        return $this->sender_id;
    }

    /**
     * Set receiverId
     *
     * @param integer $receiverId
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
     * @return integer $receiverId
     */
    public function getReceiverId()
    {
        return $this->receiver_id;
    }

    /**
     * Set ts
     *
     * @param integer $ts
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
     * @return integer $ts
     */
    public function getTs()
    {
        return $this->ts;
    }

    /**
     * Set sum
     *
     * @param integer $sum
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
     * @return integer $sum
     */
    public function getSum()
    {
        return $this->sum;
    }
}
