<template>
  <div class="flex justify-between items-center mt-4">
    <div class="bg-white flex items-center justify-between">
      <div class="flex-1 flex justify-between sm:hidden">
        <button
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
            disabled:opacity-50 disabled:cursor-not-allowed
            rounded-l-md
          "
          :disabled="!hasPrev"
          @click.prevent="changePage(prevPage)"
        >
          prev
        </button>
        <button
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
            disabled:opacity-50 disabled:cursor-not-allowed
            rounded-r-md
          "
          :disabled="!hasNext"
          @click.prevent="changePage(nextPage)"
        >
          next
        </button>
      </div>
      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
          <nav
            aria-label="Pagination"
            class="relative z-0 inline-flex shadow-sm -space-x-px"
          >
            <button
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
                disabled:opacity-50 disabled:cursor-not-allowed
                rounded-l-md
              "
              :disabled="!hasPrev"
              @click.prevent="changePage(prevPage)"
            >
              <span class="sr-only">
                prev
              </span>
              <i class="fa fa-chevron-left"></i>
            </button>
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
              href="#"
              @click.prevent="changePage(1)"
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
              href="#"
              @click.prevent="changePage(page)"
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
              href="#"
              @click.prevent="changePage(totalPages)"
            >
              {{ totalPages }}
            </a>
            <button
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
                disabled:opacity-50 disabled:cursor-not-allowed
                rounded-r-md
              "
              :disabled="!hasNext"
              @click.prevent="changePage(nextPage)"
            >
              <span class="sr-only">
                next
              </span>
              <i class="fa fa-chevron-right"></i>
            </button>
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
      v-model="pagination.limit"
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
import { PaginationResponse } from '@/types/pagination.type';
import { computed, ref } from 'vue'

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
  return (end < totalPages.value) ? end : totalPages.value
})

const totalPages = computed(() => Math.ceil(props.data.total / props.data.per_page))

const nextPage = computed(() => props.data.current_page + 1)
const prevPage = computed(() => props.data.current_page - 1)

const hasFirst = computed(() => rangeStart.value !== 1)
const hasLast = computed(() => rangeEnd.value < totalPages.value)
const hasPrev = computed(() => props.data.current_page > 1)
const hasNext = computed(() => props.data.current_page < totalPages.value)

const changePage = (page: number) => {
  if (page > 0 && page <= totalPages.value) {
    emit('page-changed', page)
  }
}

const paginationOptions = ref([10, 25, 50, 100, 500])
const pagination = ref({
  page: 1,
  limit: paginationOptions.value[0]
})
</script>
