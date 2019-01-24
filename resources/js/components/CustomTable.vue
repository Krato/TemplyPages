<template>
    <div class="overflow-hidden overflow-x-auto relative">
        <table class="table w-full" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th v-for="column in columns" :key="column" :class="{'text-left': column.sortable, 'text-right': !column.sortable}">
                        <sortable-icon
                            v-if="column.sortable"
                            @sort="sort(column.attribute)"
                            :resource-name="resourceName"
                            :uri-key="column.attribute"
                        >
                            {{ column.label }}
                        </sortable-icon>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(field, index) in fields"
                    :key="index"
                    :field="field"
                >
                    <td class="whitespace-no-wrap text-left">
                        {{ field.id }}
                    </td>
                    <td class="whitespace-no-wrap text-left">
                        {{ field.name }}
                    </td>
                    <td class="whitespace-no-wrap text-left">
                        <template v-if="field.template">
                            {{ field.template.name }}
                        </template>

                        <template v-else>
                            {{ 'Custom' }}
                        </template>
                    
                    </td>
                    <td class="td-fit text-right pr-6">

                        <template v-if="!field.isCustom">

                            <!-- View Resource Link -->
                            <router-link
                                :dusk="`${field.id}-view-button`"
                                class="cursor-pointer text-70 hover:text-primary mr-3"
                                :to="{ name: 'detail', params: {
                                    resourceName: resourceName,
                                    resourceId: field.id
                                }}"
                                :title="__('View')"
                            >
                                <icon type="view" width="22" height="18" view-box="0 0 22 16" />
                            </router-link>

                            <!-- Edit Resource Link -->
                            <router-link
                                v-if="!field.isCustom"
                                :dusk="`${field.id}-edit-button`"
                                class="cursor-pointer text-70 hover:text-primary mr-3"
                                :to="{
                                    name: 'edit',
                                    params: {
                                        resourceName: resourceName,
                                        resourceId: field.id
                                    },
                                    query: {
                                        viaResource: '',
                                        viaResourceId: '',
                                        viaRelationship: ''
                                    }
                                }"
                                :title="__('Edit')"
                            >
                                <icon type="edit" />
                            </router-link>

                        </template>

                        <template  v-else>
                            <!-- Edit Resource Link -->
                            <a :href="'/nova-vendor/infinety/temply-pages/go-to/'+field.id"
                               class="cursor-pointer text-70 hover:text-primary mr-3"
                               :title="__('Edit')"
                            >
                                <icon type="edit" />
                            </a>

                        </template>
                    
                        <!-- Delete Resource Link -->
                        <button
                            :dusk="`${field.id}-delete-button`"
                            class="appearance-none cursor-pointer text-70 hover:text-primary mr-3"
                            @click.prevent="openDeleteModal(field)"
                            :title="__('Delete')"
                        >
                            <icon />
                        </button>

                    </td>
                </tr>
            </tbody>
        </table>

        <portal to="modals">
            <transition name="fade">
                <delete-resource-modal
                    v-if="deleteModalOpen"
                    @confirm="confirmDelete"
                    @close="closeDeleteModal"
                    :mode="'delete'"
                >
                    <div slot-scope="{ uppercaseMode, mode }" class="p-8">
                        <heading :level="2" class="mb-6">{{ __(uppercaseMode+' Resource') }}</heading>
                        <p class="text-80 leading-normal">{{__('Are you sure you want to '+mode+' this resource?')}}</p>
                    </div>
                </delete-resource-modal>
            </transition>

            <transition name="fade">
                <restore-resource-modal
                    v-if="restoreModalOpen"
                    @confirm="confirmRestore"
                    @close="closeRestoreModal"
                >
                    <div class="p-8">
                        <heading :level="2" class="mb-6">{{__('Restore Resource')}}</heading>
                        <p class="text-80 leading-normal">{{__('Are you sure you want to restore this resource?')}}</p>
                    </div>
                </restore-resource-modal>
            </transition>
        </portal>
    </div>
</template>

<script>
export default {
    data: () => ({
        columns: [
            {
                label: 'ID',
                attribute: 'id',
                sortable: true,
            },
            {
                label: 'Name',
                attribute: 'name',
                sortable: true,
            },
            {
                label: 'Template',
                attribute: 'template',
                sortable: true,
            },
            {
                label: 'Actions',
                attribute: 'actions',
                sortable: false,
            },
        ],
        deleteModalOpen: false,
        restoreModalOpen: false,
        deleteField: null,
    }),
    props: {
        fields: {
            type: Array,
            required: true,
        },
        sort: {
            type: Function,
        },

        deleteResource: {
            type: Function,
        },
        resourceName: {
            type: String,
            required: true,
        },
    },
    methods: {
        openDeleteModal(field) {
            this.deleteModalOpen = true;
            this.deleteField = field;
        },

        confirmDelete() {
            this.deleteResource(this.deleteField);
            this.closeDeleteModal();
        },

        closeDeleteModal() {
            this.deleteModalOpen = false;
        },
    },
};
</script>
