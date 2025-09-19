<script setup lang="ts">
import type { Category } from "~/utils/types/models/Category";
import type { Tag } from "~/utils/types/models/Tag";
import type {AxiosResponse} from "axios";
import CreateCategory from "~/components/CreateCategory.vue";
const api = useNuxtApp().$api;
const tags = ref<Tag[]>([]);
const categories = ref<Category[]>([]);
const isHacker = ref<boolean>(false);

onMounted(() => {
  listTags();
})
function listTags() {
  api.get('/list-tags', {withCredentials: true})
      .then((response: AxiosResponse) => {
        tags.value = response.data;
        listCategories();
      })
      .catch(() => {
        isHacker.value = true;
      })
}

function listCategories() {
  api.get('/list-categories', {withCredentials: true})
  .then((response: AxiosResponse) => {
    categories.value = response.data;
  })
}

function destroyCategory(id: number | string) {
  api.delete(`/destroy-category/${id}`, {withCredentials: true})
      .then(() => {
        categories.value = categories.value.filter((c: Category) => c.id !== id);
      })
}

function destroyTag(id: number | string) {
  api.delete(`/destroy-tag/${id}`, {withCredentials: true})
      .then(() => {
        tags.value = tags.value.filter((c: Tag) => c.id !== id);
      })
}

function handleCreate(category: Category) {
  categories.value.push(category);
}
</script>

<template>
  <main>
    <div v-if="isHacker" class="font-extrabold text-9xl">
      You're one curious fella huh.
    </div>
    <div>
      <CreateCategory @created="handleCreate" />
    </div>
    <div>
      Categories: <br>
      <div v-for="category in categories" :key="category.id">
        {{ category.name }} <UButton @click="destroyCategory(category.id)">delete</UButton><br>
      </div>
    </div>
    <hr>
    <div>
      Tags: <br>
      <div v-for="tag in tags" :key="tag.id">
        {{ tag.name }}<UButton @click="destroyTag(tag.id)">delete</UButton><br>
      </div>
    </div>
  </main>
</template>