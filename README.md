Wordpress Boilerplate 
===================

This is the repo for the Wordpress Boilerplate project. This project leverages Wordpress, Bedrock, Yarn and Sage. 

Setup
-------------

#### **Environment**

This project utilizes docker for all its local development. 

Before starting the setup make sure you have:
- [docker](https://www.docker.com/)
- [docker proxy](https://4mation.atlassian.net/wiki/display/PD/Docker+Proxy)
- [yarn](https://yarnpkg.com/en/) `npm install -g yarn`

#### **Instructions**

1. Run a search & replace for local.boilerplate.com -> local.your-domain. Rename web/certs/ to local.your-domain prefix.
2. Move your .env.example file to .env. Update database details
3. Run the build script `./env/deploy/deploy.sh`
4. Build the docker instance (Run this in **powershell**)
```bash
docker-compose build
```
5.  Run your docker container
```bash
docker-compose up -d
``` 
6. Copy the contents of the hooks folder to .git/hooks. This will automatically run all build tools when you do a pull.

7. Copy  web/.htaccess.sample to web/.htaccess
```bash
cp web/.htaccess.sample web/.htaccess
```
8. Setup your hosts file. `127.0.0.1       local.boilerplate.com`

#### **Theme**

The project uses the theme [Sage](https://roots.io/sage/). For all usage documentation you should check on the themes README.md file. 

Folder is `web/app/themes/sage/`

For the page to render you'll want to run the following commands in the theme folder.
```bash
yarn run build # Compile and optimize the files in your assets directory
yarn run start # Compiles when files change
``` 

Running the start command should begin browsersync. This should be used for all frontend development. The URL should be `http://local.boilerplate.com:3000/`

#### **Database**

Currently the project only connects castaway. Ideally in the future this would load in the live database into a separate docker container.

#### **Features**

- no password required for admin login when environment is development
- mailtrap automatically used when the values are set in .env file
- svg file fixes 

Development 
-------------

#### **Using WP-CLI**

If you setup your ssh credentials in the `wp-cli.yml` file you are able to alias your environments and perform commands on them! Below are a few handy commands.

**Database Migrations**
Copy live data to your local environment
`wp @live db export - | wp @local db import -`


#### **Gotchas**

The built sass files aren't using the minified images.

- This is because you need to use the correct syntax for it to properly be picked up by the build tools.
  You should explicitly reference the entire url and source like `url($img-dir + '/my-image.png')`. 
 
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

#### **Apache Requirements**

To utilise all the features in the .htaccess file you must have the following apache modules enabled:
- mod_rewrite
- mod_deflate
- mod_setenvif
- mod_filter
- mod_mime
- mod_headers
- mod_expires