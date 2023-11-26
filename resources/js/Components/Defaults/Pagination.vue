<template>
  <div class="flex justify-between items-center mt-4">
    <div class="bg-white flex items-center justify-between">
      <div class="flex-1 flex justify-between sm:hidden">
        <a
          class="
            relative
            inline-flex
            items-center
            px-3
            py-1
            border border-gray-300
            text-sm
            font-medium
            text-gray-900
            bg-white
            hover:bg-gray-50
            rounded-l-md
          "
          :class="{'opacity-50 cursor-not-allowed': !data.prev_page_url}"
          :href="data.prev_page_url"
        >
          prev
      </a>
        <a
          class="
            relative
            inline-flex
            items-center
            px-3
            py-1
            border border-gray-300
            text-sm
            font-medium
            text-gray-900
            bg-white
            hover:bg-gray-50
            rounded-r-md
          "
          :class="{'opacity-50 cursor-not-allowed': !data.next_page_url}"
          :href="data.next_page_url"
        >
          next
        </a>
      </div>
      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
          <nav
            aria-label="Pagination"
            class="relative z-0 inline-flex shadow-sm -space-x-px"
          >
            <a
              class="
                relative
                inline-flex
                items-center
                px-1
                py-1
                border border-gray-300
                bg-white
                text-sm
                font-medium
                text-gray-500
                hover:bg-gray-50
                rounded-l-md
              "
              :class="{'opacity-50 cursor-not-allowed': !data.prev_page_url}"
              :href="data.prev_page_url"
            >
              <span class="sr-only">
                prev
              </span>
              <i class="fa fa-chevron-left"></i>
            </a>
            <a
              v-if="hasFirst"
              class="
                bg-white
                border-gray-300
                text-gray-500
                hover:bg-gray-50
                relative
                inline-flex
                items-center
                px-3
                py-1
                border
                text-sm
                font-medium
              "
              :href="data.first_page_url"
            >
              1
            </a>
            <span
              v-if="hasFirst"
              class="
                relative
                inline-flex
                items-center
                px-3
                py-1
                border border-gray-300
                bg-white
                text-sm
                font-medium
                text-gray-900
              "
            >
              ...
            </span>
            <a
              v-for="page in pages"
              :key="page"
              :class="[
                {
                  'z-10 bg-sky-50 border-sky-500 text-sky-600': data.current_page == page,
                },
                'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-3 py-1 border text-sm font-medium',
              ]"
              :href="`?page=${page}`"
            >
              {{ page }}
            </a>
            <span
              v-if="hasLast"
              class="
                relative
                inline-flex
                items-center
                px-3
                py-1
                border border-gray-300
                bg-white
                text-sm
                font-medium
                text-gray-900
              "
            >
              ...
            </span>
            <a
              v-if="hasLast"
              class="
                bg-white
                border-gray-300
                text-gray-500
                hover:bg-gray-50
                relative
                inline-flex
                items-center
                px-3
                py-1
                border
                text-sm
                font-medium
              "
              :href="data.last_page_url"
            >
              {{ data.last_page }}
            </a>
            <a
              class="
                relative
                inline-flex
                items-center
                px-1
                py-1
                border border-gray-300
                bg-white
                text-sm
                font-medium
                text-gray-500
                hover:bg-gray-50
                rounded-r-md
              "
              :class="{'opacity-50 cursor-not-allowed': !data.next_page_url}"
              :href="data.next_page_url"
            >
              <span class="sr-only">
                next
              </span>
              <i class="fa fa-chevron-right"></i>
            </a>
          </nav>
        </div>
        <div class="ml-4">
          <div class="my-3 flex justify-between items-end">
            <p class="text-sm text-gray-500">
              Showing {{ data.total }} data
            </p>
          </div>
        </div>
      </div>
    </div>
    <select
      id="item-per-page"
      v-model="perPage"
      autocomplete="item-per-page"
      class="
      focus:ring-sky-700 focus:border-sky-700
      py-1 pl-3 pr-6
      border-gray-300
      bg-transparent
      text-gray-500
      text-sm
      rounded-md
      "
      name="item-per-page"
    >
      <option
        v-for="option in paginationOptions"
        :key="option"
        :value="option"
      >
        {{ option }}
      </option>
    </select>
  </div>
</template>

<script setup lang="ts" generic="T">
import { computed, ref } from 'vue'
import { PaginationResponse } from '@/types/pagination';
import { router } from '@inertiajs/vue3';
import { watch } from 'vue';

interface Props {
  data: PaginationResponse<T>
}
const props = defineProps<Props>()
const emit = defineEmits(['page-changed'])

const pageRange = 2

const pages = computed(() => {
  const pages = []
  for (let i = rangeStart.value; i <= rangeEnd.value; i++) {
    pages.push(i)
  }
  return pages
})

const rangeStart = computed(() => {
  const start = props.data.current_page - pageRange
  return (start > 0) ? start : 1
})
const rangeEnd = computed(() => {
  const end = props.data.current_page + pageRange
  return (end < props.data.last_page) ? end : props.data.last_page
})

const hasFirst = computed(() => rangeStart.value !== 1)
const hasLast = computed(() => rangeEnd.value < props.data.last_page)

const paginationOptions = [10, 25, 50, 100, 500]
const perPage = ref(props.data.per_page)
watch(perPage, (val) => {
  router.get(route('menu.index'), {
    per_page: val
  })
})
</script>
