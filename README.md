# Urrtube (working title)
Work very much in progress.
### Setup
```shell
cp .env.example .env
# Install php dependencies
cd common && composer install && cd ..
# Install node dependencies
cd services/front && npm install && cd ..
# Start everything up
docker-compose up
# ... and the application should be running on https://localhost 
```  

### Disclaimer
Testing a monorepo configuration that is not a pain to work with.  