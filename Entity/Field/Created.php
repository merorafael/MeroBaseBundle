<?php
namespace Mero\BaseBundle\Entity\Field;

/**
 * @package Mero\BaseBundle\Entity\Field
 * @author Rafael Mello <merorafael@gmail.com>
 */
trait Created
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
     * @return Created
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
        return $this;
    }

}
