[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4612cf8e-4579-4ad5-a2ca-8e4620da09c8/mini.png)](https://insight.sensiolabs.com/projects/4612cf8e-4579-4ad5-a2ca-8e4620da09c8) [![Latest Stable Version](https://poser.pugx.org/mero/base-bundle/v/stable.svg)](https://packagist.org/packages/mero/base-bundle) [![Total Downloads](https://poser.pugx.org/mero/base-bundle/downloads.svg)](https://packagist.org/packages/mero/base-bundle) [![License](https://poser.pugx.org/mero/base-bundle/license.svg)](https://packagist.org/packages/mero/base-bundle)

## Requeriments

- PHP 5.3.3 or higher
- Symfony 2.3 or higher
- [ZnpPaginatorBundle 2.4](https://github.com/KnpLabs/KnpPaginatorBundle)

## Instalation with composer

1. Open your project directory;
2. Run `composer require mero/base-bundle` to add MeroBaseBundle in your project vendor;
3. Run `composer update command`;
4. Open **my/project/dir/app/AppKernel.php**;
5. Add dependence bundle `Knp\Bundle\PaginatorBundle\KnpPaginatorBundle()`;
6. Add `Mero\Bundle\BaseBundle\MeroBaseBundle()`.

## Usage

### Configuration KnpPaginatorBundle

1. Open **my/project/dir/app/config/config.yml**;
2. Add the configuration below.

```yaml
# KnpPaginator Configuration
knp_paginator:
    page_range: 10 #Number of records per page
    template:
        pagination: MeroBaseBundle:Bootstrap:pagination.html.twig
```

### Configuration MeroBaseBundle

1. Open **my/project/dir/app/config/config.yml**;
2. Add the configuration below.

```yaml
# MeroBase Configuration
mero_base:
    index_crud: false #Enable/Disable add form and edit form in indexAction 
```

### Doctrine ORM entities

| Name      | Type        | Description                                    | Address  |
| --------- | ----------- | ---------------------------------------------- | -------- |
| Id        | Trait       | Create the primary key field                   | [\Mero\Bundle\BaseBundle\Entity\Field\Id](https://github.com/merorafael/MeroBaseBundle/blob/master/Entity/Field/Id.php) |
| Created   | Trait       | Create field to store the creation date        | [\Mero\Bundle\BaseBundle\Entity\Field\Created](https://github.com/merorafael/MeroBaseBundle/blob/master/Entity/Field/Created.php) |
| Modified  | Trait       | Create field to store the date of last change  | [\Mero\Bundle\BaseBundle\Entity\Field\Modified](https://github.com/merorafael/MeroBaseBundle/blob/master/Entity/Field/Modified.php) |
| StdEntity | Super class | Entity superclass using the three basic traits | [\Mero\Bundle\BaseBundle\Entity\StdEntity](https://github.com/merorafael/MeroBaseBundle/blob/master/Entity/StdEntity.php) | 

### Symfony validators

| Applies to         | Options | Class | Validator |
| -------------------| ------- | ----- | --------- |
| [property or method](http://symfony.com/doc/current/book/validation.html#validation-property-target) | message | [BrazilianCNPJ](https://github.com/merorafael/MeroBaseBundle/blob/master/Validator/Constraints/BrazilianCNPJ.php) | [BrazilianCNPJValidator](https://github.com/merorafael/MeroBaseBundle/blob/master/Validator/Constraints/BrazilianCNPJValidator.php)  |
| [property or method](http://symfony.com/doc/current/book/validation.html#validation-property-target) | message | [BrazilianCPF](https://github.com/merorafael/MeroBaseBundle/blob/master/Validator/Constraints/BrazilianCPF.php)  | [BrazilianCPFValidator](https://github.com/merorafael/MeroBaseBundle/blob/master/Validator/Constraints/BrazilianCPFValidator.php)   |

### Validator exemple

```php
<?php

use Mero\Bundle\BaseBundle\Validator\Constraints as MeroAssert;

class People 
{
    
    /**
     * @MeroAssert\CPF()
     */
    private $cpf;

}
```
