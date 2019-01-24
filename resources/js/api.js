export default {
    pages() {
        return window.axios
            .get('/nova-vendor/infinety/temply-pages/pages')
            .then(response => response.data);
    },

    pageInfo(pageId) {
        return window.axios
            .get('/nova-vendor/infinety/temply-pages/page-info/' + pageId)
            .then(response => response.data);
    },

    getFields(resourceId) {
        return window.axios
            .get('/nova-vendor/infinety/temply-pages/fields', {
                params: { resourceId: resourceId },
            })
            .then(response => response.data);
    },

    saveFields(resourceId, fields) {
        return window.axios
            .post('/nova-vendor/infinety/temply-pages/actions/save-fields', {
                resourceId: resourceId,
                fields: fields,
            })
            .then(response => response.data);
    },

    getTemplateFields(resourceId, templateId) {
        return window.axios
            .get('/nova-vendor/infinety/temply-pages/page/template-fields', {
                params: { resourceId: resourceId, templateId: templateId },
            })
            .then(response => response.data);
    },

    getTemplateFieldsWithValues(resourceId) {
        return window.axios
            .get('/nova-vendor/infinety/temply-pages/page/template-fields-values', {
                params: { resourceId: resourceId },
            })
            .then(response => response.data);
    },

    configurations(page) {
        return window.axios
            .get('/nova-vendor/infinety/temply-pages/configurations', {
                params: { page: page },
            })
            .then(response => response.data);
    },

    setDesign(pageId, design) {
        return window.axios
            .post('/nova-vendor/infinety/temply-pages/page/set-design', {
                pageId: pageId,
                design: design,
            })
            .then(response => response.data);
    },
};
