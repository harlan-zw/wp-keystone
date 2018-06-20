// import external dependencies
import 'jquery';
import 'headroom.js';
import 'headroom.js/dist/jQuery.headroom.js';
import 'jquery-match-height';

import 'lazysizes/plugins/unveilhooks/ls.unveilhooks';
import 'lazysizes/plugins/object-fit/ls.object-fit';
import 'lazysizes/plugins/bgset/ls.bgset';
import 'lazysizes/plugins/respimg/ls.respimg'

// import local dependencies
import Components from './util/components';
import fonts from './components/fonts';

const components = new Components([
    fonts,
]);

components.fire('init', $);
/** Load Events */
jQuery(document).ready(($) => {
    components.fire('ready', $);
});
jQuery(window).on('load', ($) => components.fire('loaded', $));
