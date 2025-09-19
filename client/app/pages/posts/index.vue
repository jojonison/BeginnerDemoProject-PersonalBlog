<script setup lang="ts">
import type {AxiosResponse} from "axios";
import type {Article} from "~/utils/types/models/Article";
import type {Category} from "~/utils/types/models/Category";
import EditArticle from "~/components/EditArticle.vue";
import CreateArticle from "~/components/CreateArticle.vue";

const api = useNuxtApp().$api;
const articles = ref<Article[]>([]);
const categories = ref<Category[]>([]);
const isHacker = ref<boolean>(false);
onMounted(() => {
  myArticles();
  listCategories();
});
function myArticles(): void {
  api.get('/my-articles', {withCredentials: true})
      .then((response: AxiosResponse<Article[]>) => {
        articles.value = response.data;
      })
      .catch(() => {
        isHacker.value = true;
      })
}
function destroy(article: Article) {
  api.delete(`/destroy-article/${article.id}`, {withCredentials: true})
      .then(() => {
        articles.value = articles.value.filter((item: Article) => item.id !== article.id)
      })
}
function listCategories() {
  api.get('/list-categories', {withCredentials: true})
      .then((response: AxiosResponse<Category[]>) => {
        categories.value = response.data
      });
}
function handleUpdated(updated: Article) {
  const idx = articles.value.findIndex(a => a.id === updated.id);
  if (idx !== -1) {
    articles.value[idx] = updated;
  }
}
function handleCreated(created: Article) {
  articles.value.unshift(created)
}
</script>

<template>
  <main class="p-4">
    <div>
      <!-- Hacker warning -->
      <div v-if="isHacker" class="font-extrabold text-9xl">
        You're one curious fella huh.
      </div>
      <!-- Add Article Form -->
      <CreateArticle :categories="categories" @created="handleCreated"/>
      <!-- Articles Table -->
      <div class="mt-6 overflow-x-auto">
        <table class="min-w-full border-collapse border">
          <thead>
            <tr>
              <th class="px-4 py-2 border border-gray-700 text-left">Title</th>
              <th class="px-4 py-2 border border-gray-700 text-left">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="article in articles" :key="article.id">
              <td class="px-4 py-2 border border-gray-700">
                {{ article.title }}
              </td>
              <td class="px-4 py-2 border border-gray-700 space-x-2">
                <EditArticle :article="article" :categories="categories" @updated="handleUpdated"/>
                <UButton size="sm" @click="destroy(article)">
                  delete
                </UButton>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</template>
