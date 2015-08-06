<?php

namespace Mero\Bundle\BaseBundle\Entity\Field;

/**
 * @author Rafael Mello <merorafael@gmail.com>
 */
trait CreatedTrait
{
    /**
     * @var \DateTime Date created
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created;

    /**
     * Return created date if any.
     *
     * @return null|\DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Sets date created.
     *
     * @param \DateTime $created
     *
     * @return object
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }
}
