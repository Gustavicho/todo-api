FROM nginx:stable-alpine

COPY default.conf /etc/nginx/conf.d/default.conf

LABEL desc="web-server"

VOLUME [ "/var/www/symfony_todo" ]

EXPOSE 80