<?php
namespace Mero\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @package Mero\BaseBundle\Entity
 * @author Rafael Mello <merorafael@gmail.com>
 * @link https://github.com/merorafael/MeroBaseBundle Repositório do projeto
 * @copyright Copyright (c) 2014 - Rafael Mello
 * @license https://github.com/merorafael/MeroBaseBundle/blob/master/LICENSE MIT license
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class StdEntity
{
    
    /**
     * @var int Identificação(ID) do registro
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @var \DateTime Data de criação do dado
     * 
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created;
    
    /**
     * @var \DateTime Data da ultima alteração do dado
     * 
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated;

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }

    /**
     * Retorna identificação(ID) relacionado ao registro.
     * 
     * @return int ID do registro
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Define identificação(ID) relacionado ao registro.
     * 
     * @param int $id Identificação(ID) do registro
     * @return AbstractEntity
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Retorna instancia de DateTime relacionada a data de criação do registro.
     * 
     * @return \DateTime Data de criação do registro
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Define instancia de DateTime relacionada a data de criação do registro.
     * 
     * @param \DateTime $created Data de criação do registro
     * @return AbstractEntity
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Retorna instancia de DateTime relacionada a data de atualização do registro.
     * 
     * @return \DateTime Data de atualização do registro
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Define instancia de DateTime relacionada a data de atualização do registro.
     * 
     * @param \DateTime $updated Data de atualização do registro
     * @return AbstractEntity
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * Método responsável por inserir data da ultima atualização
     * do registro em questão.
     * 
     * @ORM\PreUpdate
     */
    public function updated()
    {
        $this->updated = new \DateTime();
    }
}
