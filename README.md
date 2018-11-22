# YouCompilations

YouCompilation will not allow you to skip videos on interesting topics. With this service you can get a collection of videos every day. Collections consist of tags. Tags displays the information you are interested in. If you forget about the collection, the notification system will send you a reminder.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Installing

1. Create in `/services/fpm/environments/` a `.env` from the `.env.example` file. Adapt it according to your symfony application

    ```bash
    cp .env.example .env
    ```

2. Build/run containers with (with and without detached mode)

    ```bash
    $ docker-compose build
    $ docker-compose up -d
    ```

3. Update your system host file (add symfony.local)

    ```bash
    # UNIX only: get containers IP address and update host (replace IP according to your configuration) (on Windows, edit C:\Windows\System32\drivers\etc\hosts)
    $ sudo echo $(docker network inspect bridge | grep Gateway | grep -o -E '[0-9\.]+') "youcompilations.local" >> /etc/hosts
    ```

    **Note:** For **OS X**, please take a look [here](https://docs.docker.com/docker-for-mac/networking/) and for **Windows** read [this](https://docs.docker.com/docker-for-windows/#/step-4-explore-the-application-and-run-examples) (4th step).

4. Enjoy :)

## How it works?

Have a look at the `docker-compose.yml` file, here are the `docker-compose` built images:

* `db`: This is the MariaDB database container,
* `server`: This is the PHP-FPM container in which the application volume is mounted,
* `nginx`: This is the Nginx webserver container in which application volume is mounted too,
* `queue`: This is the Queue container in which application volume is mounted too

This results in the following running containers:

```bash
$ docker-compose ps
           Name                          Command               State              Ports            
--------------------------------------------------------------------------------------------------
you-compilations_queue            "/bin/sh -c 'echo \"Q…"       Up                                       
you-compilations_nginx            "nginx -g 'daemon of…"        Up         80/tcp, 0.0.0.0:8000->8000/tcp
you-compilations_server           "/entry_point.sh php…"        Up         9000/tcp                      
mariadb:10.2.19                   "docker-entrypoint.s…"        Up         0.0.0.0:33061->3306/tcp       
```

The database has tables:

```
+----------------------------+
| Tables_in_you-compilations |
+----------------------------+
| compilation_logs           |
| compilations               |
| jobs                       |
| migrations                 |
| password_resets            |
| tags                       |
| user_tag                   |
| users                      |
| video_tag                  |
| videos                     |
+----------------------------+

```

## Deployment

Add additional notes about how to deploy this on a live system

## Built With

* [Laravel](https://laravel.com) - The web framework used
* [Composer](https://getcomposer.org) - Dependency Management
* [YouTube API](https://github.com/alaouy/Youtube) - Used to work with YouTube API

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
