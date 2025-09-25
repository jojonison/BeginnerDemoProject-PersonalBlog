<script setup lang="ts">
import type {Article} from "~/utils/types/models/Article";
import type {AxiosResponse} from "axios";
const api = useNuxtApp().$api;

const articles = ref<Article[]>([]);
const nextPageUrl = ref<string|null>(null);
onMounted(() => {
  listArticles('/list-articles');
});
function listArticles(url: string): void {
  api.get(url, { withCredentials: true })
      .then((response: AxiosResponse<{ data: Article[], links: { next: string|null } }>) => {
        articles.value.push(...response.data.data);
        nextPageUrl.value = response.data.links.next;
      });
}

function loadMore(): void {
  if (nextPageUrl.value) {
    listArticles(nextPageUrl.value);
  }
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
    <div v-if="nextPageUrl" class="mt-4">
      <UButton color="primary" @click="loadMore">Load More</UButton>
    </div>
  </main>
</template>