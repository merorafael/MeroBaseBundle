<?php
namespace Mero\BaseBundle\Entity;

/**
 * Classe abstrata para entidades
 *
 * @package Mero\BaseBundle\Entity
 * @author Rafael Mello <merorafael@gmail.com>
 * @link https://github.com/merorafael/MeroBaseBundle Repositório do projeto
 * @copyright Copyright (c) 2014 - Rafael Mello
 * @license https://github.com/merorafael/MeroBaseBundle/blob/master/LICENSE MIT license
 *
 * @Doctrine\ORM\Mapping\MappedSuperclass
 * @Doctrine\ORM\Mapping\HasLifecycleCallbacks
 */
abstract class AbstractEntity
{
    
    /**
     * @var integer Identificação(ID) do registro
     * 
     * @Doctrine\ORM\Mapping\Id
     * @Doctrine\ORM\Mapping\Column(type="integer")
     * @Doctrine\ORM\Mapping\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @var \DateTime Data de criação dos dados
     * 
     * @Doctrine\ORM\Mapping\Column(type="datetime", nullable=true)
     */
    protected $created;
    
    /**
     * @var \DateTime Data da ultima alteração dos dados
     * 
     * @Doctrine\ORM\Mapping\Column(type="datetime", nullable=true)
     */
    protected $updated;
    
    /**
     * Método construtor
     */
    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
    }

    /**
     * Retorna identificação(ID) relacionado ao registro.
     * 
     * @return integer ID do registro
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Define identificação(ID) relacionado ao registro.
     * 
     * @param integer $id Identificação(ID) do registro
     * @return \Mero\BaseBundle\Entity\AbstractEntity
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Retorna instancia de DateTime relacionada a data de criação do registro.
     * 
     * @return DateTime Data de criação do registro
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Define instancia de DateTime relacionada a data de criação do registro.
     * 
     * @param \DateTime $created Data de criação do registro
     * @return \Mero\BaseBundle\Entity\AbstractEntity
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Retorna instancia de DateTime relacionada a data de atualização do registro.
     * 
     * @return DateTime Data de atualização do registro
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Define instancia de DateTime relacionada a data de atualização do registro.
     * 
     * @param \DateTime $updated Data de atualização do registro
     * @return \Mero\BaseBundle\Entity\AbstractEntity
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * Responsável por alterar valor de updated antes de cada
     * atualização.
     * 
     * @Doctrine\ORM\Mapping\PreUpdate
     */
    public function updated()
    {
        $this->setUpdated(new \DateTime('now'));
    }
    
}