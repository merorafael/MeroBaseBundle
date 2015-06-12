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
