<?php

namespace Mero\Bundle\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Common entity class.
 *
 * @author Rafael Mello <merorafael@gmail.com>
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractEntity
{
    use Field\IdTrait, Field\CreatedTrait, Field\ModifiedTrait;

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->modified = new \DateTime();
    }
}
