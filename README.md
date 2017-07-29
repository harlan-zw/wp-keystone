Wordpress Boilerplate 
===================

This is the repo for the Wordpress Boilerplate project. This project boilerplate was built using bedrock as a guideline. 
It comes packaged with numerous handy plugins to make life easier. 

Setup
-------------

#### **Environment**

This project utilizes docker for all its local development. 

Before starting the setup make sure you have:
- [docker](https://www.docker.com/)
- [docker proxy](https://4mation.atlassian.net/wiki/display/PD/Docker+Proxy)
- [yarn](https://yarnpkg.com/en/) `npm install -g yarn`

#### **Instructions**

1. Run a search & replace for local.boilerplate.com -> local.your-domain
2. Move your .env.example file to .env. The existing database credentials are for Tardis so you may want to create a database here.
3. Run the build script `./env/deploy/deploy.sh`
4. Build the docker instance (Run this in **powershell**) `docker-compose build`
5. Run your docker container `docker-compose up -d`
6. Copy the contents of the hooks folder to .git/hooks. This will automatically run all build tools when you do a pull.
7. Copy  web/.htaccess.sample to web/.htaccess `cp web/.htaccess.sample web/.htaccess`
8. Setup your hosts file. `127.0.0.1       local.boilerplate.com`
9. Update this read me. Remove step 1 and tailor to your project. 
10. Add a theme. We recommend using [Sage](https://roots.io/sage/)

#### **Packaged Plugins**

- Easy Development Environment - no password required for admin login when environment is development
- Mailtrap - mailtrap automatically used when the values are set in .env file
- Whoops Errors - whoops error handling for staging & development
- Bottom Admin Bar - admin bar at bottom of screen
- Fix WP - Fixes common wordpress issues 

Development 
-------------

#### **Using WP-CLI**

If you setup your ssh credentials in the `wp-cli.yml` file you are able to alias your environments and perform commands on them! Below are a few handy commands.

### Database Migrations ###
Copy live data to your local environment
`wp @live db export - | wp @local db import -`


#### **Ideals**

 - All vendor plugins should be included via composer. For finding plugins check out the [wpackagist](https://wpackagist.org/)
 - All custom plugins should be developed using the plugin boilerplate [here](https://bitbucket.org/harlan_wilton/plugin-boilerplate/overview) 
 - Themes and plugins should be tested using the [wptest](https://github.com/poststatus/wptest) suite

#### **Using Test Data**

Test data should be used for all development of themes and plugins.
Run the following command
```bash
bash vendor/manovotny/wptest/wptest-cli-install.sh
```

Deployment - ElasticBeanstalk
-------------

This boilerplate comes equipped with boilerplate configuration files for deployment to ElasticBeanstalk. It leverages 
BitBucket Pipelines to build the application, package it, upload it to an S3 bucket and then deploy it to ElasticBeanstalk.

By default the `bitbucket-pipelines.yml` file lints and builds the application for testing purposes.
Replace it with the `bitbucket-pipelines.yml.elastic-beanstalk.sample` file to deploy to ElasticBeanstalk. As specified
within the file, you need to set the BitBucket Pipelines environment variables for the deployment environment to 
successfully deploy to your ElasticBeanstalk setup.

There are 2 .env files for use in production and staging ElasticBeanstalk environments
 - `.env.elastic-beanstalk.production`
 - `.env.elastic-beanstalk.staging`

The BitBucket Pipelines file will utilise the production .env file master pull requests and the staging .env file
for release pull requests.
