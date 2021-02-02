# Laravel Helpers
 
## Traits
##### HasCustomAttribtues (Getter, Setter)
##### HasHashedPassword   (User Creating, Updating password)
##### HasUuidKey          (id > UUID)
-------
## Classes
##### ExceptionHandler
-------
## Functions
```php
function json(bool $success, string $message = null, $data = null, array $errors = null, $status = 200, array $headers = null, $options = 0)
```
```php
function validate(Request $request, array $rules, string $defaultMessage = null, array $messages = null, array $fields = null, bool $redirectToRoute = false, string $redirectTo = null, array $redirectParams = null)
```