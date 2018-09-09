/**
 * Anything here will be included within the backend
 */


// import local dependencies
import Components from './util/components';

const components = new Components([
]);


components.fire('init', $);
/** Load Events */
jQuery(document).ready(($) => {
    components.fire('ready', $);
});
jQuery(window).on('load', ($) => components.fire('loaded', $));
