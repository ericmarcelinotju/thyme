<script setup lang="ts">
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import DefaultTable from '@/Components/Defaults/Table.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Menu } from '@/types/menu';
import { PaginationResponse } from '@/types/pagination';
import { TableColumn } from '@/types/table';

defineProps<{
    data?: Menu[];
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
  },
  {
    label: 'Sequence',
    key: 'sequence'
  },
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

const detailFunc = (item: Menu): boolean => {
  return !!item.children && item.children.length > 0
}

const incrementSequence = (item: Menu) => {
  console.log(item.sequence)
    const form = useForm({ ...item })
    form.put(route('menu.up', { id: item.id }));
};

const decrementSequence = (item: Menu) => {
    const form = useForm({ ...item })
    form.put(route('menu.down', { id: item.id }));
};
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
                        :items="data"
                        :total="data.length"
                        :detail-func="detailFunc"
                        @delete="handleDelete"
                        @edit="handleEdit"
                    >
                        <template #icon="{ item }">
                            <span v-html="item.icon"></span>
                        </template>
                        <template #sequence="{ item }">
                            <div class="flex gap-2">
                              <button class="bg-gray-200 rounded-md px-1 pb-1 hover:bg-gray-400" @click="incrementSequence(item)">
                                <i class="fa fa-chevron-up"></i>
                              </button>
                              <button class="bg-gray-200 rounded-md px-1 pb-1 hover:bg-gray-400" @click="decrementSequence(item)">
                                <i class="fa fa-chevron-down"></i>
                              </button>
                            </div>
                        </template>
                        <template #detail="{ item }">
                          <template v-if="item.children && item.children.length > 0">
                            <tr v-for="child in item.children" :key="child.id" class="even:bg-slate-100">
                              <td></td>
                              <td class="px-4 py-2 whitespace-nowrap text-sm text-grey-dark">
                                {{ child.name }}
                              </td>
                              <td class="px-4 py-2 whitespace-nowrap text-sm text-grey-dark">
                                {{ child.label }}
                              </td>
                              <td class="px-4 py-2 whitespace-nowrap text-sm text-grey-dark">
                                {{ child.type }}
                              </td>
                              <td class="px-4 py-2 whitespace-nowrap text-sm text-grey-dark">
                                <span v-html="child.icon"></span>
                              </td>
                              <td class="px-4 py-2 whitespace-nowrap text-sm text-grey-dark">
                                {{ child.route }}
                              </td>
                              <td class="px-4 py-2 whitespace-nowrap text-sm text-grey-dark">
                                <div class="flex gap-2">
                                  <button class="bg-gray-200 rounded-md px-1 pb-1 hover:bg-gray-400" @click="incrementSequence(child)">
                                    <i class="fa fa-chevron-up"></i>
                                  </button>
                                  <button class="bg-gray-200 rounded-md px-1 pb-1 hover:bg-gray-400" @click="decrementSequence(child)">
                                    <i class="fa fa-chevron-down"></i>
                                  </button>
                                </div>
                              </td>
                              <td class="flex text-center">
                                <a
                                  class="cursor-pointer text-amber-700 p-2 hover:text-amber-900"
                                  @click="handleEdit(child)"
                                >
                                  <i class="fa fa-pencil mr-1"></i>
                                </a>
                                <a
                                  class="cursor-pointer text-rose-700 p-2 hover:text-rose-900"
                                  @click="handleDelete(child)"
                                >
                                  <i class="fa fa-trash mr-1"></i>
                                </a>
                              </td>
                            </tr>
                          </template>
                        </template>
                    </DefaultTable>
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
