# Installing
* Install [Docker](https://www.docker.com/products/docker-desktop)
* Point magento.test to localhost in your hosts file
* Clone repository
* Run following commands
```
$ docker-compose up -d
$ docker exec -it web install-magento
$ docker exec -it web install-sampledata
```
