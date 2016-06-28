<?php

namespace Mero\Bundle\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Common entity class with simple identifier(integer type).
 *
 * @author Rafael Mello <merorafael@gmail.com>
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractEntityClassic
{
    use Field\IdTrait, Field\CreatedTrait, Field\ModifiedTrait;

    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->modified = new \DateTime('now');
    }
}
