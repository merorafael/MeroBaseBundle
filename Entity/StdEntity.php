<?php
namespace Mero\Bundle\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mero\Bundle\BaseBundle\Entity\Field;

/**
 * Common entity class.
 *
 * @package Mero\Bundle\BaseBundle\Entity
 * @author Rafael Mello <merorafael@gmail.com>
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class StdEntity
{

    use Field\Id, Field\Created, Field\Updated;

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }

}
