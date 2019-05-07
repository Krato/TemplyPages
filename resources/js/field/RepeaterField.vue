<template>
    <div>
        <template v-for="number in field.field.repeatTimes">
            <div
                class="p-4 m-4 text-90 border-b border-40 flex justify-between items-center bg-30"
                :key="'repeater_' + number"
            >
                <h1 class="font-normal text-xl capitalize">{{ field.field.name }} {{ number }}</h1>
            </div>
            <template v-for="(childrenRepeater, keyRepeater, indexRepeater) in field.children">
                <template v-if="childrenRepeater.field.attribute == 'repeater'">
                    <div
                        class="w-5/6 ml-auto"
                        :rel="keyRepeater"
                        :index="indexRepeater"
                        :parentIndex="number"
                        :key="'repeater_' + keyRepeater + '_' + number"
                    >
                        <repeater-field
                            v-bind:key="getKey(number, indexRepeater, childrenRepeater)"
                            :field="childrenRepeater"
                            :parentIndex="number"
                            :realId="keyRepeater"
                            :values="values"
                        >
                        </repeater-field>
                    </div>
                </template>
                <template v-else>
                    <div class="px-6" :key="'repeater_' + keyRepeater + '_' + number">
                        <component
                            v-bind:key="getKey(number, indexRepeater, childrenRepeater)"
                            :is="childrenRepeater.field.vue"
                            :field="
                                getField(
                                    childrenRepeater.field,
                                    number,
                                    indexRepeater,
                                    childrenRepeater
                                )
                            "
                        >
                            <!-- :errors="errors" -->
                        </component>
                    </div>
                </template>
            </template>
        </template>
    </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';

export default {
    name: 'repeater-field',
    mixins: [FormField, HandlesValidationErrors],
    props: {
        field: Object,
        parentIndex: String | Number,
        realId: String | Number,
        values: Object,
    },

    data: () => ({
        type: 'repeater',
    }),

    methods: {
        getKey(number, indexRepeater, field) {
            if (this.parentIndex != 0) {
                return field.field.key + '_' + this.parentIndex + '_' + number;
            }

            return field.field.key + '_' + number + '_' + (indexRepeater + 1);
        },
        getField(field, number, indexRepeater, childrenRepeater) {
            let key = this.getKey(number, indexRepeater, childrenRepeater);

            if (this.values.hasOwnProperty(key)) {
                field['value'] = this.values[key];
            }

            let newField = {
                attribute: field.attribute,
                component: field.component,
                fill: field.fill,
                helpText: field.helpText,
                indexName: field.indexName,
                key: field.key,
                name: field.name,
                panel: field.panel,
                prefixComponent: field.prefixComponent,
                sortable: field.sortable,
                textAlign: field.textAlign,
                value: this.values[key],
                vue: field.vue,
            };
            // return field;
            return newField;
        },
    },

    mounted() {
        //
    },
};
</script>
