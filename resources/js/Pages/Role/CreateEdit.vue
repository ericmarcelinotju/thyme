<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Role } from '@/types/role';

const props = defineProps<{
    data?: Role
}>();

const form = useForm({
    role:  props.data ? props.data.id : undefined,
    name: props.data ? props.data.name : '',
});

const submitRole = () => {
    const submitOption = {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
        onError: () => {},
    }
    if (props.data) {
        form.put(route('role.update', { role: form.role }), submitOption);
    } else {
        form.post(route('role.store'), submitOption);
    }
};
</script>

<template>
    <Head title="Role" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ props.data ? 'Update' : 'Create' }} Role
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <form @submit.prevent="submitRole" class="space-y-6">
                        <div>
                            <InputLabel for="name" value="Name" />

                            <TextInput
                                id="name"
                                v-model="form.name"
                                class="mt-1 block w-full"
                                autocomplete="role-name"
                            />

                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                            </Transition>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
