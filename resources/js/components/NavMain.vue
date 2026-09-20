<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronRight } from '@lucide/vue';
import { computed, ref, watch } from 'vue';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { Auth, NavItem } from '@/types';

const props = withDefaults(
    defineProps<{
        items: NavItem[];
        label?: string;
    }>(),
    {
        label: 'Ecommerce',
    },
);

const { currentUrl, isCurrentUrl } = useCurrentUrl();

const page = usePage<{ auth: Auth }>();

function hasPermission(item: NavItem): boolean {
    return (
        !item.permission ||
        page.props.auth.permissions.includes(item.permission)
    );
}

function filterVisible(items: NavItem[]): NavItem[] {
    return items.flatMap((item) => {
        if (!hasPermission(item)) {
            return [];
        }

        if (!item.items?.length) {
            return [item];
        }

        const children = filterVisible(item.items);

        return children.length ? [{ ...item, items: children }] : [];
    });
}

const visibleItems = computed(() => filterVisible(props.items));

function hasActiveChild(item: NavItem): boolean {
    return (item.items ?? []).some(
        (child) =>
            (child.href !== undefined && isCurrentUrl(child.href)) ||
            hasActiveChild(child),
    );
}

const openMenus = ref<Record<string, boolean>>({});

watch(
    [visibleItems, currentUrl],
    () => {
        for (const item of visibleItems.value) {
            if (item.items?.length && hasActiveChild(item)) {
                openMenus.value[item.title] = true;
            }
        }
    },
    { immediate: true },
);
</script>

<template>
    <SidebarGroup v-if="visibleItems.length" class="px-2 py-0">
        <SidebarGroupLabel>{{ label }}</SidebarGroupLabel>
        <SidebarMenu>
            <template v-for="item in visibleItems" :key="item.title">
                <SidebarMenuItem v-if="item.items?.length">
                    <Collapsible
                        v-model:open="openMenus[item.title]"
                        class="group/collapsible"
                    >
                        <CollapsibleTrigger as-child>
                            <SidebarMenuButton
                                :is-active="hasActiveChild(item)"
                                :tooltip="item.title"
                            >
                                <component :is="item.icon" />
                                <span>{{ item.title }}</span>
                                <ChevronRight
                                    class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                                />
                            </SidebarMenuButton>
                        </CollapsibleTrigger>
                        <CollapsibleContent>
                            <SidebarMenuSub>
                                <SidebarMenuSubItem
                                    v-for="subItem in item.items"
                                    :key="subItem.title"
                                >
                                    <SidebarMenuSubButton
                                        v-if="subItem.href"
                                        as-child
                                        :is-active="isCurrentUrl(subItem.href)"
                                    >
                                        <Link :href="subItem.href">
                                            <component
                                                v-if="subItem.icon"
                                                :is="subItem.icon"
                                            />
                                            <span>{{ subItem.title }}</span>
                                        </Link>
                                    </SidebarMenuSubButton>
                                </SidebarMenuSubItem>
                            </SidebarMenuSub>
                        </CollapsibleContent>
                    </Collapsible>
                </SidebarMenuItem>

                <SidebarMenuItem v-else-if="item.href">
                    <SidebarMenuButton
                        as-child
                        :is-active="isCurrentUrl(item.href)"
                        :tooltip="item.title"
                    >
                        <Link :href="item.href">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>
