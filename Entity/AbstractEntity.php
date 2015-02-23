<?php
namespace Mero\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @package Mero\BaseBundle\Entity
 *
 * @deprecated Não recomendado extender entidades de AbstractEntity,
 * será substituido pela classe StdEntity na versão 1.1.
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractEntity extends StdEntity
{
}
