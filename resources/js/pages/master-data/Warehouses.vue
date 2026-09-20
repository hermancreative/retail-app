<script setup lang="ts">
import { Form, Head, router } from '@inertiajs/vue3';
import { Pencil, Plus, Search, Trash2 } from '@lucide/vue';
import { computed, ref } from 'vue';
import WarehouseController from '@/actions/App/Http/Controllers/MasterData/WarehouseController';
import PageHeader from '@/components/PageHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { index as itemsIndex } from '@/routes/items';
import { index as warehousesIndex } from '@/routes/warehouses';

type Warehouse = {
    id: number;
    name: string;
    code: string;
    type: 'store' | 'central_warehouse';
    city: string | null;
    address: string | null;
    phone: string | null;
    manager_name: string | null;
    is_active: boolean;
};

const props = defineProps<{
    warehouses: Warehouse[];
    filters: { search?: string };
}>();

const breadcrumbs = [
    { title: 'Master Data', href: itemsIndex() },
    { title: 'Warehouses', href: warehousesIndex() },
];

const search = ref(props.filters.search ?? '');

function onSearch() {
    router.get(
        warehousesIndex().url,
        { search: search.value || undefined },
        { preserveState: true, replace: true },
    );
}

const dialogOpen = ref(false);
const editing = ref<Warehouse | null>(null);

function openCreate() {
    editing.value = null;
    dialogOpen.value = true;
}

function openEdit(warehouse: Warehouse) {
    editing.value = warehouse;
    dialogOpen.value = true;
}

const formBinding = computed(() =>
    editing.value
        ? WarehouseController.update.form(editing.value.id)
        : WarehouseController.store.form(),
);

function onSaved() {
    dialogOpen.value = false;
    editing.value = null;
}

function destroyWarehouse(warehouse: Warehouse) {
    if (!confirm(`Delete warehouse "${warehouse.name}"?`)) {
        return;
    }

    router.delete(WarehouseController.destroy.url(warehouse.id));
}
</script>

<template>
    <Head title="Warehouses" />

    <div class="flex flex-1 flex-col gap-4 p-4 md:gap-6 md:p-6">
        <PageHeader title="Warehouses" :breadcrumbs="breadcrumbs" />

        <Card class="gap-0 overflow-hidden py-0 shadow-sm">
            <CardHeader class="border-b py-5">
                <div
                    class="flex w-full flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                >
                    <form
                        class="relative w-full sm:max-w-xs"
                        @submit.prevent="onSearch"
                    >
                        <Search
                            class="text-muted-foreground absolute top-1/2 left-2.5 size-4 -translate-y-1/2"
                        />
                        <Input
                            v-model="search"
                            class="pl-8"
                            placeholder="Search warehouses..."
                            @change="onSearch"
                        />
                    </form>
                    <Button class="shrink-0" @click="openCreate">
                        <Plus class="size-4" />
                        Add Warehouse
                    </Button>
                </div>
            </CardHeader>
            <CardContent class="p-0">
                <Table>
                    <TableHeader>
                        <TableRow class="hover:bg-transparent">
                            <TableHead>Warehouse</TableHead>
                            <TableHead>Code</TableHead>
                            <TableHead>Type</TableHead>
                            <TableHead>Manager</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="w-24" />
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="warehouse in warehouses"
                            :key="warehouse.id"
                        >
                            <TableCell>
                                <div>
                                    <p class="font-medium">
                                        {{ warehouse.name }}
                                    </p>
                                    <p
                                        v-if="warehouse.city"
                                        class="text-muted-foreground text-xs"
                                    >
                                        {{ warehouse.city }}
                                    </p>
                                </div>
                            </TableCell>
                            <TableCell class="font-mono text-xs">{{
                                warehouse.code
                            }}</TableCell>
                            <TableCell>
                                <Badge
                                    :variant="
                                        warehouse.type === 'central_warehouse'
                                            ? 'info'
                                            : 'secondary'
                                    "
                                >
                                    {{
                                        warehouse.type === 'central_warehouse'
                                            ? 'Central Warehouse'
                                            : 'Store'
                                    }}
                                </Badge>
                            </TableCell>
                            <TableCell>{{
                                warehouse.manager_name ?? '—'
                            }}</TableCell>
                            <TableCell>
                                <Badge
                                    :variant="
                                        warehouse.is_active
                                            ? 'success'
                                            : 'secondary'
                                    "
                                >
                                    {{
                                        warehouse.is_active
                                            ? 'Active'
                                            : 'Inactive'
                                    }}
                                </Badge>
                            </TableCell>
                            <TableCell>
                                <div class="flex justify-end gap-1">
                                    <Button
                                        size="icon-sm"
                                        variant="ghost"
                                        @click="openEdit(warehouse)"
                                    >
                                        <Pencil class="size-4" />
                                    </Button>
                                    <Button
                                        size="icon-sm"
                                        variant="ghost"
                                        @click="destroyWarehouse(warehouse)"
                                    >
                                        <Trash2
                                            class="text-destructive size-4"
                                        />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!warehouses.length">
                            <TableCell
                                colspan="6"
                                class="text-muted-foreground py-8 text-center"
                            >
                                No warehouses found.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </CardContent>
        </Card>

        <Dialog v-model:open="dialogOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{{
                        editing ? 'Edit Warehouse' : 'Add Warehouse'
                    }}</DialogTitle>
                </DialogHeader>

                <Form
                    v-bind="formBinding"
                    class="space-y-4"
                    v-slot="{ errors, processing }"
                    @success="onSaved"
                >
                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <Label for="name">Name</Label>
                            <Input
                                id="name"
                                name="name"
                                :default-value="editing?.name"
                                required
                            />
                            <p
                                v-if="errors.name"
                                class="text-destructive text-sm"
                            >
                                {{ errors.name }}
                            </p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="code">Code</Label>
                            <Input
                                id="code"
                                name="code"
                                :default-value="editing?.code"
                                required
                            />
                            <p
                                v-if="errors.code"
                                class="text-destructive text-sm"
                            >
                                {{ errors.code }}
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="type">Type</Label>
                        <select
                            id="type"
                            name="type"
                            :value="editing?.type ?? 'store'"
                            class="border-input focus-visible:border-ring focus-visible:ring-ring/50 h-9 w-full rounded-md border bg-transparent px-3 text-sm shadow-xs outline-none focus-visible:ring-[3px]"
                        >
                            <option value="store">Store</option>
                            <option value="central_warehouse">
                                Central Warehouse
                            </option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <Label for="city">City</Label>
                            <Input
                                id="city"
                                name="city"
                                :default-value="editing?.city ?? ''"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="phone">Phone</Label>
                            <Input
                                id="phone"
                                name="phone"
                                :default-value="editing?.phone ?? ''"
                            />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="manager_name">Manager</Label>
                        <Input
                            id="manager_name"
                            name="manager_name"
                            :default-value="editing?.manager_name ?? ''"
                        />
                    </div>

                    <div class="grid gap-2">
                        <Label for="address">Address</Label>
                        <textarea
                            id="address"
                            name="address"
                            :default-value="editing?.address ?? ''"
                            rows="2"
                            class="border-input focus-visible:border-ring focus-visible:ring-ring/50 flex w-full rounded-md border bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:ring-[3px]"
                        />
                    </div>

                    <label class="flex items-center gap-2 text-sm">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            :checked="editing ? editing.is_active : true"
                            class="border-input size-4 rounded"
                        />
                        Active
                    </label>

                    <DialogFooter>
                        <Button type="submit" :disabled="processing">
                            {{ editing ? 'Save Changes' : 'Create Warehouse' }}
                        </Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>
    </div>
</template>
