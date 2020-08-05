# OpenApi Validator Bundle

Symfony Bundle for validating Request and Response based on [open api specification](https://swagger.io/specification/).

### Requirements

- Symfony > 5
- PHP > 7.4
- PHP Extension JSON

### Installation

```bash
composer require cydrickn/openapi-validator-bundle
```

#### Setting Up

Add the bundle in your `config/bundles.php`

```php
<?php

return [
    // ...
    Cydrickn\OpenApiValidatorBundle\CydricknOpenApiValidatorBundle::class => ['all' => true],
];
```

Add configuration `config/packages/cydrickn_openapi_validator.yml`
```yaml
cydrickn_openapi_validator:
    validate_request: true
    validate_response: true
    schema:
      factory: yaml-file
      file: %kernel.project_dir%/config/openapi/spec.yaml
```

#### Configurations

|Config           |Type   |Required|Accepted Value                |Default  |Description|
|-----------------|-------|--------|------------------------------|---------|-----------|
|validate_request |Boolean|Yes     |true and false                |true     |Enable validating of request|
|validate_response|Boolean|Yes     |true and false                |true     |Enable validating of response|
|schema.factory   |String |Yes     |yaml-file, json-file|yaml-file|yaml-file|Factory to use to generate the schema for validation|
|schema.file      |String |Yes     |                              |         |Open api specification file path|

### TODO

- [ ] Add Nelmio Api Schema Factory
- [x] Add Dynamic Configuration
- [x] Add document
- [ ] Code coverage of 100%
- [ ] Add CI
- [ ] Support Lower PHP Version >= 7.1
- [ ] Support Lower Symfony Version >= 3
