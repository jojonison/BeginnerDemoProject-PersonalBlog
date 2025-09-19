<script setup lang="ts">
import * as z from 'zod';
import type {Article} from "~/utils/types/models/Article";
import type { Category } from "~/utils/types/models/Category";
import type {Tag} from "~/utils/types/models/Tag";
import type {AxiosResponse} from "axios";
const api = useNuxtApp().$api;
const schema = z.object({
  title: z.string().min(1, 'Must not be empty'),
  post: z.string()
})
const props = defineProps<{
  article: Article,
  categories: Category[],
}>();
const toast = useToast();
type Schema = z.output<typeof schema>;
const state = reactive<Partial<Schema>>({
  title: props.article.title,
  post: props.article.post,
});
const selectedCategory = ref<string | number | null>(props.article.category?.id ?? null);
const tags = ref<string[]>(props.article.tags?.map((t: Tag) => t.name) ?? []);
const img = ref<File | null>(null);
const emit = defineEmits<{
  (event: 'updated', article: Article): void;
}>();
const isOpen = ref(false);
function onSubmit() {
  const formData = new FormData();
  formData.append("title", state.title ?? "");
  formData.append("post", state.post ?? "");
  formData.append("category_id", selectedCategory.value?.toString() || "");

  tags.value.forEach((tag, index) => {
    formData.append(`tags[${index}]`, tag);
  });

  if (img.value) {
    formData.append("image", img.value, img.value.name);
  }

  api
      .post(`/update-article/${props.article.id}`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
        withCredentials: true,
      })
      .then((response: AxiosResponse) => {
        toast.add({
          title: "Edited",
          description: "You changed the magic",
          color: "success",
        });
        emit('updated', response.data);
        isOpen.value = false;
      })
      .catch(() => {
        toast.add({
          title: "Error",
          description: "Update failed",
          color: "error",
        });
      });
}
const currentCategoryName = computed(() => {
  return props.article.category?.name
      || props.categories.find(c => c.id === selectedCategory.value)?.name
      || "None";
});
</script>

<template>
  <UModal v-model="isOpen" title="Edit" description="Edit the magic">
    <UButton size="sm" @click="isOpen = true">edit</UButton>
    <template #body>
      <UForm :schema="schema" :state="state" class="space-y-4 mt-4" @submit="onSubmit">
        <UFormField>
          <UInput :default-value=props.article.title v-model="state.title"/>
        </UFormField>
        <UFormField>
          <UTextarea
              v-model="state.post"
              :default-value="props.article.post"
              :rows="6"
              class="w-full"
          />
        </UFormField>

        <div>
          <label class="block font-semibold">Category:</label>
          <select v-model="selectedCategory" class="outline outline-gray-700 rounded-sm">
            <option disabled value="">Current Category: {{ currentCategoryName }}</option>
            <option
                v-for="category in categories"
                :key="category.id"
                :value="category.id"
                class="text-black"
            >
              {{ category.name }}
            </option>
          </select>
        </div>

        <div>
          <label class="block font-semibold">Tags:</label>
          <UInputTags v-model="tags" placeholder="Enter tags..."/>
        </div>
        <div>
          <label class="block font-semibold">Image:</label>
          <UFileUpload v-model="img" class="outline outline-gray-700" />
        </div>
        <UButton type="submit">
          Submit
        </UButton>
      </UForm>
    </template>
  </UModal>
</template>