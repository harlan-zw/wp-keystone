WordPress Keystone 
===================

Wordpress Keystone is a WordPress boilerplate designed for quickly building effective 
[twelve-factor applications](https://12factor.net/) 

#### **Why WordPress Keystone?**
- Development environment ready to go with docker
- Asset building with Laravel Mix
- Component / action design at the core
- Sane assumptions about how you want to use it

WordPress Keystone is the combination of many libraries, taking inspiration and straight
code from them. If you are confused on how some of the parts of this project work,
checkout the following projects:
- [Laradock](https://github.com/laradock/laradock) - Docker
- [Sage](https://github.com/roots/sage) - Architecture
- [Bedrock](https://github.com/roots/bedrock) - Architecture
- [Laravel components](https://github.com/mattstauffer/Torch) - Laravel packages
- [WP Function Me](http://www.wpfunction.me/) - Random snippets
- [Composer Git Hooks](http://change-me/) - Workflow triggers
- [Laravel Mix](https://github.com/JeffreyWay/laravel-mix) - Asset Builds

#### **Project Structure**

`app` - Project functionality 

`app/components` - This is the bread and butter of the boilerplate. All your code you would usually stick
in `functions.php` lives here. WordPress Keystone wil automatically parse folders within the `components` folder 
and have the files contained loaded under their wordpress filter or hook. 
This forces developers to think about when there code is being executed within the WordPress runtime. 

`config` - All application configuration is found here including constant definition and bootstrapping

`env` - Environment based files. Mainly docker configuration and tools are found within here.  

`resources` - Most importantly, your view files are found here as well as styles and scripts.

`runtime` - Any application runtime files live here, besides uploads.   

`web` - This is your web root. Avoid putting anything in here if you can.


Setup
-------------

#### **Boilerplate Instructions**

Install: `composer create-project loonpwn/wp-keystone project-name`

First Run a search and replace for the following:
 - Your site url: `local.wp-keystone` -> local.your-domain
 - Your project title: `Wordpress Keystone` -> Your Project Name
 - Your project slug: `wp-keystone` -> your-project-slug 
 
Then delete this section from the readme.

#### **Instructions**

Note: this project uses [docker](https://www.docker.com/) for all its local development.

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

This project uses a docker container which will host our site for us and be able to build all of our assets for us.
Some useful commands:
- `docker-compose restart` - Restart the container
- `./env/mount-workspace.sh` - Attach yourself to the workspace container
