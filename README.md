# Laravel Helpers

```php
use Pharaonic\Laravel\Helpers\...
```

## Middlewares

##### SimpleLocalization

<br><hr><br>

## Traits

##### HasCustomAttribtues (Getter, Setter)

##### HasUuidKey (id > UUID)

<br><hr><br>

## Classes

##### ExceptionHandler

##### FormRequest

<br><hr><br>

## Functions

```php
function json(bool $success, string $message = null, $data = null, $extra = [], array $errors = null, $status = 200, array $headers = null, $options = 0)
```

```php
function validate(Request $request, array $rules, string $defaultMessage = null, array $messages = null, array $fields = null, bool $redirectToRoute = false, string $redirectTo = null, array $redirectParams = null)
```
