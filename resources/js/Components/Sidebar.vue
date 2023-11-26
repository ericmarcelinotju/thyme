<script setup lang="ts">
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, usePage } from '@inertiajs/vue3';

const isCollapse = ref(false);
const onCollapse = () => {
    isCollapse.value = !isCollapse.value
}

const menus = usePage().props.menus
</script>

<template>
  <div
    class="hidden lg:flex flex-col w-72 bg-white inset-y-0 shadow-lg transition-all ease-out"
    :class="{ '!w-24': isCollapse }"
  >
      <!-- Sidebar component, swap this element with another sidebar if you like -->
      <div class="flex flex-col flex-grow shadow-lg overflow-y-auto">
          <div class="flex items-center justify-between px-6 flex-shrink-0 h-16">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
              <Link :href="route('dashboard')">
                  <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800"/>
              </Link>
            </div>
            <button>
              <svg
                class="ms-2 -me-0.5 h-6 w-6"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
                @click="onCollapse"
              >
                <path
                  fill-rule="evenodd"
                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                  clip-rule="evenodd"
                />
              </svg>
            </button>
          </div>
          <nav
            aria-label="Sidebar"
            class="flex-1 flex flex-col divide-y divide-grey-dark overflow-y-auto"
          >
            <div class="space-y-1">
              <template v-for="menu in menus" :key="menu.id">
                <Link
                  v-if="menu.type === 'link' && menu.route"
                  class="flex px-6 py-3 hover:bg-indigo-400 hover:text-white"
                  :class="{'bg-indigo-300': route().current(menu.route)}"
                  :href="route(menu.route)"
                >
                    <span v-if="menu.icon" v-html="menu.icon" class="mr-4"></span>
                    <span>{{ menu.label }}</span>
                </Link>
                <div v-else-if="menu.type === 'title'" class="px-6 pt-2 text-sm font-bold">{{ menu.label }}</div>
              </template>
            </div>
          </nav>
      </div>
  </div>
</template>