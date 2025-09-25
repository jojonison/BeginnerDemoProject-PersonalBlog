<script setup lang="ts">
import type { Article } from "~/utils/types/models/Article";
import type { AxiosResponse } from "axios";

const api = useNuxtApp().$api;
const route = useRoute();
const article = ref<Article | null>(null);

onMounted(() => {
  fetchArticle();
});

function fetchArticle(): void {
  api.get(`/show-article/${route.params.id}`, { withCredentials: true })
      .then((response: AxiosResponse<Article>) => {
        article.value = response.data;
      });
}
</script>

<template>
  <main v-if="article" class="m-2">
    <div class="flex flex-col">
      <NuxtLink :to="`/home`" class="hover:text-blue-600 hover:underline">↩️Back</NuxtLink>
      <span class="font-bold">Category: </span>{{article.category?.name}}
    </div>
    <h1 class="text-2xl font-bold">{{ article.title }}</h1>
    <p class="leading-relaxed whitespace-pre-line">{{ article.post }}</p>
    <hr/>
    <img v-if="article.image" :src="article.image" class="max-w-md" alt="caption" />
    <hr/>
    {{article.user.name}}
    {{article.user.email}}
  </main>
</template>
