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
- [docker proxy](https://4mation.atlassian.net/wiki/display/PD/Docker+Proxy) `docker run -d --name proxy -p 80:80 -v /var/run/docker.sock:/tmp/docker.sock:ro jwilder/nginx-proxy `
- [yarn](https://yarnpkg.com/en/) `npm install -g yarn`

#### **Instructions**

1. Run a search & replace for local.boilerplate.com -> local.your-domain - delete this line after doing

Local Setup
1. Run our local setup script `./env/docker/setup-local.sh`
2. Setup your hosts file. `127.0.0.1       local.boilerplate.com`


#### **Automated Testing**

Automated tested is setup using behat. To get it working properly you need to run a selenium server which 
has been supplied in the form of a composer package. For this to work you'll need to copy over
the drivers. This has to be done outside of docker at the moment. 

1. Run the server `composer selenium`
2. Run tests `composer test`

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
