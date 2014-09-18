## Requisitos:
- Symfony 2.3 ou superior
- [KnpPaginatorBundle](https://github.com/KnpLabs/KnpPaginatorBundle)

## Instalação e configuração:

Você poderá adicionar facilmente em seu arquivo composer.json

```json
{
    "require": {
        "mero/base-bundle": "dev-master"
    }
}
```

### Adicione BaseBundle em seu AppKernel

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
