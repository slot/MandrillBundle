<?php
/**
 * Representation of Mandrill user
 *
 * User: svenloth
 * Date: 19.04.15
 * Time: 19:18
 */

namespace Hip\MandrillBundle;


class UserInfo {

    /**
     * @var string
     */
    protected $username;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var string
     */
    protected $publicId;

    /**
     * @var int
     */
    protected $reputation;

    /**
     * @var int
     */
    protected $hourlyQuota;

    /**
     * @var int
     */
    protected $backlog;

    /**
     * @var array
     */
    protected $stats;

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getPublicId()
    {
        return $this->publicId;
    }

    /**
     * @param string $publicId
     */
    public function setPublicId($publicId)
    {
        $this->publicId = $publicId;
    }

    /**
     * @return int
     */
    public function getReputation()
    {
        return $this->reputation;
    }

    /**
     * @param int $reputation
     */
    public function setReputation($reputation)
    {
        $this->reputation = $reputation;
    }

    /**
     * @return int
     */
    public function getHourlyQuota()
    {
        return $this->hourlyQuota;
    }

    /**
     * @param int $hourlyQuota
     */
    public function setHourlyQuota($hourlyQuota)
    {
        $this->hourlyQuota = $hourlyQuota;
    }

    /**
     * @return int
     */
    public function getBacklog()
    {
        return $this->backlog;
    }

    /**
     * @param int $backlog
     */
    public function setBacklog($backlog)
    {
        $this->backlog = $backlog;
    }

    /**
     * @return array
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * @param array $stats
     */
    public function setStats($stats)
    {
        $this->stats = $stats;
    }


}