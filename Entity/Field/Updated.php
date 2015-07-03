<?php
namespace Mero\Bundle\BaseBundle\Entity\Field;

/**
 * @package Mero\Bundle\BaseBundle\Entity\Field
 * @author Rafael Mello <merorafael@gmail.com>
 */
trait Updated
{

    /**
     * @var \DateTime Date modified
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated;

    /**
     * Returns registry modification date if any.
     *
     * @return null|\DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Sets modified date.
     *
     * @param \DateTime $updated Date modified
     *
     * @return Updated
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * Method to update modified date.
     *
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->updated = new \DateTime();
    }

}
