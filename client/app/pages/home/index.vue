<script setup lang="ts">
import type {Article} from "~/utils/types/models/Article";
import type {AxiosResponse} from "axios";
const api = useNuxtApp().$api;

const articles = ref<Article[]>([]);

onMounted(() => {
  listArticles();
});
function listArticles(): void {
  api.get('/list-articles', { withCredentials: true })
      .then((response:AxiosResponse<Article[]>) => {
        articles.value = response.data;
      })
}
</script>

<template>
  <main>
    <div v-for="article in articles" :key="article.id">
      <div>
        Title: {{ article.title }}
        Category: {{ article.category.name }}
        <div v-for="tag in article.tags" :key="tag.id">
          <UBadge color="neutral">{{ tag.name }}</UBadge>
        </div>
        <NuxtLink :to="`/articles/${article.id}`" class="underline hover:text-blue-600">
          Read More
        </NuxtLink>
        <hr/>
      </div>
    </div>
  </main>
</template>