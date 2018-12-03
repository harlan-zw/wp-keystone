import WebFont from 'webfontloader';

export default {
    init: function () {
        WebFont.load({
            google: {
                families: ['Open+Sans'],
            },
        });
    },
};
