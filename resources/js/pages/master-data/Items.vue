<script setup lang="ts">
import { Form, Head, router } from '@inertiajs/vue3';
import { Pencil, Plus, Search, Trash2 } from '@lucide/vue';
import { computed, ref } from 'vue';
import ItemController from '@/actions/App/Http/Controllers/MasterData/ItemController';
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

type Category = { id: number; name: string };
type Unit = { id: number; name: string; symbol: string };

type Item = {
    id: number;
    sku: string;
    name: string;
    item_category_id: number | null;
    unit_of_measure_id: number | null;
    description: string | null;
    cost_price: string;
    selling_price: string;
    reorder_point: number;
    is_active: boolean;
    category: Category | null;
    unit_of_measure: Unit | null;
};

const props = defineProps<{
    items: Item[];
    categories: Category[];
    units: Unit[];
    filters: { search?: string };
}>();

const breadcrumbs = [
    { title: 'Master Data', href: itemsIndex() },
    { title: 'Items', href: itemsIndex() },
];

const search = ref(props.filters.search ?? '');

function onSearch() {
    router.get(
        itemsIndex().url,
        { search: search.value || undefined },
        { preserveState: true, replace: true },
    );
}

const dialogOpen = ref(false);
const editing = ref<Item | null>(null);

function openCreate() {
    editing.value = null;
    dialogOpen.value = true;
}

function openEdit(item: Item) {
    editing.value = item;
    dialogOpen.value = true;
}

const formBinding = computed(() =>
    editing.value
        ? ItemController.update.form(editing.value.id)
        : ItemController.store.form(),
);

function onSaved() {
    dialogOpen.value = false;
    editing.value = null;
}

function destroyItem(item: Item) {
    if (!confirm(`Delete item "${item.name}"?`)) {
        return;
    }

    router.delete(ItemController.destroy.url(item.id));
}

function stockStatusVariant(item: Item) {
    if (!item.is_active) {
        return 'secondary' as const;
    }

    return 'success' as const;
}
</script>

<template>
    <Head title="Items" />

    <div class="flex flex-1 flex-col gap-4 p-4 md:gap-6 md:p-6">
        <PageHeader title="Items" :breadcrumbs="breadcrumbs" />

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
                            placeholder="Search items..."
                            @change="onSearch"
                        />
                    </form>
                    <Button class="shrink-0" @click="openCreate">
                        <Plus class="size-4" />
                        Add Item
                    </Button>
                </div>
            </CardHeader>
            <CardContent class="p-0">
                <Table>
                    <TableHeader>
                        <TableRow class="hover:bg-transparent">
                            <TableHead>Item</TableHead>
                            <TableHead>SKU</TableHead>
                            <TableHead>Category</TableHead>
                            <TableHead>Unit</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-right">Price</TableHead>
                            <TableHead class="w-24" />
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="item in items" :key="item.id">
                            <TableCell class="font-medium">{{
                                item.name
                            }}</TableCell>
                            <TableCell class="font-mono text-xs">{{
                                item.sku
                            }}</TableCell>
                            <TableCell>{{
                                item.category?.name ?? '—'
                            }}</TableCell>
                            <TableCell>{{
                                item.unit_of_measure?.symbol ?? '—'
                            }}</TableCell>
                            <TableCell>
                                <Badge :variant="stockStatusVariant(item)">
                                    {{ item.is_active ? 'Active' : 'Inactive' }}
                                </Badge>
                            </TableCell>
                            <TableCell class="text-right font-medium">{{
                                item.selling_price
                            }}</TableCell>
                            <TableCell>
                                <div class="flex justify-end gap-1">
                                    <Button
                                        size="icon-sm"
                                        variant="ghost"
                                        @click="openEdit(item)"
                                    >
                                        <Pencil class="size-4" />
                                    </Button>
                                    <Button
                                        size="icon-sm"
                                        variant="ghost"
                                        @click="destroyItem(item)"
                                    >
                                        <Trash2
                                            class="text-destructive size-4"
                                        />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!items.length">
                            <TableCell
                                colspan="7"
                                class="text-muted-foreground py-8 text-center"
                            >
                                No items found.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </CardContent>
        </Card>

        <Dialog v-model:open="dialogOpen">
            <DialogContent class="sm:max-w-xl">
                <DialogHeader>
                    <DialogTitle>{{
                        editing ? 'Edit Item' : 'Add Item'
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
                            <Label for="sku">SKU</Label>
                            <Input
                                id="sku"
                                name="sku"
                                :default-value="editing?.sku"
                                required
                            />
                            <p
                                v-if="errors.sku"
                                class="text-destructive text-sm"
                            >
                                {{ errors.sku }}
                            </p>
                        </div>
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
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <Label for="item_category_id">Category</Label>
                            <select
                                id="item_category_id"
                                name="item_category_id"
                                :value="editing?.item_category_id ?? ''"
                                class="border-input focus-visible:border-ring focus-visible:ring-ring/50 h-9 w-full rounded-md border bg-transparent px-3 text-sm shadow-xs outline-none focus-visible:ring-[3px]"
                            >
                                <option value="">None</option>
                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>
                        <div class="grid gap-2">
                            <Label for="unit_of_measure_id">Unit</Label>
                            <select
                                id="unit_of_measure_id"
                                name="unit_of_measure_id"
                                :value="editing?.unit_of_measure_id ?? ''"
                                class="border-input focus-visible:border-ring focus-visible:ring-ring/50 h-9 w-full rounded-md border bg-transparent px-3 text-sm shadow-xs outline-none focus-visible:ring-[3px]"
                            >
                                <option value="">None</option>
                                <option
                                    v-for="unit in units"
                                    :key="unit.id"
                                    :value="unit.id"
                                >
                                    {{ unit.name }} ({{ unit.symbol }})
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="grid gap-2">
                            <Label for="cost_price">Cost Price</Label>
                            <Input
                                id="cost_price"
                                type="number"
                                step="0.01"
                                min="0"
                                name="cost_price"
                                :default-value="editing?.cost_price ?? 0"
                                required
                            />
                            <p
                                v-if="errors.cost_price"
                                class="text-destructive text-sm"
                            >
                                {{ errors.cost_price }}
                            </p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="selling_price">Selling Price</Label>
                            <Input
                                id="selling_price"
                                type="number"
                                step="0.01"
                                min="0"
                                name="selling_price"
                                :default-value="editing?.selling_price ?? 0"
                                required
                            />
                            <p
                                v-if="errors.selling_price"
                                class="text-destructive text-sm"
                            >
                                {{ errors.selling_price }}
                            </p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="reorder_point">Reorder Point</Label>
                            <Input
                                id="reorder_point"
                                type="number"
                                min="0"
                                name="reorder_point"
                                :default-value="editing?.reorder_point ?? 0"
                            />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="description">Description</Label>
                        <textarea
                            id="description"
                            name="description"
                            :default-value="editing?.description ?? ''"
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
                            {{ editing ? 'Save Changes' : 'Create Item' }}
                        </Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>
    </div>
</template>
