<template>
    <div class="flex flex-wrap py-4 demo">
        <div class="w-full">
            <template  v-for="section in orderedSections">
                <div id="template-fields" class="card overflow-hidden section flex-flex-wrap mb-4 " v-bind:key="section.id">
                    <div class="header w-full flex flex-wrap justify-around items-center">
                        <div class="w-1/2 flex flex-wrap items-center">
                            {{ section.name }} 
                            <input type="text" class="w-auto form-control form-input text-xs ml-3" :placeholder="__('Name')" v-model="section.name">
                        </div>
                        <div class="w-1/2 fields flex flex-wrap justify-end ">
                            <div class="w-2/3 flex flex-wrap justify-end">
                                <select class="w-2/3 form-control form-select" :id="section.id+'_code_language'" v-model="section.selectedField">
                                    <option disabled="disabled" selected="selected" :value="null">
                                        {{ __('Choose a field') }}
                                    </option>
                                    <optgroup v-for="(fieldGroup, groupKey) in fields" :label="groupKey" :key="groupKey">
                                        <option v-for="(field, key) in fieldGroup" :value="field" :key="key">{{ field.name }}</option>    
                                    </optgroup>
                                </select>
                                <button class="btn btn-default btn-primary mx-2" @click="addField(section)">{{ __('Add') }}</button>
                            </div>

                            <div class="pl-2 flex content-center ">


                                <template  v-if="section.first">
                                    <button class="btn btn-move btn-primary mx-1" @click="moveDown(section)">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="fill-current"><path fill-rule="nonzero" d="M15.3 9.3a1 1 0 0 1 1.4 1.4l-4 4a1 1 0 0 1-1.4 0l-4-4a1 1 0 0 1 1.4-1.4l3.3 3.29 3.3-3.3z"/></svg>
                                    </button>
                                </template>

                                <template  v-else-if="section.last">
                                    <button class="btn btn-move btn-primary mx-1" @click="moveUp(section)">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="fill-current"><path fill-rule="nonzero"  d="M8.7 14.7a1 1 0 0 1-1.4-1.4l4-4a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1-1.4 1.4L12 11.42l-3.3 3.3z"/></svg>
                                    </button>
                                </template>

                                <template  v-else>
                                    <button class="btn btn-move btn-primary mx-1" @click="moveDown(section)">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="fill-current"><path fill-rule="nonzero" d="M15.3 9.3a1 1 0 0 1 1.4 1.4l-4 4a1 1 0 0 1-1.4 0l-4-4a1 1 0 0 1 1.4-1.4l3.3 3.29 3.3-3.3z"/></svg>
                                    </button>

                                    <button class="btn btn-move btn-primary mx-1" @click="moveUp(section)">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="fill-current"><path fill-rule="nonzero"  d="M8.7 14.7a1 1 0 0 1-1.4-1.4l4-4a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1-1.4 1.4L12 11.42l-3.3 3.3z"/></svg>
                                    </button>
                                </template>
                                
                            </div>
                        </div>
                    </div>

                    <draggable ref="panel-template" id="fieldlist" class="dragArea py-3" :class="{ 'placeholder': section.children.length == 0 }" v-model="section.children" :options="fieldsTemplateDragOption">

                        <template v-for="(item, indexItem) in section.children">
                            <field-template v-bind:key="indexItem" :item="item" :fields="fields" v-on:removeField="removeField"></field-template>
                        </template>
                            
                    </draggable>
                </div>
            </template>
        </div>

        <div class="buttons-section bg-30  w-full flex px-8 py-4 mt-4 border border-60">
            <div class="w-2/3 pl-2">
                <button class="btn btn-default btn-primary" @click="addSection">{{ __('Add section') }}</button>
            </div>

            <button class="ml-auto btn btn-default btn-primary mr-3" @click="saveFields" type="button">
                <template v-if="saving">
                    {{ __('Saving') }}
                </template>
                <template v-else>
                    {{ __('Save') }}
                </template>
            </button>
        </div>
    </div>
</template>

<script>
import _ from 'lodash';
import api from '../api.js';
import draggable from 'vuedraggable';
import FieldTemplate from './FieldTemplate';

export default {
    props: ['resourceName', 'resourceId', 'field'],

    components: {
        draggable: draggable,
        'field-template': FieldTemplate,
    },
    data: () => ({
        saving: false,
        fields: [],
        fieldsTemplate: [],
        sections: [
            {
                name: 'Section',
                position: 1,
                id: 1,
                selectedField: null,
                component: 'section',
                children: [],
            },
        ],
        fieldsDragOption: {
            group: {
                name: 'fields',
                pull: 'clone',
                put: false,
                revertClone: true,
            },
            sort: false,
        },

        fieldsTemplateDragOption: {
            group: 'fields',
            sort: true,
            handle: '.handle',
        },
    }),

    mounted() {
        return api.getFields(this.resourceId).then(result => {
            this.fields = result.staticFields;
            if (result.fields.length > 0) {
                this.sections = result.fields;
            }
            this.setDefaultSection();
        });
    },

    methods: {
        setDefaultSection() {
            let position = 1;
            this.sections.forEach(section => {
                let first = false;
                let last = false;

                if (!section.hasOwnProperty('selectedField')) {
                    this.$set(section, 'selectedField', null);
                }
                if (!section.hasOwnProperty('position')) {
                    this.$set(section, 'position', position);
                }

                if (position === 1) {
                    first = true;
                }

                if (_.size(this.sections) == position) {
                    last = true;
                }

                this.$set(section, 'first', first);
                this.$set(section, 'last', last);

                position++;
            });
        },

        setPositions() {
            let position = 1;

            _.forEach(_.orderBy(this.sections, 'position'), section => {
                let first = false;
                let last = false;

                // this.$set(section, 'position', position);
                if (position === 1) {
                    first = true;
                }

                if (_.size(this.sections) == position) {
                    last = true;
                }

                this.$set(section, 'first', first);
                this.$set(section, 'last', last);
                position++;
            });
        },

        addSection() {
            let newSection = {
                name: 'Section',
                position: _.size(this.sections) + 1,
                id: Math.floor(Math.random() * 9999) + 1,
                component: 'section',
                selectedField: null,
                children: [],
            };
            this.sections.push(newSection);
        },

        addField(section) {
            if (section.selectedField !== null) {
                let original = section.selectedField;
                let clone = _.clone(original, true);
                clone.id = Math.floor(Math.random() * 9999) + 1;
                clone.panel = section.id;
                section.children.push(clone);
                section.selectedField = null;
            }
        },

        moveDown(section) {
            let previusSection = _.find(this.sections, function(s) {
                return s.position === section.position + 1;
            });

            this.moveObjectElement(section, previusSection);
            this.$nextTick(() => {
                this.setPositions();
            });
        },

        moveUp(section) {
            let nextSection = _.find(this.sections, function(s) {
                return s.position === section.position - 1;
            });

            this.moveObjectElement(section, nextSection);
            this.$nextTick(() => {
                this.setPositions();
            });
        },

        moveObjectElement(currentSection, oldSection) {
            let oldPosition = oldSection.position;
            oldSection.position = currentSection.position;
            currentSection.position = oldPosition;
        },

        clone: function(original) {
            let clone = _.clone(original, true);
            clone.id = Math.floor(Math.random() * 9999) + 1;
            return clone;
        },

        removeField: function(item) {
            this.sections.forEach(section => {
                if (section.id == item.panel) {
                    section.children = _.filter(section.children, function(element) {
                        return element.id !== item.id;
                    });
                }
            });
        },

        saveFields() {
            this.saving = true;
            return api
                .saveFields(this.resourceId, this.orderedSections)
                .then(() => {
                    this.$toasted.show(this.__('Fields saved!'), {
                        type: 'success',
                    });
                    this.saving = false;
                })
                .catch(() => {
                    this.$toasted.show(this.__('Error saving the file. Please check your log'), {
                        type: 'error',
                    });
                    this.saving = false;
                });
        },
    },

    computed: {
        fieldsTemplateEmpty() {
            return _.size(this.fieldsTemplate) === 0 ? true : false;
        },

        orderedSections() {
            return _.orderBy(this.sections, 'position');
        },
    },

    watch: {
        //
    },
};
</script>

<style scoped>
.btn-move {
    height: 2.25rem;
    padding-left: 0.25rem;
    padding-right: 0.25rem;
    border-radius: 0.5rem;
    -webkit-box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.05);
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.05);
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}
</style>
