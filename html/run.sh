#!/usr/bin/env bash
set -e

trap "echo 'sig term exit';killall php; sleep 2; ps; exit" INT TERM
trap "echo 'really exiting';sleep 1; ps; exit" EXIT

echo "Start web server"
php -S 0.0.0.0:80 -t ./pub/ ./phpserver/router.php & wait

exec "$@"
