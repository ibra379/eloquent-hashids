# Eloquent Hashids

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dialloibrahima/eloquent-hashids.svg?style=flat-square)](https://packagist.org/packages/dialloibrahima/eloquent-hashids)
[![Total Downloads](https://img.shields.io/packagist/dt/dialloibrahima/eloquent-hashids.svg?style=flat-square)](https://packagist.org/packages/dialloibrahima/eloquent-hashids)
[![PHP Version](https://img.shields.io/packagist/php-v/dialloibrahima/eloquent-hashids.svg?style=flat-square)](https://packagist.org/packages/dialloibrahima/eloquent-hashids)
[![License](https://img.shields.io/packagist/l/dialloibrahima/eloquent-hashids.svg?style=flat-square)](https://packagist.org/packages/dialloibrahima/eloquent-hashids)


A Laravel package that automatically obfuscates Eloquent model IDs in URLs by converting them to reversible hash strings. This improves security and aesthetics by hiding sequential database IDs.

## Features

- ✅ **Zero dependencies** - Uses a custom Base62 encoder
- ✅ **Automatic route model binding** - Works seamlessly with Laravel routes
- ✅ **Reversible hashes** - Decode hashids back to original IDs
- ✅ **Per-model configuration** - Customize prefix, suffix, length per model
- ✅ **Laravel 10, 11, 12 support**

## Installation

```bash
composer require dialloibrahima/eloquent-hashids
```

Publish the config file:

```bash
php artisan vendor:publish --tag="eloquent-hashids-config"
```

## Usage

### Basic Usage

Add the `Hashidable` trait to your model:

```php
use DialloIbrahima\EloquentHashids\Hashidable;

class User extends Model
{
    use Hashidable;
}
```

Now you can use hashids in your URLs:

```php
// Get the hashid
$user->hashid; // "aBcD3FgH1jKlM4nP"

// Find by hashid
User::findByHashid('aBcD3FgH1jKlM4nP');
User::findByHashidOrFail('aBcD3FgH1jKlM4nP');

// Route model binding works automatically
Route::get('/users/{user}', function (User $user) {
    return $user;
});
// URL: /users/aBcD3FgH1jKlM4nP
```

### Per-Model Configuration

Implement `HashidableConfigInterface` to customize hashids per model:

```php
use DialloIbrahima\EloquentHashids\Contracts\HashidableConfigInterface;
use DialloIbrahima\EloquentHashids\Hashidable;

class Invoice extends Model implements HashidableConfigInterface
{
    use Hashidable;

    public function hashidableConfig(): array
    {
        return [
            'prefix' => 'inv',
            'suffix' => 'v1',
            'length' => 12,
            'separator' => '_',
        ];
    }
}

// Result: inv_aBcD3FgH_v1
```

### API Resources

Expose hashids in your API responses:

```php
class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->hashid,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
```

## Configuration

```php
// config/eloquent-hashids.php

return [
    'length' => 16,           // Minimum hashid length
    'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
    'salt' => env('HASHID_SALT', env('APP_KEY', '')),
    'prefix' => '',           // Optional prefix
    'suffix' => '',           // Optional suffix
    'separator' => '-',       // Separator for prefix/suffix
];
```

> ⚠️ **Warning**: Changing the `salt` will invalidate all existing hashids!

## Testing

```bash
composer test
```

## Security

Hashids provide **obfuscation, not encryption**. They:

- ✅ Hide sequential IDs
- ✅ Prevent URL enumeration
- ❌ Are NOT cryptographically secure
- ❌ Do NOT replace authentication/authorization

Always use proper authorization in your controllers:

```php
public function show(User $user)
{
    $this->authorize('view', $user);
    return $user;
}
```

## Credits

- [Ibrahima Diallo](https://github.com/ibra379)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
