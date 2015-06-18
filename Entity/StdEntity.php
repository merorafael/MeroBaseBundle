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

    use Field\Id, Field\Created, Field\Modified;

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->modified = new \DateTime();
    }

    /**
     * Method to update modified date.
     *
     * @ORM\PreUpdate()
     */
    public function updateModifiedDate()
    {
        $this->modified = new \DateTime();
    }

}
