<template> 
    <div>
        <template v-if="isCustom">
            <!-- {{ __('This is a custom page and no has predefined designs.') }} -->
            <div class="w-3/4 py-4">
                <a class="btn btn-default btn-primary" :href="page.url">{{ __('Click here to edit your page') }}</a>
            </div>
        </template>
        <template v-if="!isCustom">

            <div class="flex justify-end menu-button">
                <button
                    title="Add"
                    class="btn btn-default btn-icon bg-primary mr-3 text-white"
                    v-on:click="saveDesign()"
                >
                    {{ __('Save design') }}
                </button>
            </div>

            <div class="">
            	<div class="mt-4 flex flex-wrap  flex -mx-2">
                    <div class="w-1/5 px-2"  v-for="(template, index) in configurations" @click="selectTemplate(template)">
                        <div class="get-preview cursor-pointer hover:opacity-50">
                            <div class="bg-custom bg-white w-full rounded shadow-md p-4" :class="{'active' : template.selected, 'choosen': isChoosen(template) }">
                                <div class="flex flex-wrap">
                                    <div class="w-1/5 border-r border-60 mr-4">
                                        <span class="text-5xl ">{{ index + 1 }}</span>
                                    </div>
                                    <div class="flex flex-col w-2/3  justify-center">
                                        <h5 class="group font-medium uppercase">{{ group.name }} {{ __('template') }}</h5>
                                        <h3 class="uppercase">{{ template.name }}</h3>
                                    </div>
                                    
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="">
                <div id="preview" class="flex mt-4 mx-2 shadow-md" v-if="currentTemplate">

                    <div class="bg-80 w-full rounded border border-80 px-1 h-full min-h-full">
                        <div class="flex flex-wrap justify-between items-center">
                            <div class="bg-80 h-8 flex flex-wrap items-center ml-1">
                                <div class="h-3 w-3 ml-1 rounded-full bg-danger"></div>
                                <div class="h-3 w-3 ml-1 rounded-full bg-warning"></div>
                                <div class="h-3 w-3 ml-1 rounded-full bg-success"></div>
                            </div>
                            <div class="text-sm pr-1">
                                <a :href="previewUrl" target="_blank" class="no-underline text-white-50%">
                                    {{ __('Open in new tab') }} <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </div>
                        <div class="iframe-container">
                            <iframe class="iframe" :src="previewUrl"></iframe>
                        </div>
                        
                    </div>

                </div>
            </div>
        </template>
    </div>
</template>

<script>
import api from '../api.js';
export default {
	props: ['resourceName', 'resourceId', 'field'],
    data: () => ({
        isCustom: false,
        page: {},
        group: {},
        configurations: {},
        currentTemplate: false,
        previewUrl: '',
        choosen: ''
    }),

    methods: {
    	getConfigurations() {
    		return api.configurations(this.resourceId).then(result => {
                this.page = result.page
                this.isCustom = result.isCustom
                if (this.isCustom) {
                    // this.$parent.$parent.panel.name = 'Edit your custom page'
                } else {
                    // this.$parent.$parent.panel.component = 'panel'
                    this.group = result.group
                    this.choosen = result.current
                    this.configurations = this.parseConfigurations()
                }
                
            });
    	},

        selectTemplate(template) {
            this.resetSelecteds();
            template.selected = true
            this.currentTemplate = template;
            this.previewUrl = 'https://images.weserv.nl/?url=https://i.ibb.co/vkf98nz/Image-and-text.jpg'
        },

        parseConfigurations() {
            if (!this.group.templates) {
                return {}
            }
            return this.group.templates.map((item) => {
                this.$set(item, 'selected', false);
                return item
            })
        },

        saveDesign() {
            this.currentTemplate
            return api.setDesign(this.resourceId, this.currentTemplate.id).then(result => {
                this.$toasted.show(this.__('Design saved!'), { type: 'success' })
                this.previewUrl = ''
                this.resetSelecteds();
                this.getConfigurations()
            });
        },

        resetSelecteds() {
            this.currentTemplate = false
            this.configurations.forEach((item) => {
                item.selected = false
            })
        },

        isChoosen(template) {
            if (template.id == this.choosen) {
                return true;
            }

            return false;
        }
    },

    computed: {
        
    },

    async created() {
        // this.$parent.$parent.panel.component = 'card'
    	await this.getConfigurations()
    },
};
</script>

<style lang="sass">
/* Scoped Styles */
@import url("https://use.fontawesome.com/releases/v5.6.3/css/all.css")

.menu-button
    position: absolute
    right: -12px
    margin-top: -72px

.get-preview
    &:hover
        opacity:.75

.group
    color: var(--70)

.active
    background-color: #4099de
    color: #fff

    .group
        color: #fff


.choosen
    background-color: #21b978
    color: #fff

    .group
        color: #fff

.iframe-container
    position: relative
    overflow: hidden
    padding-top: 56.25%

.iframe
    position: absolute
    top: 0
    left: 0
    width: 100%
    height: 100%
    border: 0

</style>