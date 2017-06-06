/** import external dependencies */
import 'jquery';
import 'bootstrap-sass';

/** import local dependencies */
import Components from './util/Components';
import Router from './util/Router';
import common from './routes/common';
import home from './routes/home';
import aboutUs from './routes/about';

/** import components */
// e.g. import header from  './components/header.js';

/**
 * Our extra components
 * @type {Components}
 */
const components = new Components([
    // e.g. header,
]);

/**
 * Populate Router instance with DOM routes
 * @type {Router} routes - An instance of our router
 */
const routes = new Router({
  /** All pages */
  common,
  /** Home page */
  home,
  /** About Us page, note the change from about-us to aboutUs. */
  aboutUs,
});

/** Load Events */
jQuery(document).ready(($) => {
    routes.loadEvents();
    components.fire('ready', $);
});

jQuery(window).on('load', ($) => components.fire('loaded', $));
