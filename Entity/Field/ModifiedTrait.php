<?php

namespace Mero\Bundle\BaseBundle\Entity\Field;

/**
 * @author Rafael Mello <merorafael@gmail.com>
 */
trait ModifiedTrait
{
    /**
     * @var \DateTime Date modified
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $modified;

    /**
     * Returns registry modification date if any.
     *
     * @return null|\DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Sets modified date.
     *
     * @param \DateTime $modified Date modified
     *
     * @return object
     */
    public function setModified(\DateTime $modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Method to update modified date.
     *
     * @ORM\PreUpdate
     */
    public function updateModifiedDate()
    {
        $this->modified = new \DateTime();
    }
}
