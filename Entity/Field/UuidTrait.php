<?php
namespace Mero\Bundle\BaseBundle\Entity\Field;

trait UuidTrait
{
    /**
     * @var string Primary key UUID
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $uuid;

    /**
     * Return primary key UUID identifier.
     *
     * @return string Primary key UUID
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Sets primary key UUID identifier.
     *
     * @param string $uuid Primary key UUID
     *
     * @return UuidTrait
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }


}
