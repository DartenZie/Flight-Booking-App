#!/bin/bash
podman exec -it php-web bash -c "php /var/www/html/app/init/init.php"
