<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Bread, Column } from '@/types/bread';
import { Option } from '@/types/option';

const props = defineProps<{
    data?: Bread
    fieldTypeOptions: Option[]
    relationOptions: Option[]
}>();

const form = useForm({
    bread:  props.data ? props.data.id : undefined,
    name: props.data ? props.data.name : '',
    table_name: props.data ? props.data.table_name : '',
    is_allow_browse: props.data ? props.data.is_allow_browse : false,
    is_allow_read: props.data ? props.data.is_allow_read : false,
    is_allow_edit: props.data ? props.data.is_allow_edit : false,
    is_allow_add: props.data ? props.data.is_allow_add : false,
    is_allow_delete: props.data ? props.data.is_allow_delete : false,
    columns: props.data ? props.data.columns : [{} as Column]
});

const submitBread = () => {
    const submitOption = {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
        onError: () => {},
    }
    if (props.data) {
        form.put(route('bread.update', { bread: form.bread }), submitOption);
    } else {
        form.post(route('bread.store'), submitOption);
    }
};

const onAddColumn = () => {
  form.columns.push({} as Column)
}

const selectedRelationColumns = (relId: string) => {
  let columns = props.relationOptions?.find((item: Option) => item.value === relId)?.object?.columns
  return columns ? JSON.parse(columns) : []
}
</script>

<template>
    <Head title="Bread" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ props.data ? 'Update' : 'Create' }} Bread
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <form @submit.prevent="submitBread" class="space-y-6">

                        <div>
                            <InputLabel for="name" value="Name" />

                            <TextInput
                                id="name"
                                v-model="form.name"
                                class="mt-1 block w-full"
                                autocomplete="bread-name"
                            />

                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="table_name" value="Table Name" />

                            <TextInput
                                id="table_name"
                                v-model="form.table_name"
                                class="mt-1 block w-full"
                                autocomplete="bread-table_name"
                            />

                            <InputError :message="form.errors.table_name" class="mt-2" />
                        </div>

                        <div>
                            <div class="flex gap-3">
                              <TextInput
                                  v-model="form.is_allow_browse"
                                  id="is_allow_browse"
                                  type="checkbox"
                              />
                              <InputLabel for="is_allow_browse" value="Allow Browse" />
                            </div>

                            <InputError :message="form.errors.is_allow_browse" class="mt-2" />
                        </div>

                        <div>
                            <div class="flex gap-3">
                              <TextInput
                                  v-model="form.is_allow_read"
                                  id="is_allow_read"
                                  type="checkbox"
                              />
                              <InputLabel for="is_allow_read" value="Allow Read" />
                            </div>

                            <InputError :message="form.errors.is_allow_read" class="mt-2" />
                        </div>

                        <div>
                            <div class="flex gap-3">
                              <TextInput
                                  v-model="form.is_allow_edit"
                                  id="is_allow_edit"
                                  type="checkbox"
                              />
                              <InputLabel for="is_allow_edit" value="Allow Edit" />
                            </div>

                            <InputError :message="form.errors.is_allow_edit" class="mt-2" />
                        </div>

                        <div>
                            <div class="flex gap-3">
                              <TextInput
                                  v-model="form.is_allow_add"
                                  id="is_allow_add"
                                  type="checkbox"
                              />
                              <InputLabel for="is_allow_add" value="Allow Add" />
                            </div>

                            <InputError :message="form.errors.is_allow_add" class="mt-2" />
                        </div>
                        
                        <div>
                            <div class="flex gap-3">
                              <TextInput
                                  v-model="form.is_allow_delete"
                                  id="is_allow_delete"
                                  type="checkbox"
                              />
                              <InputLabel for="is_allow_delete" value="Allow Delete" />
                            </div>

                            <InputError :message="form.errors.is_allow_delete" class="!my-0" />
                        </div>

                        <div>
                          <hr/>
                          <InputLabel for="column-name-1" value="Columns" class="mt-5 -mb-3"/>
                        </div>

                        <div class="border rounded-md p-4 space-y-6" v-for="(column, key) in form.columns" :key="key">
                          <div>
                              <InputLabel :for="`column-name-${key}`" value="Name" />

                              <TextInput
                                  :id="`column-name-${key}`"
                                  v-model="column.name"
                                  class="mt-1 block w-full"
                              />

                              <!-- <InputError :message="form.errors.blocks " class="mt-2" /> -->
                          </div>

                          <div>
                            <InputLabel :for="`column-type-${key}`" value="Field Type" />

                            <select
                                v-model="column.field_type"
                                class="
                                mt-1 block
                                border-gray-300
                                focus:border-indigo-500 focus:ring-indigo-500
                                rounded-md
                                shadow-sm
                                w-full
                                "
                                :id="`column-type-${key}`"
                            >
                                <option
                                    v-for="option in fieldTypeOptions"
                                    :key="option.value"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </option>
                            </select>

                            <!-- <InputError :message="form.errors.type " class="mt-2" /> -->
                          </div>

                          <div v-if="column.field_type === 'relation_select' || column.field_type === 'relation_radio'">
                            <InputLabel :for="`column-relation-id-${key}`" value="Relation" />

                            <select
                                v-model="column.relation_id"
                                class="
                                mt-1 block
                                border-gray-300
                                focus:border-indigo-500 focus:ring-indigo-500
                                rounded-md
                                shadow-sm
                                w-full
                                "
                                :id="`column-relation-id-${key}`"
                            >
                                <option
                                    v-for="option in relationOptions"
                                    :key="option.value"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </option>
                            </select>

                            <!-- <InputError :message="form.errors.type " class="mt-2" /> -->
                          </div>

                          <div v-if="!!column.relation_id">
                            <InputLabel :for="`column-relation-column-${key}`" value="Relation Column" />

                            <select
                                v-model="column.relation_column"
                                class="
                                mt-1 block
                                border-gray-300
                                focus:border-indigo-500 focus:ring-indigo-500
                                rounded-md
                                shadow-sm
                                w-full
                                "
                                :id="`column-relation-column-${key}`"
                            >
                                <option
                                    v-for="option in selectedRelationColumns(column.relation_id)"
                                    :key="option.name"
                                    :value="option.name"
                                >
                                    {{ option.name }}
                                </option>
                            </select>

                            <!-- <InputError :message="form.errors.type " class="mt-2" /> -->
                          </div>
                        </div>

                        <button class="w-full rounded-md p-2 border" type="button" @click="onAddColumn">
                          Add Column
                        </button>

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
