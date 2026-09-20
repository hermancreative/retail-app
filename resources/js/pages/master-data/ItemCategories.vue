<script setup lang="ts">
import { Form, Head, router } from '@inertiajs/vue3';
import { Pencil, Plus, Search, Trash2 } from '@lucide/vue';
import { computed, ref } from 'vue';
import ItemCategoryController from '@/actions/App/Http/Controllers/MasterData/ItemCategoryController';
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
import { index as itemCategoriesIndex } from '@/routes/item-categories';
import { index as itemsIndex } from '@/routes/items';

type ItemCategory = {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    is_active: boolean;
    items_count: number;
};

const props = defineProps<{
    categories: ItemCategory[];
    filters: { search?: string };
}>();

const breadcrumbs = [
    { title: 'Master Data', href: itemsIndex() },
    { title: 'Item Categories', href: itemCategoriesIndex() },
];

const search = ref(props.filters.search ?? '');

function onSearch() {
    router.get(
        itemCategoriesIndex().url,
        { search: search.value || undefined },
        { preserveState: true, replace: true },
    );
}

const dialogOpen = ref(false);
const editing = ref<ItemCategory | null>(null);

function openCreate() {
    editing.value = null;
    dialogOpen.value = true;
}

function openEdit(category: ItemCategory) {
    editing.value = category;
    dialogOpen.value = true;
}

const formBinding = computed(() =>
    editing.value
        ? ItemCategoryController.update.form(editing.value.id)
        : ItemCategoryController.store.form(),
);

function onSaved() {
    dialogOpen.value = false;
    editing.value = null;
}

function destroyCategory(category: ItemCategory) {
    if (!confirm(`Delete category "${category.name}"?`)) {
        return;
    }

    router.delete(ItemCategoryController.destroy.url(category.id));
}
</script>

<template>
    <Head title="Item Categories" />

    <div class="flex flex-1 flex-col gap-4 p-4 md:gap-6 md:p-6">
        <PageHeader title="Item Categories" :breadcrumbs="breadcrumbs" />

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
                            placeholder="Search categories..."
                            @change="onSearch"
                        />
                    </form>
                    <Button class="shrink-0" @click="openCreate">
                        <Plus class="size-4" />
                        Add Category
                    </Button>
                </div>
            </CardHeader>
            <CardContent class="p-0">
                <Table>
                    <TableHeader>
                        <TableRow class="hover:bg-transparent">
                            <TableHead>Name</TableHead>
                            <TableHead>Slug</TableHead>
                            <TableHead>Items</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="w-24" />
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="category in categories"
                            :key="category.id"
                        >
                            <TableCell class="font-medium">{{
                                category.name
                            }}</TableCell>
                            <TableCell
                                class="text-muted-foreground font-mono text-xs"
                                >{{ category.slug }}</TableCell
                            >
                            <TableCell>{{ category.items_count }}</TableCell>
                            <TableCell>
                                <Badge
                                    :variant="
                                        category.is_active
                                            ? 'success'
                                            : 'secondary'
                                    "
                                >
                                    {{
                                        category.is_active
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
                                        @click="openEdit(category)"
                                    >
                                        <Pencil class="size-4" />
                                    </Button>
                                    <Button
                                        size="icon-sm"
                                        variant="ghost"
                                        @click="destroyCategory(category)"
                                    >
                                        <Trash2
                                            class="text-destructive size-4"
                                        />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!categories.length">
                            <TableCell
                                colspan="5"
                                class="text-muted-foreground py-8 text-center"
                            >
                                No categories found.
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
                        editing ? 'Edit Category' : 'Add Category'
                    }}</DialogTitle>
                </DialogHeader>

                <Form
                    v-bind="formBinding"
                    class="space-y-4"
                    v-slot="{ errors, processing }"
                    @success="onSaved"
                >
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            name="name"
                            :default-value="editing?.name"
                            required
                        />
                        <p v-if="errors.name" class="text-destructive text-sm">
                            {{ errors.name }}
                        </p>
                    </div>

                    <div class="grid gap-2">
                        <Label for="description">Description</Label>
                        <textarea
                            id="description"
                            name="description"
                            :default-value="editing?.description ?? ''"
                            rows="3"
                            class="border-input focus-visible:border-ring focus-visible:ring-ring/50 flex w-full rounded-md border bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:ring-[3px]"
                        />
                        <p
                            v-if="errors.description"
                            class="text-destructive text-sm"
                        >
                            {{ errors.description }}
                        </p>
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
                            {{ editing ? 'Save Changes' : 'Create Category' }}
                        </Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>
    </div>
</template>
