# OpenApi Validator Bundle

Symfony Bundle for validating Request and Response based on [open api specification](https://swagger.io/specification/) 3.

### Requirements

- Symfony >= 5
- PHP >= 7.4
- PHP Extension JSON

#### Optional

- nelmio/api-doc-bundle >= 4.0 (Currently in Beta)
    - Why 4.0, version 3 and below only supports version 2 of OpenAPI Specification.


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
cydrickn_open_api_validator:
    validate_request: true
    validate_response: true
    schema:
      factory: yaml-file
      file: %kernel.project_dir%/config/openapi/spec.yaml
    condition:
      query:
        name: 'query-parameter-name'
        value: 'query-parameter-value'
```

#### Configurations

|Config           |Type   |Required|Accepted Value                |Default  |Description|
|-----------------|-------|--------|------------------------------|---------|-----------|
|validate_request |Boolean|Yes     |true or false                 |true     |Enable validating of request|
|validate_response|Boolean|Yes     |true or false                 |true     |Enable validating of response|
|schema.factory   |String |Yes     |yaml-file, json-file or nelmio|yaml-file|Factory to use to generate the schema for validation|
|schema.file      |String |Required only for yaml-file and json-file|||File path of the specification|
|condition        |Array  |No      |query                         |     |Dynamically enable/disable the validator. Validation will occur only if condition passes.
|condition.query.name|String|Required if query is set as condition|Name of the query parameter| |Checks if this query parameter exists before running validation.
|condition.query.value|String|No|Value of the query parameter| |Checks if the query parameter has this exact value before running validation.

### TODO

- [x] Add Nelmio Api Schema Factory
- [x] Add Dynamic Configuration
- [x] Add document
- [ ] Add Route Schema Factory
- [ ] Add PHP file Schema Factory
- [ ] Code coverage of 100%
- [ ] Add CI
- [ ] Support Lower PHP Version >= 7.1
- [ ] Support Lower Symfony Version >= 3
