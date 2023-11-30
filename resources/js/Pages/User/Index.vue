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
import { PaginationResponse } from '@/types/pagination';
import { TableColumn } from '@/types/table';
import { User } from '@/types/user';

defineProps<{
    data?: PaginationResponse<User>;
}>();

const columns: TableColumn<User>[] = [
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
    label: 'Email',
    key: 'email',
    isSortable: true,
    isSearchable: true
  },
]

const handleEdit = (item: User) => {
    const form = useForm({})
    form.get(route('user.edit', { user: item }))
}

// Delete client
const confirmingUserDeletion = ref(false)
const deleteForm = useForm({
    user: {}
})
const handleDelete = (data: User) => {
  confirmingUserDeletion.value = true
  deleteForm.user = data
}
const closeDeleteModal = () => {
  confirmingUserDeletion.value = false
  deleteForm.reset()
}
const deleteUser = () => {
  deleteForm.delete(route('user.destroy', { user: deleteForm.user }), {
        preserveScroll: true,
        onSuccess: () => closeDeleteModal(),
        onFinish: () => {
            deleteForm.reset();
        },
    });
}
</script>

<template>
    <Head title="User" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">User</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <Link :href="route('user.create')">
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
                    />
                    <DefaultPagination v-if="data" :data="data"/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <Modal :show="confirmingUserDeletion" @close="closeDeleteModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Are you sure you want to delete this user?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Once this user is deleted, all of its resources and data will be permanently deleted!
            </p>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeDeleteModal"> Cancel </SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': deleteForm.processing }"
                    :disabled="deleteForm.processing"
                    @click="deleteUser"
                >
                    Delete User
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
