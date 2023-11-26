<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Menu } from '@/types';

const props = defineProps<{
    data?: Menu
}>();

const form = useForm({
    menu:  props.data ? props.data.id : undefined,
    name: props.data ? props.data.name : '',
    label: props.data ? props.data.label : '',
    icon: props.data ? props.data.icon : '',
    route: props.data ? props.data.route : '',
});

const submitMenu = () => {
    const submitOption = {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
        onError: () => {},
    }
    if (props.data) {
        form.put(route('menu.update', { menu: form.menu }), submitOption);
    } else {
        form.post(route('menu.store'), submitOption);
    }
};
</script>

<template>
    <Head title="Menu" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ props.data ? 'Update' : 'Create' }} Menu
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <form @submit.prevent="submitMenu" class="space-y-6">
                        <div>
                            <InputLabel for="name" value="Name" />

                            <TextInput
                                id="name"
                                v-model="form.name"
                                class="mt-1 block w-full"
                                autocomplete="menu-name"
                            />

                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="label" value="Label" />

                            <TextInput
                                id="label"
                                v-model="form.label"
                                class="mt-1 block w-full"
                                autocomplete="menu-label"
                            />

                            <InputError :message="form.errors.label" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="icon" value="Icon" />

                            <textarea
                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.icon"
                            />

                            <InputError :message="form.errors.icon" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="route" value="Route" />

                            <TextInput
                                id="route"
                                v-model="form.route"
                                class="mt-1 block w-full"
                                autocomplete="menu-route"
                            />

                            <InputError :message="form.errors.route" class="mt-2" />
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