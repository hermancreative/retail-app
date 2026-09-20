<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import type { BreadcrumbItem } from '@/types';

withDefaults(
    defineProps<{
        title: string;
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);
</script>

<template>
    <!-- Page title box, mirrors UBold's page-title-box: title left, breadcrumb right -->
    <div
        class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between"
    >
        <h1 class="text-xl font-semibold tracking-tight">{{ title }}</h1>
        <Breadcrumbs v-if="breadcrumbs.length" :breadcrumbs="breadcrumbs" />
    </div>
</template>
