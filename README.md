[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4612cf8e-4579-4ad5-a2ca-8e4620da09c8/mini.png)](https://insight.sensiolabs.com/projects/4612cf8e-4579-4ad5-a2ca-8e4620da09c8) [![Latest Stable Version](https://poser.pugx.org/mero/base-bundle/v/stable.svg)](https://packagist.org/packages/mero/base-bundle) [![Total Downloads](https://poser.pugx.org/mero/base-bundle/downloads.svg)](https://packagist.org/packages/mero/base-bundle) [![License](https://poser.pugx.org/mero/base-bundle/license.svg)](https://packagist.org/packages/mero/base-bundle)

Bundle para desenvolvimento ágil no Symfony 2. Recursos:

- StdController(Controller com métodos adicionais)
- StdCrudController(Controller abstrata para CRUD simples)
- StdEntity(Entidade abstrata contendo campos comuns)
- Layout base com jQuery 1.11.2, Bootstrap 3.3.2, Font-Awesome 4.3.0 embarcados.

## Requisitos mínimos:
- [php 5.3](http://php.net)
- [symfony/framework-bundle 2.3](https://packagist.org/packages/symfony/framework-bundle)
- [doctrine/orm 2.4](https://packagist.org/packages/doctrine/orm)
- [knplabs/knp-paginator-bundle 2.4](https://packagist.org/packages/knplabs/knp-paginator-bundle)
- [rhumsaa/uuid 2.8](https://packagist.org/packages/rhumsaa/uuid)

## Instalação e configuração:

### Adicione o MeroBaseBundle em seu composer.json
Adicione em seu arquivo composer.json o pacote *mero/base-bundle* em sua versão 1.1.* ou dev-master.
Exemplo:

```json
{
    "require": {
        "mero/base-bundle": "dev-master"
    }
}
```

### Adicione BaseBundle em seu AppKernel
Para o MeroBaseBundle ser reconhecido internamente pelo Symfony, é necessário adicionar sua chamada no arquivo
app/AppKernel.php. Exemplo:

```php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new Mero\BaseBundle\MeroBaseBundle(),
        // ...
    );
}
```

## Recursos

### StdEntity
- __construct()__, método construtor para gerar Data/Hora da ultima criação ou atualização do registro;
- __getId() / setId()__, métodos da primary key da entidade;
- __getCreated() / setCreated()__, métodos respectivos a data de criação do registro;
- __getUpdated() / setUpdated()__, métodos respectivos a data de ultima alteração do registro.

### StdController
- __getBundleName()__, retorna string com o nome do Bundle correspondente a controller;
- __createUuid1()__, cria um hash UUID na versão 1(baseado na hora);
- __createUuid3($nome, $ns)__, cria um hash UUID na versão 3(baseado no nome e criptografado em MD5);
- __createUuid4()__, cria um hash UUID na versão 4(aletarório);
- __createUuid5($nome, $ns)__, cria um hash UUID na versão 3(baseado no nome e criptografado em SHA1).

### StdCrudController


## Tutoriais

### Criando entidades com StdEntity
Extenda sua classe de StdEntity para criar uma entidade compatível com o MeroBaseBundle. Entidades compatíveis
já possuem automaticamente os campos id(representando a primary-key da tabela), created e updated. Exemplo:

```php
use Doctrine\ORM\Mapping as ORM;
use Mero\BaseBundle\Entity\StdEntity;

class Product extends StdEntity
{

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $price;

    /**
     * @ORM\Column(type="string")
     */
    protected $description;

}

```

Tabela relativa a entidade *Product* equivalerá aos campos id, name, price, description, created e updated. Não esqueça
de chamar o método construtor de StdEntity no correspondente a sua entidade caso exista. Exemplo:

```php

//...
public function __construct()
{
    parent::__construct();
}
//...

```

### Criando CRUD com StdCrudController
Extenda sua classe Controller de StdCrudController para criar uma controller simples do MeroBaseBundle. A classe
abstrata contem as actions indexAction(), addAction(), editAction(), removeAction(), detailsAction(). Exemplo:

```php

use Mero\BaseController\Controller\StdCrudController;

class ProductController extends StdCrudController
{
}

```
