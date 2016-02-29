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
abstract class AbstractEntityWithUuid
{
    use Field\UuidTrait, Field\CreatedTrait, Field\ModifiedTrait;

    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->modified = new \DateTime('now');
    }
}
