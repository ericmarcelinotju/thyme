<script setup lang="ts">
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import DefaultTable from '@/Components/Defaults/Table.vue';
import DefaultPagination from '@/Components/Defaults/Pagination.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Menu } from '@/types/menu';
import { PaginationResponse } from '@/types/pagination';
import { TableColumn } from '@/types/table';

defineProps<{
    data?: PaginationResponse<Menu>;
}>();

const columns: TableColumn<Menu>[] = [
  {
    label: 'ID',
    key: 'id',
    isHidden: true
  },
  {
    label: 'Name',
    key: 'name',
    isSortable: true,
    isSearchable: true
  },
  {
    label: 'Label',
    key: 'label',
    isSortable: true,
    isSearchable: true
  },
  {
    label: 'Type',
    key: 'type',
    isSortable: true,
    isSearchable: true
  },
  {
    label: 'Icon',
    key: 'icon'
  },
  {
    label: 'Route',
    key: 'route',
    isSortable: true,
    isSearchable: true
  }
]

const handleEdit = (item: Menu) => {
    const form = useForm({})
    form.get(route('menu.edit', { menu: item }))
}

// Delete client
const confirmingMenuDeletion = ref(false)
const deleteForm = useForm({
    menu: {}
})
const handleDelete = (data: Menu) => {
  confirmingMenuDeletion.value = true
  deleteForm.menu = data
}
const closeDeleteModal = () => {
  confirmingMenuDeletion.value = false
  deleteForm.reset()
}
const deleteMenu = () => {
  deleteForm.delete(route('menu.destroy', { menu: deleteForm.menu }), {
        preserveScroll: true,
        onSuccess: () => closeDeleteModal(),
        onFinish: () => {
            deleteForm.reset();
        },
    });
}
</script>

<template>
    <Head title="Menu" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Menu</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <Link :href="route('menu.create')">
                    <PrimaryButton>
                        Create
                    </PrimaryButton>
                </Link>
                
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DefaultTable
                        v-if="data"
                        :columns="columns"
                        :items="data.data"
                        :total="data.total"
                        @delete="handleDelete"
                        @edit="handleEdit"
                    >
                        <template #icon="{ item }">
                            <span v-html="item.icon"></span>
                        </template>
                    </DefaultTable>
                    <DefaultPagination v-if="data" :data="data"/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <Modal :show="confirmingMenuDeletion" @close="closeDeleteModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Are you sure you want to delete this menu?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Once this menu is deleted, all of its resources and data will be permanently deleted!
            </p>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeDeleteModal"> Cancel </SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': deleteForm.processing }"
                    :disabled="deleteForm.processing"
                    @click="deleteMenu"
                >
                    Delete Menu
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>