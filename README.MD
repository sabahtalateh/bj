## Настройка базы
#### Докер с MySQL
```
docker run --name bj -e MYSQL_ROOT_PASSWORD=3e5217fa79804f2185c33c42bf5e5168 -p 33306:3306 -d mysql:8.0.19
docker exec -it bj bash
mysql -u root -p
# Для запуска контейнера после перезагрузки 
docker start bj
```

#### Пользователь и база
```
CREATE DATABASE bj;
CREATE USER 'bj'@'%' IDENTIFIED BY '4195a0860b7a4782bfa9b842cae77d4d';
GRANT ALL PRIVILEGES ON bj.* TO 'bj'@'%';
```

#### Миграции
```bash
./vendor/bin/doctrine-migrations migrate --no-interaction
```
