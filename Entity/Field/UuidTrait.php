<?php

namespace Mero\Bundle\BaseBundle\Entity\Field;

trait UuidTrait
{
    /**
     * @var string Primary key UUID
     *
     * @ORM\Id
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * Return primary key UUID identifier.
     *
     * @return string Primary key UUID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets primary key UUID identifier.
     *
     * @param string $id Primary key UUID
     *
     * @return UuidTrait
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
