MeroBaseBundle
=================

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4612cf8e-4579-4ad5-a2ca-8e4620da09c8/mini.png)](https://insight.sensiolabs.com/projects/4612cf8e-4579-4ad5-a2ca-8e4620da09c8)
[![Build Status](https://travis-ci.org/merorafael/MeroBaseBundle.svg?branch=master)](https://travis-ci.org/merorafael/MeroBaseBundle)
[![Latest Stable Version](https://poser.pugx.org/mero/base-bundle/v/stable.svg)](https://packagist.org/packages/mero/base-bundle) 
[![Total Downloads](https://poser.pugx.org/mero/base-bundle/downloads.svg)](https://packagist.org/packages/mero/base-bundle) 
[![License](https://poser.pugx.org/mero/base-bundle/license.svg)](https://packagist.org/packages/mero/base-bundle)

Symfony Bundle with additional features.

Requeriments
------------

- PHP 5.4.9 or above
- Symfony 2.7 or above(including Symfony 3)

Instalation with composer
-------------------------

1. Open your project directory;
2. Run `composer require mero/base-bundle` to add MeroBaseBundle in your project vendor;
3. Open **my/project/dir/app/AppKernel.php**;
4. Add `Mero\Bundle\BaseBundle\MeroBaseBundle()`.

Symfony validators
------------------

| Applies to         | Options | Class | Validator | Description |
| -------------------| ------- | ----- | --------- | ----------- |
| [property or method](http://symfony.com/doc/current/book/validation.html#validation-property-target) | message | [DateRange](https://github.com/merorafael/MeroBaseBundle/blob/master/Validator/Constraints/DateRange.php)   | [DateRangeValidator](https://github.com/merorafael/MeroBaseBundle/blob/master/Validator/Constraints/DateRangeValidator.php)    | Validates date range  |

### Basic usage

```php
<?php

use Mero\Bundle\BaseBundle\Validator\Constraints as MeroAssert;

class Payment
{
    /**
     * @var \DateTime Payment date
     *
     * @MeroAssert\DateRange(min="2017-01-01", max="today")
     */
    private $date;
}
```

Brazilian validators in the new version
---------------------------------------

The brazilian validators feature has been migrated to the package [mero/br-validator-bundle](https://packagist.org/packages/mero/br-validator-bundle).

AbstractController to Symfony Controllers
-----------------------------------------

Abstract controller with basic methods for easy identification framework resources.

| Name                         | Atributes                                          | Return type | Description                           |
| ---------------------------- | -------------------------------------------------- | ----------- | ------------------------------------- |
| getCurrentRequest            | -                                                  | Request     | Gets the cuurrent request             |
| getActionName                | -                                                  | string      | Gets the action name                  |
| getBundleName                | Request $request                                   | string      | Gets the bundle name                  |
| getRouteName                 | Request $request                                   | string      | Gets the route name                   |
| wsResponse                   | $data, int $status, array $headers, string $format | Response    | Return a new JSON or XML response     |

### Usage example:
```php
namespace Acme\Bundle\BlogBundle\Controller;

use Mero\Bundle\BaseBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class NewsController extends AbstractController
{

    /**
     * @Route("/", name="news")
     */
    public function indexAction(Request $request)
    {
        $routeName = $this->getRouteName($request); // Return "news"
        $actionName = $this->getActionName($request); // Return "indexAction"
        $bundleName = $this->getBundleName(); // Return "AcmeBlogBundle"
        $data = [
            'data' => [
                'news' => [
                    [
                        'title' => 'Lorem ipsum'
                    ]
                ]
            ]
        ];
        
        //to return JSON
        return $this->wsResponse($data, 200, [], AbstractController::WS_RESPONSE_JSON);
        
        //to return XML
        return $this->wsResponse($data, 200, [], AbstractController::WS_RESPONSE_XML);
    }

}
```

Doctrine ORM entities
---------------------

| Name                   | Type           | Description                                         | Address                                                                                                                                          |
| ---------------------- | -------------- | --------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------ |
| IdTrait                | Trait          | Create the primary key integer field                | [Mero\Bundle\BaseBundle\Entity\Field\IdTrait](https://github.com/merorafael/MeroBaseBundle/blob/master/Entity/Field/IdTrait.php)                 |
| UuidTrait              | Trait          | Create the primary key UUID field                   | [Mero\Bundle\BaseBundle\Entity\Field\UuidTrait](https://github.com/merorafael/MeroBaseBundle/blob/master/Entity/Field/UuidTrait.php)             |
| CreatedTrait           | Trait          | Create field to store the creation date             | [Mero\Bundle\BaseBundle\Entity\Field\CreatedTrait](https://github.com/merorafael/MeroBaseBundle/blob/master/Entity/Field/CreatedTrait.php)       |
| ModifiedTrait          | Trait          | Create field to store the date of last change       | [Mero\Bundle\BaseBundle\Entity\Field\ModifiedTrait](https://github.com/merorafael/MeroBaseBundle/blob/master/Entity/Field/ModifiedTrait.php)     | 
| AbstractEntity         | Abstract Class | Classic entity superclass using integer identifier  | [Mero\Bundle\BaseBundle\Entity\AbstractEntity](https://github.com/merorafael/MeroBaseBundle/blob/master/Entity/AbstractEntity.php) |
| AbstractEntityWithUuid | Abstract Class | Entity superclass using UUID identifier             | [Mero\Bundle\BaseBundle\Entity\AbstractEntityWithUuid](https://github.com/merorafael/MeroBaseBundle/blob/master/Entity/AbstractEntityWithUuid.php)               | 

### Usage example with AbstractEntity:
```php
namespace Acme\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mero\Bundle\BaseBundle\Entity\AbstractEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="post")
 */
class Post extends AbstractEntity
{
    // Entity class with IdTrait, CreatedTrait and ModifiedTrait implemented
}
```

### Usage example with AbstractEntityWithUuid:
```php
namespace Acme\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mero\Bundle\BaseBundle\Entity\AbstractEntityWithUuid;

/**
 * @ORM\Entity()
 * @ORM\Table(name="post")
 */
class Post extends AbstractEntityWithUuid
{
    // Entity class with UuidTrait, CreatedTrait and ModifiedTrait implemented
}
```

### Usage example with Traits:
```php
namespace Acme\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mero\Bundle\BaseBundle\Entity\IdTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="post")
 */
class Post
{
    use IdTrait;
}
```

Twig extensions
---------------

### Country

Gets name of the country based on the ISO 3166-1 alpha 2.
Example: entity.getCountry() is a value generated by the type of form [country](http://symfony.com/doc/current/reference/forms/types/country.html).

```twig
{# Returns the country name in the server language #}
{{ entity.getCountry()|country() }}

{# Returns the country name in english #}
{{ entity.getCountry()|country('en_US') }}

{# Returns the country name in portuguese #}
{{ entity.getCountry()|country('pt_BR') }}
```

### Language

Gets name of the language based on the unicode language identifier.
Example: entity.getLanguage() is a value generated by the type of form [language](http://symfony.com/doc/current/reference/forms/types/language.html).

```twig
{# Returns the language name in the server language #}
{{ entity.getLanguage()|language() }}

{# Returns the language name in english #}
{{ entity.getLanguage()|language('en_US') }}

{# Returns the language name in portuguese #}
{{ entity.getLanguage()|language('pt_BR') }}
```

Helpers
-------

### className

Gets name of the class. This helper was added to facilitate applications using PHP 5.4.
Use of this feature is discouraged if you are using PHP 5.5 or above due to the native implementation added in 
the language.

```php
namespace Acme\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mero\Bundle\BaseBundle\Helper\ClassNameTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="post")
 */
class Post
{
    use ClassNameTrait;
}

Post::className(); // Return "Post" using ClassNameTrait for PHP 5.4
Post::class // Return "Post" if you are using PHP 5.5 or above
```
