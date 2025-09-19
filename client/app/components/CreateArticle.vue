<script setup lang="ts">
import * as z from "zod";
import type {AxiosResponse} from "axios";
import type {Article} from "~/utils/types/models/Article";
import type {Category} from "~/utils/types/models/Category";

const toast = useToast()
const api = useNuxtApp().$api;
const schema = z.object({
  title: z.string().min(1, 'Must be at least 8 characters'),
  post: z.string()
})
type Schema = z.output<typeof schema>
const state = reactive<Partial<Schema>>({
  title: undefined,
  post: undefined
})

const selectedCategory = ref<number | null>(null);
const tags = ref<string[]>([]);
const img = ref<File | null>(null);
const emit = defineEmits<{(event: 'created', article: Article): void;}>();
const props = defineProps<{categories: Category[];}>()

async function onSubmit() {
  const formData = new FormData();
  formData.append('title', state.title || "");
  formData.append('post', state.post || "");
  formData.append("category_id", selectedCategory.value?.toString() || "");
  tags.value?.forEach((tag, index) => {
    formData.append(`tags[${index}]`, tag);
  });

  if (img.value) {
    formData.append("image", img.value, img.value.name);
  }

  api.post('/create-article', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    },
    withCredentials: true
  })
      .then((response: AxiosResponse<Article>) => {
        toast.add({ title: 'Success', description: 'Article created successfully!', color: 'success' });
        emit('created', response.data);
        state.title = undefined;
        state.post = undefined;
        selectedCategory.value = null;
        tags.value = [];
        img.value = null;
      })
      .catch(() => {
        toast.add({ title: 'Error', description: 'Failed to create article', color: 'error' });
      });
}
</script>

<template>
  <UModal title="Create an Article" description="Write your magic">
    <UButton>Create an Article</UButton>
    <template #body>
      <UForm :schema="schema" :state="state" class="space-y-4 mt-4" @submit="onSubmit">
        <UFormField>
          <UInput placeholder="Title" v-model="state.title" />
        </UFormField>
        <UFormField>
          <UTextarea v-model="state.post" placeholder="Body" :rows="6" class="w-full"/>
        </UFormField>

        <div>
          <label class="block font-semibold">Category:</label>
          <select v-model="selectedCategory" class="outline outline-gray-700">
            <option disabled value="">-- Select a category --</option>
            <option v-for="category in props.categories" :key="category.id" :value="category.id"
                    class="text-black"
            >
              {{ category.name }}
            </option>
          </select>
        </div>

        <div>
          <label class="block font-semibold">Tags:</label>
          <UInputTags v-model="tags" placeholder="Enter tags..." />
        </div>
        <div>
          <label class="block font-semibold">Image:</label>
          <UFileUpload v-model="img" class="outline outline-gray-700"/>
        </div>
        <UButton type="submit">
          Submit
        </UButton>
      </UForm>
    </template>
  </UModal>
</template>