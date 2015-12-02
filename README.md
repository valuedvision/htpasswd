# axy\htpasswd

Working with htpasswd file.

* GitHub: [axypro/htpasswd](https://github.com/axypro/htpasswd)
* Composer: [axy/htpasswd](https://packagist.org/packages/axy/htpasswd)

PHP 5.4+

The library does not require any dependencies (except composer packages).

The library provides program API for manipulation with htpasswd file.

```php
use axy\htpasswd\PasswordFile;

$file = new PasswordFile('/path/to/.htpasswd');
$file->setPassword('nick', 'password');
$file->setPassword('john', '123456');
$file->save();
```

Currently supported the following algorithms (constants of `PasswordFile::*`):

 * `ALG_MD5`: Apache APR1-MD5 algorithm (by default)
 * `ALG_SHA1`: SHA-1
 * `ALG_CRYPT`: crypt (unux)
 * `ALG_PLAIN`: Plain text (not supported of servers on some platforms).

Currently not supported:

 * BCrypt

##### `__construct([string $filename])`

The constructor takes the name of a htpasswd file.

Or `NULL`: analogue of the option `-n` of the console utility:

```php

$file = new PasswordFile();
$file->setPassword('nick', 'password');
$file->getContent(); // out of the "file" content

$file->save(); // Exception FileNotSpecified
```

##### `setPassword(string $user, string $password [, string $algorithm]): bool`

Sets the password `$password` for a user `$user`.
For hashing uses `$algorithm` (by default Apache MD5).

Returns `TRUE` a new user has been created.
And `FALSE` if has been changed the password of an existing user.

##### `remove(string $user): bool`

Removes a user from the file.
Returns `TRUE` is the user has been removed.
And `FALSE` if the user was not found.

##### `verify(string $user, string $password): bool`

Returns `TRUE` if a `$user` exists and has `$password` as the password.

##### `isUserExist(string $user): bool`

Returns `TRUE` is a user exists in the file.

```php
if (!$file->isUserExist('john')) {
    echo 'John? I do not known you.';
    exit();
}
if (!$file->verify('john', 'password')) {
    echo 'You not John! You an impostor!';
    exit();
}
echo 'Hello, John';
```

##### `getContent(void): string`

Returns the file content (without saving).

##### `save(void): void`

Saves the content to the file (if it is specified).

In contrast, from the utility `htpasswd` (see the option `-c`) the existing file always changing (not overwritten).
Nonexistent file is created.

##### `setFileName(string $filename): void`

Sets a new filename. The content of the old file will be loaded and saved to the new (after `save()`).

##### `getFileName(void): string`

Returns the current specified file name.

### Exceptions

In the `axy\htpasswd\errors` namespace.

* `InvalidFileFormat`: a password file has invalid format.
* `FileNotSpecified`: throws from `save()` if the file is not specified in the constructor.
