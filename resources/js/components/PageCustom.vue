<template>
	<div>
		<heading class="mb-6">Pages</heading>

	    <div class="flex justify-between">
	            <div class="relative h-9 flex items-center mb-6">
	                <icon type="search" class="absolute ml-3 text-70" />

	                <input v-model="search"
	                       class="appearance-none form-control form-input w-search pl-search"
	                       placeholder="Search"
	                       type="search"
	                >
	            </div>

	            <span class="ml-auto mb-6">
	                <button @click="createModalOpened = !createModalOpened"
	                        class="btn btn-default btn-primary"
	                >
	                    {{ __('Create') }}
	                </button>
	            </span>
	        </div>

	        <card>
	            <custom-table :fields="filteredFields" :sort="sortBy" :resourceName="resourceName" :deleteResource="deleteResource"
	            ></custom-table>
	        </card>

             <portal to="modals">
                <transition name="fade">
                    <modal
                        ref="modalCreate"
                        v-if="createModalOpened"
                        :name="'modalCreate'"
                        :align="'flex justify-end'"
                        :width="400"
                    >
                        <div slot="container">
                            <h2 class="mb-6 text-90 font-normal text-xl">{{ __('Create new') }} {{ __('page') }}</h2>
                            
                            <p class="text-80 leading-normal">
                                {{ __('What kind of oage you want to create') }}
                            </p>
                        </div>
                        <div slot="buttons">
                            <div class="ml-auto">
                                <button
                                    type="button"
                                    @click.prevent="closeModal"
                                    class="btn text-80 font-normal h-9 px-3 mr-3 btn-link"
                                >
                                    {{ __('Cancel') }}
                                </button>

                                <button
                                    id="confirm-overwrite-button"
                                    ref="confirmButton"
                                    @click.prevent="confirmItemDelete"
                                    class="btn btn-default btn-danger"
                                >
                                    {{ __('Yes, remove!') }}
                                </button>
                            </div>
                        </div>
                    </modal>
                </transition>
            </portal>
	    </div>
	</div>
</template>

<script>
import CustomTable from './CustomTable';
import Modal from './Modal'

export default {
    components: { CustomTable, Modal},
    data() {
        return {
            fields: [],
            search: '',
            sort: {
                field: '',
                order: -1,
            },
            showNova: false,
            resourceName: 'pages',
            createModalOpened: false
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            Nova.request().get('/nova-vendor/infinety/temply-pages/pages').then(response => {
                this.fields = response.data;
            });
        },
        sortBy(field) {
            this.sort.field = field;
            this.sort.order *= -1;
            this.fields.sort((field1, field2) => {
                let comparison = 0;
                if (field1[this.sort.field] < field2[this.sort.field]) {
                    comparison = -1;
                }
                if (field1[this.sort.field] > field2[this.sort.field]) {
                    comparison = 1;
                }
                return comparison * this.sort.order;
            });
        },
        toggleNova() {
            this.showNova = ! this.showNova;
        },

        deleteResource(field) {
            Nova.request().delete('/nova-vendor/infinety/temply-pages/page/'+field.id+'/destroy').then(response => {
                this.getData()
            });
        }
    },
    computed: {
        filteredFields() {
            if (! this.search.length) {
                return this.fields;
            }
            const regex = this.searchRegex;
            // User input is not a valid regular expression, show no results
            if (! regex) {
                return {};
            }
            return this.fields.filter(route => {
                let matchesSearch = false;
                for (let key in route) {
                    if (Array.isArray(route[key])) {
                        route[key].forEach(property => {
                            if (regex.test(property)) {
                                matchesSearch = true;
                            }
                        });
                    }
                    else if (regex.test(route[key])) {
                        matchesSearch = true;
                    }
                }
                return matchesSearch;
            });
        },
        searchRegex() {
            try {
                return new RegExp('(' + this.search + ')','i');
            } catch (e) {
                return false;
            }
        }
    }
};
</script>

<style>
/* Scoped Styles */
</style>
