<template>
    <div class="page-resource">
        <div v-for="section in sections" :key="section.name" v-if="!isCustomOrEmpty(section)">
            <!-- HEADING -->
            <div class="p-4 text-90 border-b border-40 flex justify-between items-center bg-30">
                <h1 class="font-normal text-xl capitalize">{{ section.name }}</h1>
                <!-- <span v-on:click.stop="demo">YTest</span> -->
            </div>
            <!-- HEADING -->

            <template v-for="(children, key, index) in section.children">
                <div class="w-full" :key="key">
                    <template v-if="children.field.attribute == 'repeater'">
                        <repeater-field
                            :realId="key"
                            :field="children"
                            :parentIndex="index"
                            :values="fieldValues"
                        ></repeater-field>
                    </template>
                    <component
                        v-else
                        :is="children.field.vue"
                        :errors="errors"
                        :field="children.field"
                    ></component>
                </div>
            </template>
        </div>

        <div v-if="isCustom" class="">
            <div class="p-4 text-90 border-b border-40 flex justify-between items-center bg-30">
                <h1 class="font-normal text-xl capitalize">{{ __('Custom Page') }}</h1>
            </div>

            <div class="flex border-b border-40">
                <div class="flex items-center w-1/5 py-6 px-8">
                    <h4 class="font-normal text-80">
                        {{ __('Edit Page') }}
                    </h4>
                </div>

                <div class="py-6 px-8 w-1/2">
                    <a
                        class="btn btn-default btn-primary"
                        :href="getUrlForBuilder(page.url)"
                        target="_blank"
                        >{{ __('Click here to edit') }}</a
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import _ from 'lodash';
import api from '../api.js';
import { FormField, HandlesValidationErrors } from 'laravel-nova';
// import RepeaterField from './RepeaterField';

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    components: {
        // 'repeater-field': RepeaterField
    },

    data: () => ({
        templateId: null,
        dependencyValues: {},
        sections: {},
        fieldValues: {},
        isCustom: false,
        page: {},
        customValues: {},
    }),

    created() {
        this.value = {};
        this.getPageInfo();
    },

    methods: {
        getPageInfo() {
            return api.pageInfo(this.resourceId).then(page => {
                this.templateId = page.template_id;
                this.page = page;
                if (this.page.isCustom) {
                    this.isCustom = true;
                }

                this.registerDependencyWatchers(this.$parent);
                this.updateTemplateFields();
            });
        },

        getUrlForBuilder(url) {
            return url + '?edit-page=true&t=' + new Date().getTime();
        },

        registerDependencyWatchers(parent) {
            parent.$children.forEach(component => {
                if (this.componentIsDependency(component)) {
                    component.$watch(
                        'selectedResource.value',
                        value => {
                            this.templateId = value;
                            this.updateTemplateFields();
                        },
                        { immediate: true }
                    );

                    setTimeout(() => (component.field.resourceName = undefined), 500);

                    this.dependencyValues[component.field.attribute] = component.field.value;
                }
            });
        },

        componentIsDependency(component) {
            if (component.field !== undefined && component.field.attribute == 'template') {
                return true;
            }

            return false;
        },

        updateTemplateFields() {
            if (!this.templateId) {
                this.sections = [];
                return false;
            }

            this.getFieldsFromTemplate(this.templateId);
        },

        getFieldsFromTemplate(templateId) {
            return api.getTemplateFields(this.resourceId, templateId).then(result => {
                this.sections = result.sections;
                this.fieldValues = result.values;
            });
        },

        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || '';
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value || '');
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value;
        },

        addWatcher(children) {
            if (children.field.component == 'file-field') {
                children.$watch(
                    'file',
                    value => {
                        if (value) {
                            var reader = new FileReader();
                            reader.readAsDataURL(value);

                            reader.onloadend = () => {
                                var newValue = {
                                    lastModified: value.lastModified,
                                    lastModifiedDate: value.lastModifiedDate,
                                    name: value.name,
                                    size: value.size,
                                    type: value.type,
                                    data: reader.result,
                                };
                                this.setNewValue(children.field.key, newValue);
                            };
                        }
                    },
                    { immediate: true }
                );
            } else {
                children.$watch(
                    'value',
                    value => {
                        let key = children.field.key;
                        if (children.$vnode.data.key) {
                            key = children.$vnode.data.key;
                        }
                        if (value) {
                            this.setNewValue(key, value);
                        } else {
                            this.setNewValue(key, '');
                        }
                    },
                    { immediate: true }
                );
            }
        },

        checkChildren(childrens, parent) {
            childrens.forEach(children => {
                if (children.type == 'repeater') {
                    this.checkChildren(children.$children, '');
                    // _.forEach(children.field.children, childrenRepeater => {

                    // })
                }

                this.addWatcher(children, parent);
            });
        },

        setNewValue(key, val) {
            if (val) {
                this.$set(this.customValues, key, val);
            } else {
                this.$set(this.customValues, key, '');
            }

            this.value = JSON.stringify(this.customValues);

            // formData.append(this.field.attribute, this.fieldValues);
        },

        isCustomOrEmpty(section) {
            // if (this.isCustom) {
            //     return true;
            // }

            if (!section.children) {
                return true;
            }

            if (Object.keys(section.children).length == 0) {
                return true;
            }

            return false;
        },
    },
    watch: {
        sections: function(sections) {
            if (_.size(sections) > 0) {
                setTimeout(() => this.checkChildren(this.$children, ''), 1000);
            }
        },
    },
};
</script>
