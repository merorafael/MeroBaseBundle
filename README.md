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

### Doctrine ORM entities

- **Trait "Id":** Create the primary key field - **Class:** \Mero\Bundle\BaseBundle\Entity\Field\Id;
- **Trait "Created":** Create field to store the creation date - **Class:** \Mero\Bundle\BaseBundle\Entity\Field\Created;
- **Trait "Modified":** Create field to store the date of last change - **Class:** \Mero\Bundle\BaseBundle\Entity\Field\Modified;
- **Abstract "StdEntity":** Entity superclass using the three basic traits - **Class:** \Mero\Bundle\BaseBundle\Entity\StdEntity

## Symfony validators

| Applies to         | Options | Class | Validator |
| -------------------| ------- | ----- | --------- |
| [property or method](http://symfony.com/doc/current/book/validation.html#validation-property-target) | message | BrazilianCNPJ | BrazilianCNPJValidator  |
| [property or method](http://symfony.com/doc/current/book/validation.html#validation-property-target) | message | BrazilianCPF  | BrazilianCPFValidator   |

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
