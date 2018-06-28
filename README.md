WordPress Keystone 
===================

Wordpress Keystone is a WordPress boilerplate designed for quickly building effective 
[twelve-factor applications](https://12factor.net/) 

It is built on top of many existing projects:
- [Laradock](https://github.com/laradock/laradock) - Docker
- [Sage](https://github.com/roots/bedrock) - Architecture and Webpack
- [Bedrock](https://github.com/roots/bedrock) - Architecture
- [Laravel components](https://github.com/mattstauffer/Torch)
- [Wordhat](https://github.com/paulgibbs/behat-wordpress-extension/)
- [WP Function Me](http://www.wpfunction.me/)
- [Composer Git Hooks](http://change-me/)

Setup
-------------

#### **Environment**

This project utilizes [docker](https://www.docker.com/) for all its local development, before starting the setup make sure you have it installed.

#### **Boilerplate Instructions**

First Run a search & replace for the following:
 - Your site url: `local.wp-keystone` -> local.your-domain
 - Your project title: `Wordpress Keystone` -> Your Project Name
 - Your project slug (docker): `wp-keystone` -> your-project-slug 
 
Then delete this section from the readme.

#### **Instructions**
Local Setup
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

#### **Plugins**

All 3rd party plugins are ideally included via composer within the `composer.json` file. For finding plugins check out the [wpackagist](https://wpackagist.org/). 
If you are working on a custom plugin checkout the plugin boilerplate that's available [here](https://bitbucket.org/harlan_wilton/plugin-boilerplate/overview).

#### **WP-CLI**

If you setup your ssh credentials in the `wp-cli.yml` file you are able to alias your environments and perform commands on them! Below are a few handy commands.

#### **Migrations**

Copy live data to your local environment
`wp @live db export - | wp @local db import -`


Testing 
-------------

#### **Automated Testing**

Automated tested is setup using behat. To get it working properly you need to run a selenium server which 
has been supplied in the form of a composer package. For this to work you'll need to copy over
the drivers. This has to be done outside of docker at the moment. 

1. Run the server `composer selenium`
2. Run tests `composer test`
