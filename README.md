MeroBaseBundle
=================

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4612cf8e-4579-4ad5-a2ca-8e4620da09c8/mini.png)](https://insight.sensiolabs.com/projects/4612cf8e-4579-4ad5-a2ca-8e4620da09c8)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/merorafael/MeroBaseBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/merorafael/MeroBaseBundle/?branch=master)
[![Build Status](https://travis-ci.org/merorafael/MeroBaseBundle.svg?branch=master)](https://travis-ci.org/merorafael/MeroBaseBundle)
[![Latest Stable Version](https://poser.pugx.org/mero/base-bundle/v/stable.svg)](https://packagist.org/packages/mero/base-bundle) 
[![Total Downloads](https://poser.pugx.org/mero/base-bundle/downloads.svg)](https://packagist.org/packages/mero/base-bundle) 
[![License](https://poser.pugx.org/mero/base-bundle/license.svg)](https://packagist.org/packages/mero/base-bundle)

Bundle with additional features for Symfony.

Requeriments
------------

- PHP 5.5.9 or above
- Symfony 2.7 or above(including Symfony 3)

Instalation with composer
-------------------------

1. Open your project directory;
2. Run `composer require mero/base-bundle` to add MeroBaseBundle in your project vendor;
3. Open **my/project/dir/app/AppKernel.php**;
4. Add `Mero\Bundle\BaseBundle\MeroBaseBundle()`.

AbstractController to Symfony Controllers
-----------------------------------------

Abstract controller with basic methods for easy identification framework resources.

| Name                         | Atributes                          | Description                           |
| ---------------------------- | ---------------------------------- | ------------------------------------- |
| apiResponse                  | $data, int $status, string $format | Return a new JSON or XML response     |
| getRouteName                 | -                                  | Gets the route name.                  |
| getActionName                | -                                  | Gets the action name.                 |
| getBundleName                | -                                  | Gets the bundle name.                 |
| createInvalidEntityException | $message, \Exception $previous     | Returns a InvalidEntityException.     |

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
        throw $this->createInvalidEntityException(); // Throw invalid entity exception
    }

}
```

Doctrine ORM entities
---------------------

| Name                  | Type           | Description                                         | Address  |
| --------------------- | -------------- | --------------------------------------------------- | -------- |
| IdTrait               | Trait          | Create the primary key integer field                | [\Mero\Bundle\BaseBundle\Entity\Field\IdTrait](https://github.com/merorafael/MeroBaseBundle/blob/master/Entity/Field/IdTrait.php) |
| UuidTrait             | Trait          | Create the primary key UUID field                   | [\Mero\Bundle\BaseBundle\Entity\Field\UuidTrait](https://github.com/merorafael/MeroBaseBundle/blob/master/Entity/Field/UuidTrait.php) |
| CreatedTrait          | Trait          | Create field to store the creation date             | [\Mero\Bundle\BaseBundle\Entity\Field\CreatedTrait](https://github.com/merorafael/MeroBaseBundle/blob/master/Entity/Field/CreatedTrait.php) |
| ModifiedTrait         | Trait          | Create field to store the date of last change       | [\Mero\Bundle\BaseBundle\Entity\Field\ModifiedTrait](https://github.com/merorafael/MeroBaseBundle/blob/master/Entity/Field/ModifiedTrait.php) |
| AbstractEntity        | Abstract Class | Entity superclass using UUID identifier             | [\Mero\Bundle\BaseBundle\Entity\AbstractEntity](https://github.com/merorafael/MeroBaseBundle/blob/master/Entity/AbstractEntity.php) | 
| AbstractEntityClassic | Abstract Class | Classic entity superclass using integer identifier  | [\Mero\Bundle\BaseBundle\Entity\AbstractEntityClassic](https://github.com/merorafael/MeroBaseBundle/blob/master/Entity/AbstractEntityClassic.php) | 

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
    // Entity class with UuidTrait, CreatedTrait and ModifiedTrait implemented
}
```

### Usage example with AbstractEntityClassic:
```php
namespace Acme\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mero\Bundle\BaseBundle\Entity\AbstractEntityClassic;

/**
 * @ORM\Entity()
 * @ORM\Table(name="post")
 */
class Post extends AbstractEntityClassic
{
    // Entity class with IdTrait, CreatedTrait and ModifiedTrait implemented
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

Symfony validators
------------------

This feature has been migrated to the package [mero/br-validator-bundle](https://packagist.org/packages/mero/br-validator-bundle)
because earlier versions contains only validators related to Brazilian locations.

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
