#!/bin/bash
BIND=127.0.0.1:9000
USER=www-data
PHPRC=/etc/php5/cgi/
PHP_FCGI_MAX_REQUESTS=1000
export PHPRC
export PHP_FCGI_MAX_REQUESTS
exec /usr/bin/php5-cgi
