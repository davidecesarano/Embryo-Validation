# Embryo Validation
PHP validation for PSR-7 request.

## Features
* PSR compatible.
* Used for `$request->getParsedBody` and `$request->getUploadedFiles`.
* Sanitize values.

## Requirements
* PHP >= 7.1
* A [PSR-7](https://www.php-fig.org/psr/psr-7/) http message implementation and [PSR-17](https://www.php-fig.org/psr/psr-17/) http factory implementation (ex. [Embryo-Http](https://github.com/davidecesarano/Embryo-Http))

## Installation
Using Composer:
```
$ composer require davidecesarano/embryo-validation
```

## Example
You may quickly test this using the built-in PHP server going to http://localhost:8000.
```
$ cd example
$ php -S localhost:8000
```

## Usage
### Writing simple validation logic
Let's assume we have `POST` request. With PSR we have the `$_POST` parameters in `$request->getParsedBody()` and the `$_FILES` files in `$request->getUploadedFiles`. Use Embryo Validation for to validate parameters values.

```php
$request = (new Embryo\Http\Factory\ServerRequestFactory)->createServerRequestFromServer();
$validation = new Embryo\Validation\Validation($request);

$validation->name('title')->type('text')->required();
$validation->name('body')->type('any')->required();
if ($validation->isSuccess()) {
    // ...
} else {
    print_r($validation->getErrors());
}
```
If the validation rules pass, your code will keep executing normally; however, if validation fails, you can display errors.

### Methods

#### `name(string $name)`
Set field name. If name not exists in `$request->getParsedBody()` or in `$request->getUploadedFiles()` return an error. 

#### `type(string $type)`
Set field type. If value not match at type return an error. The types are:
* **text**. Value may be anything, it will be sanitized with `FILTER_SANITIZE_STRING`.  
* **email**. Value must be an email.
* **file**. Value must be an array of `UploadedFileInterface` objects or an `UploadedFileInterface` object.
* **array**. Value must be an array. 
* **datetime**. Value must be a `DateTime` format.
* **number**. Value must be a generic number.
* **int**. Value must be an integer.
* **float**. Value must be a float.
* **url**. Value must be a url.
* **boolean**. Value must be a boolean.
* **any**. Value may be anything, without sanitization (this is useful for html code).

#### `required()`
Set field require. The value must not be empty.

#### `pattern(string $type)`
Set field pattern. The value must match regular expression.

#### `equal($value)`
Set field value. The value must be the same.

#### `match(... $value)`
Set field matches. The value must match at one of the values.

#### `length(int $min, int $max)`
Set field length. The value may not be less than `$min` and greater than `$max`.

#### `maxSize(int $size)`
Set field file max size. The size of the file must not be greater than `$size` in bytes.

#### `accept(... $ext)`
Set allowed extensions for file field. The extension must match at one of the values.

#### `result()`
Return validation result. This method return an array like so:
```php
    return [
        'status' => 200,
        'data' => [
            'title' => 'Hello World!',
            'body' => 'This is a post...',
            'name' => ''
        ],
        'errors' => [
            'name' => [
                'The %s field is required.'
            ]
        ],
        'errorList' => [
            'The %s field is required.'
        ]
    ];
```
If validation fails, status is `400`, otherwise is `200`.

#### `isSuccess()`
Return `true` if validation pass, otherwise return `false`.

#### `getErrors()`
Return errors multidimensional array where key is field name and value is an errors array.

#### `getErrorList()`
Return errors array.

#### `getData()`
Return sanitized data array. If the value is an instance of `UploadedFileInterface`, you must use, for example, `$file->getClientFilename()` for returning file name.