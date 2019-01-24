<template>
    <div class="">
        <heading class="px-2 mb-4">Page Templates</heading>


        <div class="px-2" v-if="!selectedSection">
            <div class="mt-4 flex flex-wrap flex -mx-2">
                <div class="w-1/5 px-2" v-for="section in sections" :key="section.id">
                    <div class="bg-white w-full section rounded shadow-md p-4">
                        <div class="flex flex-wrap mb-4">
                            <span class="w-1/3 text-primary-50% pr-2">
                                <i class="fas fa-file-alt text-5xl"></i>
                            </span>
                            <div class="flex flex-col w-2/3  justify-between">
                                <h5 class="text-70 font-medium uppercase">{{ section.count }} {{ __('templates') }}</h5>
                                <h3 class="uppercase">{{ section.name }}</h3>
                            </div>
                        </div>
                        <div class="flex flex-wrap justify-center pt-4 border-t border-60">
                            <p class="text-center">{{ section.description }}</p>

                            <button class="btn btn-default btn-primary mt-4" @click="selectSection(section)">{{ __('View templates') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

	   	

        <div id="options" class="px-2" v-if="selectedSection">

            <div class="flex flex-wrap justify-between">
                <div class="inline-block bg-primary rounded-lg cursor-pointer" @click="reset">
                    <div class="flex flex-wrap text-white px-4 py-4">
                        <i class="fas fa-arrow-left mr-2"></i> {{ __('Return to sections') }}
                    </div>
                </div>

                <div class="inline-block bg-primary rounded-lg cursor-pointer" @click="newPage" v-if="previewUrl">
                    <div class="flex flex-wrap text-white px-4 py-4">
                        {{ __('Recreate that page') }}
                    </div>
                </div>	
            </div>

	   		   		
	   		

            <div class="mt-4 flex flex-wrap  flex -mx-2">
                <div class="w-1/5 px-2"  v-for="(template, index) in templates" :key="'template_'+index" @click="selectTemplate(template)">
                    <div class="get-preview cursor-pointer hover:opacity-50">
                        <div class="bg-custom bg-white w-full rounded shadow-md p-4" :class="{'active' : template.selected }">
                            <div class="flex flex-wrap">
                                <div class="w-1/5 border-r border-60 mr-4">
                                    <span class="text-5xl ">{{ index + 1 }}</span>
                                </div>
                                <div class="flex flex-col w-2/3  justify-center">
                                    <h5 class="text-70 font-medium uppercase">{{ selectedSection.name }} {{ __('template') }}</h5>
                                    <h3 class="uppercase">{{ template.name }}</h3>
                                </div>
		   						
                            </div>
			   				
                        </div>
                    </div>
                </div>
            </div>


        </div>


        <div id="preview" class="flex mt-4 mx-2 shadow-md" v-if="selectedTemplate">

            <div class="bg-80 w-full rounded border border-80 px-1 h-full min-h-full">
                <div class="flex flex-wrap justify-between items-center">
                    <div class="bg-80 h-8 flex flex-wrap items-center ml-1">
                        <div class="h-3 w-3 ml-1 rounded-full bg-danger"></div>
                        <div class="h-3 w-3 ml-1 rounded-full bg-warning"></div>
                        <div class="h-3 w-3 ml-1 rounded-full bg-success"></div>
                    </div>
                    <div class="text-sm pr-1">
                        <a :href="previewUrl" target="_blank" class="no-underline text-white-50%">
                            Open in new tab <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
                <div class="iframe-container">
                    <iframe class="iframe" :src="previewUrl"></iframe>
                </div>
				
            </div>

        </div>


        <div ref="modals">
            <modal ref="modalConfirm" v-if="modalConfirm" :name="'modalConfirm'" :align="'flex justify-end'">
                <div slot="container">
                    <h2 class="mb-6 text-90 font-normal text-xl">{{ __('Warning') }}</h2>
                    <p class="text-80 leading-normal ">{{ __('These are your options') }}</p>
                    <p><strong>Page type:</strong> {{ selectedSection.name }}</p>
                    <p><strong>Template:</strong> {{ selectedTemplate.name }}</p>
                </div>
                <div slot="buttons">
                    <div class="ml-auto">
                        <button type="button" @click.prevent="modalConfirm = !modalConfirm" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{ __('Cancel') }}</button>

                        <button id="confirm-overwrite-button" ref="confirmButton" @click.prevent="confirmTheme" class="btn btn-default btn-danger">{{ __('Yes') }}</button>
                    </div>
                </div>
            </modal>
        </div>
	   	
    </div>
</template>
<script>
import Modal from './Modal';

export default {
    components: {
        Modal,
    },

    data: () => ({
        sections: [
            {
                key: 'home',
                name: 'Home',
                description: 'Templates for your website home page',
                count: 4,
            },
            {
                key: 'contact',
                name: 'Contact',
                description: 'Templates for your contact page',
                count: 7,
            },
        ],
        templates: [
            {
                key: 'full',
                name: 'Full',
                url: 'https://images.weserv.nl/?url=https://i.ibb.co/vkf98nz/Image-and-text.jpg',
            },
            {
                key: 'with-map',
                name: 'With Map',
                url: 'https://images.weserv.nl/?url=https://i.ibb.co/frLVfFm/Text.jpg',
            },
        ],

        selectedSection: null,
        selectedTemplate: null,
        previewUrl: null,
        modalConfirm: false,
    }),

    methods: {
        reset() {
            this.selectedSection = null;
            this.selectedTemplate = null;
            this.previewUrl = null;
            this.resetSelecteds();
        },

        selectSection(section) {
            this.selectedSection = section;
        },

        selectTemplate(template) {
            this.resetSelecteds();
            this.selectedTemplate = template;
            template.selected = true;
            this.previewUrl = template.url;
        },

        newPage() {
            this.modalConfirm = true;
        },

        resetSelecteds() {
            this.templates.forEach(item => {
                item.selected = false;
            });
        },
    },

    mounted() {},
};
</script>
<style lang="sass">
/* Scoped Styles */
@import url("https://use.fontawesome.com/releases/v5.6.3/css/all.css")

.get-preview
	&:hover
		opacity:.5


.active
	background-color: #4099de
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
