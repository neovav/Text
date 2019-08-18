Php class for text processing
=============================

This is sample how use neovav/Text class

<!-- [START getstarted] -->
## Getting Started

### Installation

For installations neovav/Text, run:

```bash
git clone https://github.com/neovav/Text
cd Text
composer install
```

### Usage

Trim multi byte string:

```php
use \neovav\Text\Text;

require_once '..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

echo Text::trim('  Hello World ');
```

Get only digits from text:

```php
use \neovav\Text\Text;

require_once '..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

echo Text::digits('  Digits 15 in text ');
```

Generate password:

```php
use \neovav\Text\Text;

require_once '..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

echo Text::genPass();
```

Translit from russian chars to latin chars:

```php
use \neovav\Text\Text;

require_once '..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

echo Text::genPass('Привет');
```

For more samples view in directory : samples