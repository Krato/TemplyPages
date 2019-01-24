<template>
    <div class="page-resource">
        <div v-for="section in sections" :key="section.name" v-if="!isCustomOrEmpty(section)">
            <!-- Only if section has fields -->
            <!-- HEADING -->
            <div class="section-header p-4 text-90 border-b border-40 flex justify-between items-center bg-30">
                <h1 class="font-normal text-xl capitalize">{{ section.name }}</h1>
                <!-- <span v-on:click.stop="demo">YTest</span> -->
            </div>
            <!-- HEADING -->
         
            <template v-for="(children, key, index) in section.children">
                <div class="w-full"  :key="key">
                    <template v-if="children.field.attribute == 'repeater'">
                        <repeater-field  :realId="key" :field="children" :parentIndex="index" :values="fieldValues"></repeater-field>
                    </template>  
                    <component v-else :is="children.field.vue" :errors="errors" :field="children.field"></component>
                </div>
            </template>
        </div>

        <div v-if="isCustom" class="flex border-b border-40">
            <div class="flex items-center w-1/4 py-4">
                <h4 class="font-normal text-80">
                    {{ __('Edit Page') }}
                </h4>
            </div>

            <div class="w-3/4 py-4">
                <a class="btn btn-default btn-primary" :href="page.url">{{ __('Click here to edit') }}</a>
            </div>
            
        </div>
    </div>
</template>

<script>
import api from '../api.js';

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data: () => ({
        templateId: null,
        sections: {},
        fieldValues: {},
        errors: {},
        isCustom: false,
        page: {},
    }),

    created() {
        this.value = {};
        this.getInfo();
    },

    methods: {
        getInfo() {
            return api.pageInfo(this.resourceId).then(page => {
                this.page = page;
                this.templateId = page.template_id;
                if (this.templateId == null) {
                    this.isCustom = true;
                } else {
                    this.getTemplateFieldsWithValues(this.templateId);
                }
            });
        },

        getTemplateFieldsWithValues(templateId) {
            return api.getTemplateFieldsWithValues(this.resourceId, templateId).then(result => {
                this.sections = result.sections;
                this.fieldValues = result.values;
            });
        },

        isCustomOrEmpty(section) {
            if (this.isCustom) {
                return true;
            }

            if (!section.children) {
                return true;
            }

            if (Object.keys(section.children).length == 0) {
                return true;
            }

            return false;
        },
    },
};
</script>

<style scoped>
.section-header {
    margin-left: -24px;
    width: calc(100% + 48px);
}
</style>
