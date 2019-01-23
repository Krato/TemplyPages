Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'temply-pages.pages',
            path: '/content/pages',
            component: require('./components/Page'),
        },
        {
            name: 'temply-pages.templates-old',
            path: '/content/templates-old',
            component: require('./components/Tool'),
        },
        {
            name: 'temply-pages.templates',
            path: '/content/templates',
            component: require('./components/TemplatesTool'),
        },
    ]);

    Vue.component('fields-tool', require('./components/FieldsResourceTool'));
    Vue.component('page-configuration', require('./components/PageConfiguration'));
});
