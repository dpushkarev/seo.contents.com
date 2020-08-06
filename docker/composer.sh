#!/usr/bin/env bash

call="cd /app/application && /usr/local/bin/composer"

eval "docker exec -it seo_contents_apache bash -c \"$call $*\""
eval "docker exec -it seo_contents_apache bash -c \"chmod -R a+w /app/application/vendor/\""
