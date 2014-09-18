<?php
namespace Mero\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Classe abstrata para entidades
 *
 * @package BaseBundle\Entity
 * @author Rafael Mello <merorafael@gmail.com>
 * @copyright Copyright (c) 2014 - Rafael Mello
 * @license https://github.com/merorafael/MeroBaseBundle/blob/master/LICENSE BSD license
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractEntity
{
    
    /**
     * Primary Key na tabela do banco de dados
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * Data de criação dos dados
     * 
     * @ORM\Column(type="datetime")
     */
    protected $created;
    
    /**
     * Data da ultima alteração dos dados
     * 
     * @ORM\Column(type="datetime")
     */
    protected $updated;
    
    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
        return $this;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * Responsável por alterar valor de updated antes de cada
     * atualização.
     * 
     * @ORM\PreUpdate
     */
    public function updated()
    {
        $this->setUpdated(new \DateTime('now'));
    }
    
}