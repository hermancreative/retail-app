<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';

// Breadcrumbs are still accepted here (pages pass them via `defineOptions`)
// but are rendered inline next to each page's title via `PageHeader`,
// not in the topbar anymore.
defineProps<{
    breadcrumbs?: BreadcrumbItem[];
}>();
</script>

<template>
    <AppLayout>
        <slot />
    </AppLayout>
</template>
