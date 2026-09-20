<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    Boxes,
    Database,
    LayoutGrid,
    Package,
    Ruler,
    ShoppingBag,
    Store,
    Truck,
    Users,
} from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard, orders } from '@/routes';
import { index as customersIndex } from '@/routes/customers';
import { index as itemCategoriesIndex } from '@/routes/item-categories';
import { index as itemsIndex } from '@/routes/items';
import { index as suppliersIndex } from '@/routes/suppliers';
import { index as unitsIndex } from '@/routes/units-of-measure';
import { index as warehousesIndex } from '@/routes/warehouses';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Orders',
        href: orders(),
        icon: ShoppingBag,
    },
    {
        title: 'Master Data',
        icon: Database,
        permission: 'manage-master-data',
        items: [
            {
                title: 'Units of Measure',
                href: unitsIndex(),
                icon: Ruler,
            },
            {
                title: 'Item Categories',
                href: itemCategoriesIndex(),
                icon: Boxes,
            },
            {
                title: 'Items',
                href: itemsIndex(),
                icon: Package,
            },
            {
                title: 'Suppliers',
                href: suppliersIndex(),
                icon: Truck,
            },
            {
                title: 'Customers',
                href: customersIndex(),
                icon: Users,
            },
            {
                title: 'Warehouses',
                href: warehousesIndex(),
                icon: Store,
            },
        ],
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader class="gap-0">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" label="Ecommerce" />
        </SidebarContent>
    </Sidebar>
    <slot />
</template>
