FROM charlesstover/docker-php-gd
LABEL Author "Charles Stover <docker@charlesstover.com>"

COPY src/ /var/www/html/
EXPOSE 80
