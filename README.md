## Stubborn

[![Build Status](https://travis-ci.org/gsdevme/Stubborn.svg?branch=master)](https://travis-ci.org/gsdevme/Stubborn)

#### Idea taken from
http://www.reddit.com/r/PHP/comments/2mqyxw/a_little_library_i_wrote_for_dealing_with/

### Concept
For each API vendor (e.g. Facebook, Twitter, Dropbox) have the requests implement StubbornAwareInterface and throw the class into Stubborn, You can implement the methods to handle the API oddities

### Something example
```php
$like = new Something\Like('blagbla83eq33b3r', 235);

$stubborn = new \Stubborn\Stubborn($like);
$result = $stubborn->run();
```
