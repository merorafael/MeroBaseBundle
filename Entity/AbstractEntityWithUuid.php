<?php
namespace Mero\Bundle\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntityWithUuid
{
    use Field\UuidTrait, Field\CreatedTrait, Field\ModifiedTrait;

    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->modified = new \DateTime('now');
    }
}
