FROM docker.ops.colourlife.com:5000/nginx-php54
COPY . /var/www/html
COPY ./devops/config/default.conf /etc/nginx/nginx.conf
COPY ./devops/config/vhost.conf /etc/nginx/conf.d/vhost.conf
COPY ./devops/config/mime.types /etc/nginx/mime.types
WORKDIR /var/www/html

