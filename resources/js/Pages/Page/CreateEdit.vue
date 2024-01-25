<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Page } from '@/types/page';
import { Block } from '@/types/block';
import { computed } from 'vue';
import { Option } from '@/types/option';

const props = defineProps<{
    data?: Page
    roleOptions: Option[]
}>();

const isUpdate = computed(() => !!props.data)

const form = useForm({
    page:  props.data ? props.data.id : undefined,
    name: props.data ? props.data.name : '',
    title: props.data ? props.data.title : '',
    description: props.data ? props.data.description : '',
    keywords: props.data ? props.data.keywords : '',
    blocks: props.data ? props.data.blocks?.map((block: Block) => block.id) : undefined
});

const submitPage = () => {
    const submitOption = {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
        onError: () => {},
    }
    if (props.data) {
        form.put(route('page.update', { page: form.page }), submitOption);
    } else {
        form.post(route('page.store'), submitOption);
    }
};
</script>

<template>
    <Head title="Page" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ props.data ? 'Update' : 'Create' }} Page
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <form @submit.prevent="submitPage" class="space-y-6">
                        <div>
                            <InputLabel for="name" value="Name" />

                            <TextInput
                                id="name"
                                v-model="form.name"
                                class="mt-1 block w-full"
                                autocomplete="page-name"
                            />

                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="title" value="Title" />

                            <TextInput
                                id="title"
                                v-model="form.title"
                                class="mt-1 block w-full"
                                autocomplete="page-title"
                            />

                            <InputError :message="form.errors.title" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="description" value="Description" />

                            <textarea
                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.description"
                            />

                            <InputError :message="form.errors.description" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="keywords" value="Keywords" />

                            <TextInput
                                id="keywords"
                                v-model="form.keywords"
                                class="mt-1 block w-full"
                                autocomplete="page-keywords"
                            />

                            <InputError :message="form.errors.keywords" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="blocks" value="Blocks" />

                            <TextInput
                                id="blocks"
                                v-model="form.blocks"
                                class="mt-1 block w-full"
                            />

                            <InputError :message="form.errors.blocks " class="mt-2" />
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
