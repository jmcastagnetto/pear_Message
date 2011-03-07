Message
=======

Package of utility functions to perform *Message Digest* operations:
hash calculation and HMAC signature generation for data authentication.

See RFC 2104 (and examples in RFC 2202) for more details on HMAC

The package is documented and usable.  See examples in the "misc" dir.

Input data, can be a string or a resource created using
fopen/fsocket/gzopen/bzopen/popen (or its stream equivalent).

If the mhash extension is detected then it will be used, otherwise
it will fall back to using md5() and sha1() only.

This is old code I wrote for [PEAR](http://pear.php.net). 
Originally it was tracked in CVS, then it was moved to SVN, 
and now I am importing it to git :-)

-- Jesus M. Castagnetto
