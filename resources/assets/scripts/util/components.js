// The routing fires all common scripts, followed by the page specific scripts.
// Add additional events for more control over timing e.g. a finalize event
export default class Components {
    constructor(routes) {
        this.routes = routes;
    }

    fire(fn = 'init', args) {
        this.routes
            .filter((route) => {
                // check if they have implemented shouldFire
                if (typeof route['shouldFire'] === 'function') {
                    if (!route['shouldFire'](route)) {
                        return false;
                    }
                }
                return typeof route[fn] === 'function'
            })
            .forEach((route) => route[fn](args));
    }

}
