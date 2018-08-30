WordPress Keystone 
===================

Wordpress Keystone is a WordPress boilerplate designed for quickly building effective 
[twelve-factor applications](https://12factor.net/) 

Why WordPress Keystone?
- Development environment ready to go with docker
- Asset building with Laravel Mix
- Ease of use at the core design 
- All-in-one solution ready to build your next site 

It is built on top of many existing projects:
- [Laradock](https://github.com/laradock/laradock) - Docker
- [Sage](https://github.com/roots/bedrock) - Architecture and Webpack
- [Bedrock](https://github.com/roots/bedrock) - Architecture
- [Laravel components](https://github.com/mattstauffer/Torch)
- [Wordhat](https://github.com/paulgibbs/behat-wordpress-extension/)
- [WP Function Me](http://www.wpfunction.me/)
- [Composer Git Hooks](http://change-me/)
- [Laravel Mix](https://github.com/JeffreyWay/laravel-mix)

Setup
-------------

#### **Boilerplate Instructions**

Install: `composer create-project loonpwn/wp-keystone project-name`

First Run a search and replace for the following:
 - Your site url: `local.wp-keystone` -> local.your-domain
 - Your project title: `Wordpress Keystone` -> Your Project Name
 - Your project slug (docker): `wp-keystone` -> your-project-slug 
 
Then delete this section from the readme.

#### **Instructions**

Note: this project uses [docker](https://www.docker.com/) for all its local development.

_Start Proxy_
```
docker network create -d bridge proxy
docker run -d --name proxy -p 80:80 --network="proxy" -v /var/run/docker.sock:/tmp/docker.sock:ro jwilder/nginx-proxy
```

_Local Setup_
1. Copy over the env file `cp -n env/.env.local .env`
2. Copy over the htaccess `cp -n web/.htaccess.local web/.htaccess`
3. Setup docker containers `docker-compose up -d`
4. Setup your hosts file. `sudo sh -c 'echo "127.0.0.1       local.wp-keystone" >> /etc/hosts'`
5. Mount yourself to the workspace `./env/mount-workspace.sh`
6. Run the deployment script `composer build`


Development 
-------------

#### **Docker**

This project uses a docker container which will host our site for us and be able to build all of our assets for us. Some useful commands:
- `docker-compose restart` - Restart the container
- `docker exec -it project-slug bash` - Attach yourself to the container

#### **Migrations**

Copy live data to your local environment
`wp @live db export - | wp @local db import -`


Testing 
-------------

#### **Automated Testing**

Automated tested is setup using wordhat.

1. Run the server `composer selenium`
2. Run tests `composer test`

To see the test output run `composer vnc`. The password is `vnc123`
