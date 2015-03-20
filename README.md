[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4612cf8e-4579-4ad5-a2ca-8e4620da09c8/mini.png)](https://insight.sensiolabs.com/projects/4612cf8e-4579-4ad5-a2ca-8e4620da09c8) [![Latest Stable Version](https://poser.pugx.org/mero/base-bundle/v/stable.svg)](https://packagist.org/packages/mero/base-bundle) [![Total Downloads](https://poser.pugx.org/mero/base-bundle/downloads.svg)](https://packagist.org/packages/mero/base-bundle) [![License](https://poser.pugx.org/mero/base-bundle/license.svg)](https://packagist.org/packages/mero/base-bundle)

Bundle para desenvolvimento ágil no Symfony 2. Recursos:

- StdController(Controller com métodos adicionais)
- StdCrudController(Controller abstrata para CRUD simples)
- StdEntity(Entidade abstrata contendo campos comuns)
- Layout base com jQuery 1.11.2, Bootstrap 3.3.2, Font-Awesome 4.3.0 embarcados.

## Requisitos mínimos:
- PHP 5.3.3
- Symfony 2.3
- Doctrine ORM 2.4
- [KnpPaginatorBundle 2.4](https://github.com/KnpLabs/KnpPaginatorBundle)

## Instalação e configuração:

### Adicione o MeroBaseBundle em seu composer.json

Adicione em seu arquivo composer.json o pacote *mero/base-bundle* em sua versão 1.0.0 ou dev-master.
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

## Recursos básicos

### Criando entidades

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

Tabela relativa a entidade *Product* equivalerá aos campos id, name, price, description, created e updated.

### Criando CRUD

Extenda sua classe Controller de StdCrudController para criar uma controller simples do MeroBaseBundle. A classe
abstrata contem as actions indexAction(), addAction(), editAction(), removeAction(), detailsAction(). Exemplo:

```php

use Mero\BaseController\Controller\StdCrudController;

class ProductController extends StdCrudController
{
}

```

Métodos que poderão ser sobreescritos para tornar sua controller mais ágil ou compativel com o StdCrudController:

- *getEntityNamespace()*, retorna string com o namespace da entidade principal pertencente ao CRUD;
- *getEntityName()*, retorna string com o nome da classe da entidade;
- *getFormType()*, retorna o objeto do tipo do formulário relacionado ao CRUD;
- *getBundleName()*, retorna string com o nome do Bundle;
- *getViewName()*, retorna string com o nome do arquivo twig;
- *getActionRoute($action)*,  retorna a rota para a action informada;
- *getEm()*, retorna EntityManager do Doctrine a ser utilizado no CRUD;
- *indexQueryBuilder()*, método a ser sobrescrito caso deva adicionar codições ao Query builder;
- *newEntity()*, método a ser sobrescrito caso deva adicionar parametros no momento em que uma nova entidade é instanciada;
- *dataManagerAdd()*, método a ser sobrescrito caso deva adicionar parametros no momento em que um registro é adicionado;
- *dataManagerEdit()*, método a ser sobrescrito caso deva adicionar parametros no momento em que um registro é editado.
