# Installing
1. Install [Docker](https://www.docker.com/products/docker-desktop)
2. Point magento.test to localhost in your hosts file
3. Clone repository
4. Settings can be found on .env-file
5. Run following commands
```
$ docker-compose up -d
$ docker exec -it web install-magento
$ docker exec -it web install-sampledata
```
# Usage
## Magento site
```
http://magento.test
```
## Add product page
```
http://magento.test:8080
```