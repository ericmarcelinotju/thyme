<template>
  <div class="flex-auto min-h-0 flex flex-col">
    <div class="mb-3 flex justify-between items-end">
      <div class="inset-y-0 left-0 flex items-center" />
      <div
        v-if="hasSelection"
        class="right-0 flex items-center"
      >
        <slot name="selection-action" />
      </div>
    </div>
    <div class="align-middle min-w-full overflow-x-auto rounded-md shadow">
      <table
        class="min-w-full divide-y divide-gray-200"
        :style="tableStyle"
      >
        <thead
          class="bg-slate-100 outline outline-1 outline-gray-300 sticky top-0"
        >
          <tr>
            <th v-if="hasSelection">
              <div>
                <input
                  class="default-checkbox"
                  style="margin-right: 0"
                  type="checkbox"
                  @input="onSelectionInput"
                >
              </div>
            </th>
            <th v-if="hasDetail">
              <!-- Leave empty for header -->
            </th>
            <th
              v-for="column in filterColumns"
              :key="column.key"
              class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 whitespace-nowrap"
              :class="column.class"
              rowspan="3"
              scope="col"
              :title="column.label"
              :width="column.width"
              @click="setSort(column)"
            >
              <div>
                <span class="mr-1">
                  {{ column.label }}
                </span>
                <Component
                  :is="getSortingComponent(column)"
                  v-if="column.isSortable"
                  class="inline h-5 w-5 cursor-pointer"
                />
              </div>
            </th>
            <th
              v-if="hasEdit || hasDelete"
              class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 whitespace-nowrap text-center w-1"
            />
          </tr>
        </thead>
        <tbody>
          <template v-if="hasItems">
            <template
              v-for="(item, index) in items"
              :key="item.id"
            >
              <tr
                class="even:bg-slate-100"
                :class="{ 'cursor-pointer': isClickable }"
                @click="onRowClick(item)"
              >
                <td v-if="hasDetail">
                  <a
                    class="cursor-pointer"
                    @click="onToogleDetail(index, item)"
                  >
                    <i v-if="!isDetailOpens[index]" class="fa fa-chevron-down"></i>
                    <i v-else class="fa fa-chevron-up"></i>
                  </a>
                </td>
                <td
                  v-for="column in filterColumns"
                  :key="column.key"
                  class="px-4 py-2 whitespace-nowrap text-sm text-grey-dark"
                  :class="column.class"
                >
                  <template v-if="$slots[column.key as string]">
                    <slot
                      :item="item"
                      :name="column.key"
                    />
                  </template>
                  <template v-else>
                    <span>
                      {{ item && item[column.key] }}
                    </span>
                  </template>
                </td>
                <td
                  v-if="hasEdit || hasDelete"
                  class="flex text-center"
                >
                  <slot
                    :item="item"
                    name="action"
                  />
                  <a
                    v-if="hasEdit"
                    class="cursor-pointer text-amber-700 p-2 hover:text-amber-900"
                    @click="onEdit(item)"
                  >
                    <i class="fa fa-pencil mr-1"></i>

                  </a>
                  <a
                    v-if="hasDelete"
                    class="cursor-pointer text-rose-700 p-2 hover:text-rose-900"
                    @click="onDelete(item)"
                  >
                    <i class="fa fa-trash mr-1"></i>
                  </a>
                </td>
              </tr>
              <tr v-if="hasDetail && isDetailOpens[index]">
                <td colspan="100%">
                  <slot
                    :item="item"
                    name="detail"
                  />
                </td>
              </tr>
            </template>
          </template>
          <template v-else>
            <tr>
              <td :colspan="columns.length">
                <div class="text-center p-12">
                  <i class="fa fa-cloud mx-auto text-lg text-gray-400"></i>
                  <h3 class="mt-2 text-sm font-medium text-gray-900">
                    Data is empty
                  </h3>
                  <p class="mt-1 text-sm text-gray-500">
                    Test
                  </p>
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts" generic="T">
import { computed, watch, nextTick, useSlots, ref, Ref } from 'vue'
import { TableColumn } from '@/types/table.type'

interface Props {
  name?: string
  height?: number
  columns: TableColumn<T>[]
  items: T[]
  hasSearch?: boolean
  hasSelection?: boolean
  hasEdit?: boolean
  hasDelete?: boolean
  isClickable?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  name: 'Data',
  height: 0,
  hasSearch: true,
  hasEdit: true,
  hasDelete: true
})
const emit = defineEmits([
  'search',
  'selection',
  'detail',
  'delete',
  'edit',
  'rowClick'
])

const slots = useSlots()

type SortDirection = 'asc' | 'desc'

class Sort {
  by: string | undefined
  direction: SortDirection | undefined
}

const sort: Ref<Sort> = ref({
  by: undefined,
  direction: undefined
})

const tableStyle = computed(() => ({ minHeight: `${props.height}px` }))
const hasItems = computed(() => props.items && props.items.length > 0)

const filterColumns = computed(() =>
  props.columns.filter((column) => !column.isHidden)
)
watch(
  () => filterColumns,
  (val) => {
    nextTick(() => setSort(val.value[0], 'desc'))
  },
  { immediate: true }
)

const getSortingComponent = (column: TableColumn<T>) => {
  if (!column.isSortable) return ''
  if (column.key === sort.value.by) {
    if (sort.value.direction === 'asc') {
      return 'ArrowSmDownIcon'
    }
    return 'ArrowSmUpIcon'
  }
  return 'SwitchVerticalIcon'
}

const setSort = (column: TableColumn<T>, direction: SortDirection = 'asc') => {
  if (column.isSortable) {
    sort.value.by = column.key as string
    if (direction) {
      sort.value.direction = direction
    } else {
      if (sort.value.by === column.key && sort.value.direction === 'asc') {
        sort.value.direction = 'asc'
      } else {
        sort.value.direction = 'desc'
      }
    }
  }
}

const onSelectionInput = (event: Event) => {
  emit('selection', (<HTMLInputElement>event.target).checked)
}

const onEdit = (item: T) => {
  emit('edit', item)
}

const onDelete = (item: T) => {
  emit('delete', item)
}

const hasDetail = computed(() => !!slots.detail)
const isDetailOpens: Ref<boolean[]> = ref([])
const onToogleDetail = (index: number, item: T) => {
  isDetailOpens.value[index] = !isDetailOpens.value[index]
  emit('detail', isDetailOpens.value[index], index, item)
}

const onRowClick = (item: T) => {
  emit('rowClick', item)
}
</script>
