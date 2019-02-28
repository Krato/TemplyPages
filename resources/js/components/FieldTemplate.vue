<template>
    <div class="item-field px-3 py-3 text-grey items-center border border-60 border rounded-lg mx-3 mb-3" :class="{ 'editing': item.editActive }">
        <div class="flex flex-wrap justify-around  w-full items-center">

            <div class="handle cursor-pointer mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"/></svg>
            </div>

            <slot name="title">
                <template v-if="item.extras.name">
                    {{ __('Type') }}:<span class="capitalize px-1">{{ item.type }}</span> - {{ __('Name') }}: {{ item.extras.name }}
                </template>
                <template v-else>
                    {{ item.name }}
                </template>
            </slot>
                
            <div class="actions flex content-center">
                <button title="Edit" @click="openInfo" class="appearance-none cursor-pointer text-70 hover:text-primary mr-3">
                    <svg v-if="item.editActive" height="24" width="24" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" class="fill-current"><path fill-rule="nonzero" d="M16 0C7.163 0 0 7.164 0 16c0 8.837 7.163 16 16 16 8.836 0 16-7.163 16-16 0-8.836-7.164-16-16-16zm0 30C8.28 30 1.969 23.72 1.969 16S8.28 2 16 2c7.72 0 14 6.28 14 14s-6.28 14-14 14z"/><path d="M16.699 11.293a1.037 1.037 0 0 0-1.429 0l-6.999 6.899a.994.994 0 0 0 0 1.414 1.017 1.017 0 0 0 1.429 0l6.285-6.195 6.285 6.196a1.017 1.017 0 0 0 1.429 0 .994.994 0 0 0 0-1.414l-7-6.9z"/></svg>

                    <svg v-else height="24" width="24" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" class="fill-current"><path fill-rule="nonzero" d="M16 0C7.164 0 0 7.163 0 16c0 8.836 7.164 16 16 16 8.837 0 16-7.164 16-16 0-8.837-7.163-16-16-16zm0 30C8.28 30 2 23.72 2 16S8.28 2 16 2s14.031 6.28 14.031 14S23.72 30 16 30z"/><path d="M22.3 12.393l-6.285 6.195-6.285-6.196a1.017 1.017 0 0 0-1.429 0 .994.994 0 0 0 0 1.414l6.999 6.9c.384.38 1.044.381 1.429 0l6.999-6.899a.994.994 0 0 0 0-1.414 1.018 1.018 0 0 0-1.428 0z"/></svg>
                </button>

                <button title="Delete" @click="removeField" class="appearance-none cursor-pointer text-70 hover:text-primary mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg>
                </button>
            </div>

        </div>

        <div class="info-field px-2">
            <div class="w-full flex flex-wrap border-t border-60  mt-4 pt-3 px-3 -mx-2">

                <template v-if="item.type !== 'repeater'">
	            		
                    <div class="w-1/3 px-2 mb-4">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2 mb-2" :for="item.id+'_name'">
                            {{ __('Name') }}
                        </label>
                        <input class="w-full form-control form-input form-input-bordered py-3 px-4" type="text" :id="item.id+'_name'" v-model="item.extras.name" autocomplete="off">
                    </div>

                    <template v-if="item.type == 'text'">
                        <div class="w-1/3 px-2 mb-4">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2 mb-2" :for="item.id+'_placeholder'">
                                {{ __('Placeholder') }}
                            </label>
                            <input class="w-full form-control form-input form-input-bordered py-3 px-4" type="text" :id="item.id+'_placeholder'" v-model="item.extras.placeholder" autocomplete="off">
                        </div>
                    </template>
                    <template v-if="this.isFileType">
                        <div class="w-1/3 px-2 mb-4">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2 mb-2" :for="item.id+'_disk'">
                                {{ __('Disk') }}
                            </label>
                            <input class="w-full form-control form-input form-input-bordered py-3 px-4" type="text" :id="item.id+'_disk'" v-model="item.extras.disk" autocomplete="off">
                        </div>
                    </template>

                    <template v-if="item.type == 'code'">
                        <div class="w-1/3 px-2 mb-4">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2 mb-2" :for="item.id+'_language'">
                                {{ __('Json or Highlight') }}
                            </label>
                            <select class="w-full form-control form-select" :id="item.id+'_code_language'" v-model="item.extras.language">
                                <option disabled="disabled" selected="selected" :value="null">
                                    {{ __('Choose an option') }}
                                </option>
                                <option v-for="(language, key) in codeSelect" :value="key" :key="key">{{ language }}</option>
                            </select>
                        </div>
                    </template>

                    <template v-if="this.hasFormat">
                        <div class="w-1/3 px-2 mb-4">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2 mb-2" :for="item.id+'_format'">
                                {{ __('Format') }}
                            </label>
                            <input class="w-full form-control form-input form-input-bordered py-3 px-4" type="text" :id="item.id+'_format'" v-model="item.extras.format" autocomplete="off">
                        </div>
                    </template>

                    <template v-if="item.type == 'number'">
                        <div class="w-2/3 flex flex-wrap px-2 mb-4">
                            <div class="w-1/3 px-2 mb-4">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2 mb-2" :for="item.id+'_min'">
                                    {{ __('Min') }}
                                </label>
                                <input class="w-full form-control form-input form-input-bordered py-3 px-4" type="text" :id="item.id+'_min'" v-model="item.extras.min" autocomplete="off">
                            </div>
                            <div class="w-1/3 px-2 mb-4">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2 mb-2" :for="item.id+'_max'">
                                    {{ __('Max') }}
                                </label>
                                <input class="w-full form-control form-input form-input-bordered py-3 px-4" type="text" :id="item.id+'_max'" v-model="item.extras.max" autocomplete="off">
                            </div>
                            <div class="w-1/3 px-2 mb-4">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2 mb-2" :for="item.id+'_step'">
                                    {{ __('Step') }}
                                </label>
                                <input class="w-full form-control form-input form-input-bordered py-3 px-4" type="text" :id="item.id+'_step'" v-model="item.extras.step" autocomplete="off">
                            </div>
                        </div>
                    </template>

                    <template v-if="item.type == 'place'">
                        <div class="w-2/3 flex flex-wrap px-2 mb-4">
                            <div class="w-1/2 px-2 mb-4">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2 mb-2" :for="item.id+'_countries'">
                                    {{ __('Countries') }}
                                </label>
                                <input class="w-full form-control form-input form-input-bordered py-3 px-4" type="text" :id="item.id+'_countries'" v-model="item.extras.countries" autocomplete="off">
                            </div>
                            <div class="w-1/2 px-2 mb-4">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2 mb-2" :for="item.id+'_onlycities'">
                                    {{ __('Only Cities') }}
                                </label>
                                <input type="checkbox" :id="item.id+'_onlycities'" v-model="item.extras.onlyCities">
                            </div>
                        </div>
                    </template>

                    <template v-if="item.type == 'select'">
                        <div class="w-2/3 px-2 mb-4">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2 mb-2" :for="item.id+'_displayUsingLabels'">
                                {{ __('Display Using Labels') }}
                            </label>
                            <input type="checkbox" :id="item.id+'_displayUsingLabels'" v-model="item.extras.displayUsingLabels">
                        </div>
                    </template>

                    <template v-if="item.type == 'trix'">
                        <div class="w-1/3 px-2 mb-4">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2 mb-2" :for="item.id+'_withPublic'">
                                {{ __('Disk') }}
                            </label>
                            <input class="w-full form-control form-input form-input-bordered py-3 px-4" type="text" :id="item.id+'_withPublic'" v-model="item.extras.withPublic" autocomplete="off">
                        </div>
                    </template>


                    <template v-if="item.type == 'redactor'">
                        <div class="w-1/3 px-2 mb-4">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2 mb-2" :for="item.id+'_withPublic'">
                                {{ __('Disk') }}
                            </label>
                            <input class="w-full form-control form-input form-input-bordered py-3 px-4" type="text" :id="item.id+'_withPublic'" v-model="item.extras.withPublic" autocomplete="off">
                        </div>
                    </template>

                    <div class="w-full flex flex-wrap">

                        <div class="w-3/4 px-2 mb-4">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2 mb-2" :for="item.id+'_help'">
                                {{ __('Help text') }}
                            </label>
                            <input class="w-full form-control form-input form-input-bordered py-3 px-4" type="text" :id="item.id+'_help'" v-model="item.extras.help" autocomplete="off">
                        </div>

                        <div class="w-1/4 px-2 mb-4">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2 mb-2" :for="item.id+'_required'">
                                {{ __('Required') }}
                            </label>
                            <input type="checkbox" :id="item.id+'_required'" v-model="item.extras.required">
                        </div>
                    </div>

                    <template v-if="item.type == 'select'">
                        <div class="flex flex-wrap " >
                            <div class="w-full flex flex-wrap items-end px-2">
                                <div class="w-2/3 flex flex-wrap pr-3">
                                    <label class="w-full block uppercase tracking-wide text-grey-darker text-xs font-bold pr-2 mb-2" :for="item.id+'_help'">
                                        {{ __('Add options') }}
                                    </label>
                                    <div class="w-1/2 pr-2">
                                        <input class="w-full form-control form-input form-input-bordered py-3 px-4" :placeholder="__('Key')" type="text" v-model="select_key" autocomplete="off">
                                    </div>
                                    <div class="w-1/2 pl-2">
                                        <input class="w-full form-control form-input form-input-bordered py-3 px-4" :placeholder="__('Option')" type="text" v-model="select_option" autocomplete="off">
                                    </div>
                                </div>
	                                
                                <div class="w-1/3"> 
                                    <button class="mx-auto btn btn-default btn-primary mr-3" @click="addOption">{{ __('Add Option') }}</button>
                                </div>
                            </div>
                            <div class="w-full flex flex-wrap mt-4 ml-2" v-if="hasOptions">
                                <table class="table w-full">
                                    <thead>
                                        <tr>
                                            <th class="text-left">{{ __('Key') }}</th>
                                            <th class="text-left">{{ __('Option') }}</th>
                                            <th class="text-left">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(option, key) in item.extras.options" :key="key">
                                            <td>{{ key }}</td>
                                            <td>{{ option }}</td>
                                            <td>
                                                <button title="Delete" class="appearance-none cursor-pointer text-70 hover:text-primary mr-3" @click="removeOption(key)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>
                </template>

                <template v-if="item.type == 'repeater'">

                    <div class="section w-full px-2">
                        <div class="header flex flex-wrap items-center">
                            <div class="w-1/2 flex flex-wrap items-center">
                                Repeat

                                <div class="w-16 mx-4">
		
                                    <input class="w-full form-control form-input form-input-bordered py-3 px-4" type="number" step="1" :id="item.id+'_repeatTimes'" v-model="item.extras.repeatTimes">
                                </div>

                                times
                            </div>
                            <div class="w-1/3 fields flex flex-wrap justify-end">
                                <div class="w-2/3">
                                    <select class="w-full form-control form-select" :id="item.id+'_code_language'" v-model="item.selectedField">
                                        <option disabled="disabled" selected="selected" :value="null">
                                            {{ __('Choose a field') }}
                                        </option>
                                        <optgroup v-for="(fieldGroup, groupKey) in fields" :label="groupKey" :key="groupKey">
                                            <option v-for="(field, key) in fieldGroup" :value="field" :key="key">{{ field.name }}</option>    
                                        </optgroup>
	                                        
                                    </select>
                                </div>

                                <div class="w-1/3 pl-2">
                                    <button class="btn btn-default btn-primary" @click="addField">{{ __('Add') }}</button>
                                </div>
                            </div>
                        </div>

                        <draggable ref="panel-template" id="fieldlist" class="dragArea py-3" :class="{ 'placeholder': item.children.length == 0 }" v-model="item.children" :options="fieldsTemplateDragOption">

                            <template v-for="(children, childrenId) in item.children">
                                <field-template v-bind:key="childrenId" :item="children" :fields="fields" v-on:removeField="removeFieldRepeater"></field-template>
                            </template>
                        </draggable>
                    </div>
                </template>
            </div>
            	
                
        </div>
    </div>
</template>

<script>
import _ from 'lodash';
import draggable from 'vuedraggable';
import FieldTemplate from './FieldTemplate';

export default {
    name: 'field-template',
    components: {
        draggable: draggable,
        'field-template': FieldTemplate,
    },
    props: {
        item: Object,
        fields: Object,
    },
    data: () => ({
        editActive: false,
        codeSelect: {
            json: 'json',
            dockerfile: 'dockerfile',
            javascript: 'javascript',
            markdown: 'markdown',
            nginx: 'nginx',
            php: 'php',
            ruby: 'ruby',
            sass: 'sass',
            shell: 'shell',
            vue: 'vue',
            xml: 'xml',
            yaml: 'yaml',
        },
        select_key: null,
        select_option: null,
    }),
    methods: {
        addField() {
            if (this.item.selectedField !== null) {
                let original = this.item.selectedField;
                let clone = _.clone(original, true);
                clone.id = Math.floor(Math.random() * 9999) + 1;
                clone.panel = this.item.id;
                clone.editActive = false;
                this.item.children.push(clone);
                this.item.selectedField = null;
            }
        },

        removeField() {
            this.$emit('removeField', this.item);
        },

        removeOption(key) {
            this.$delete(this.item.extras.options, key);
        },

        removeFieldRepeater(item) {
            this.item.children = _.filter(this.item.children, function(element) {
                return element.id !== item.id;
            });
        },

        addOption() {
            if (!this.item.extras.options.hasOwnProperty(this.select_key)) {
                this.$set(
                    this.item.extras.options,
                    this.select_key.toLowerCase(),
                    this.select_option
                );
                this.select_key = null;
                this.select_option = null;
            }
        },

        openInfo() {
            if (!this.item.hasOwnProperty('editActive')) {
                this.$set(this.item, 'editActive', true);
            } else {
                this.item.editActive = !this.item.editActive;
            }
        },

        setInfo() {
            this.setDefaults();

            if (this.item.type == 'text') {
                this.textField();
            }

            if (this.isFileType) {
                this.fileField();
            }

            if (this.hasFormat) {
                this.formatField();
            }

            if (this.item.type == 'number') {
                this.numberField();
            }

            if (this.item.type == 'place') {
                this.placeField();
            }

            if (this.item.type == 'select') {
                this.selectField();
            }

            if (this.item.type == 'trix') {
                this.trixField();
            }

            if (this.item.type == 'repeater') {
                this.repeaterField();
            }
        },

        setDefaults() {
            // Set ID
            if (!this.item.hasOwnProperty('id')) {
                this.$set(this.item, 'id', Math.floor(Math.random() * 9999) + 1);
            }

            //Set name
            if (!this.item.hasOwnProperty('extras')) {
                this.$set(this.item, 'extras', {});
            }

            //Set Name
            if (!this.item.extras.hasOwnProperty('name')) {
                this.$set(this.item.extras, 'name', '');
            }

            //Set Required
            if (!this.item.extras.hasOwnProperty('required')) {
                this.$set(this.item.extras, 'required', false);
            }

            //Set Help
            if (!this.item.extras.hasOwnProperty('help')) {
                this.$set(this.item.extras, 'help', '');
            }
        },

        fileField() {
            if (!this.item.extras.hasOwnProperty('disk')) {
                this.$set(this.item.extras, 'disk', '');
            }
        },

        textField() {
            //Set Placeholder
            if (!this.item.extras.hasOwnProperty('placeholder')) {
                this.$set(this.item.extras, 'placeholder', '');
            }
        },

        codeField() {
            //Set Placeholder
            if (!this.item.extras.hasOwnProperty('language')) {
                this.$set(this.item.extras, 'language', '');
            }
        },

        formatField() {
            if (!this.item.extras.hasOwnProperty('format')) {
                this.$set(this.item.extras, 'format', null);
            }
        },

        numberField() {
            if (!this.item.extras.hasOwnProperty('min')) {
                this.$set(this.item.extras, 'min', null);
            }
            if (!this.item.extras.hasOwnProperty('max')) {
                this.$set(this.item.extras, 'max', null);
            }
            if (!this.item.extras.hasOwnProperty('step')) {
                this.$set(this.item.extras, 'step', null);
            }
        },

        placeField() {
            if (!this.item.extras.hasOwnProperty('onlyCities')) {
                this.$set(this.item.extras, 'onlyCities', false);
            }
            if (!this.item.extras.hasOwnProperty('countries')) {
                this.$set(this.item.extras, 'countries', []);
            }
        },

        selectField() {
            if (!this.item.extras.hasOwnProperty('options')) {
                this.$set(this.item.extras, 'options', {});
            }

            if (!this.item.extras.hasOwnProperty('displayUsingLabels')) {
                this.$set(this.item.extras, 'displayUsingLabels', false);
            }
        },

        trixField() {
            if (!this.item.extras.hasOwnProperty('withFiles')) {
                this.$set(this.item.extras, 'withFiles', null);
            }
        },

        repeaterField() {
            if (!this.item.extras.hasOwnProperty('repeatTimes')) {
                this.$set(this.item.extras, 'repeatTimes', 1);
            }

            if (!this.item.hasOwnProperty('children')) {
                this.$set(this.item, 'children', []);
            }

            if (!this.item.hasOwnProperty('selectedField')) {
                this.$set(this.item, 'selectedField', null);
            }
        },
    },
    created() {
        this.setInfo();
    },
    computed: {
        isFileType() {
            if (
                this.item.type == 'file' ||
                this.item.type == 'avatar' ||
                this.item.type == 'image'
            ) {
                return true;
            }

            return false;
        },

        hasFormat() {
            if (
                this.item.type == 'currency' ||
                this.item.type == 'date' ||
                this.item.type == 'datetime'
            ) {
                return true;
            }

            return false;
        },

        hasOptions: function() {
            if (this.item.hasOwnProperty('extras')) {
                if (this.item.extras.hasOwnProperty('options')) {
                    if (_.size(this.item.extras.options) > 0) {
                        return true;
                    }
                }
            }

            return false;
        },
    },
};
</script>

<style>





















<
