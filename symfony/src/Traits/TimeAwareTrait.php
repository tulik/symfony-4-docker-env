<?php

declare(strict_types=1);

namespace App\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

trait TimeAwareTrait
{
    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @JMS\Exclude
     */
    protected $created;

    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @JMS\Exclude
     */
    protected $updated;

    /**
     * Set created.
     *
     * @param DateTime $created
     *
     * @return mixed
     */
    public function setCreated($created)
    {
        return $this->created = $created;
    }

    /**
     * Get created.
     *
     * @return DateTime
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * Set updated.
     *
     * @param DateTime $updated
     *
     * @return mixed
     */
    public function setUpdated($updated)
    {
        return $this->updated = $updated;
    }

    /**
     * Get updated.
     *
     * @return DateTime
     */
    public function getUpdated(): DateTime
    {
        return $this->updated;
    }
}
