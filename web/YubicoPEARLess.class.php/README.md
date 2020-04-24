YubicoPEARLess.class.php
========================

Edited Yubico's PHP class to remove PEAR and some normalization to be easier to read  
Based on the original code by Yubico at https://github.com/Yubico/php-yubico/

## What's different
Instead of using PEAR, just check if $yubi->verify() return false,  
and if you want the precise error, use $yubi->getLastError();

## Example of use:
```php
require_once(YubikeyPEARLess.class.php);
$otp = "ccbbddeertkrctjkkcglfndnlihhnvekchkcctif";
// Generate a new id+key from https://api.yubico.com/get-api-key/
$yubi = new Auth_Yubico('42', 'FOOBAR=');
if (!$yubi->verify($otp)))
	print "Authentication failed: ".$yubi->getLastError();
else
	print "You are authenticated!";
```
