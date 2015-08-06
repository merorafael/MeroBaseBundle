<?php

namespace Mero\Bundle\BaseBundle\Entity\Field;

/**
 * @author Rafael Mello <merorafael@gmail.com>
 */
trait IdTrait
{
    /**
     * @var int Primary key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * Return primary key identifier.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets primary key identifier.
     *
     * @param int $id
     *
     * @return object
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
