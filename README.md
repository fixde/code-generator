# Code Generator for Laravel
Using this package to generate controller, migration, model, route, repository, request, resource for your Laravel application

## Installation
```composer require fixde/code-generator --dev```

## Usage
* Run `php artisan generate:all {YOUR_MODEL_NAME} --field={FIELD_NAME}:{FIELD_TYPE}`
* Example: `php artisan generate:all product --field=name:string`
* Available field types: **smallint**, **bigint**, **datetimetz**, **blob**, **integer**, **boolean**, **date**, **time**, **datetime**, **text**, **decimal**, **float**, **object**, **array**, **simple_array**, **json_array**, **guid**
* You can custom your own templates by running `php artisan vendor:publish --tag=code-generator`
* You can also use our `BaseRepository` by implementing `Fixde\CodeGenerator\Repositories\BaseRepository` class or create your own