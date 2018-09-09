import WebFont from 'webfontloader';

export default {
    init: function () {
        WebFont.load({
            typekit: {
                id: 'kga8gvy',
            },
            google: {
                families: ['Open+Sans'],
            },
        });
    },
};
