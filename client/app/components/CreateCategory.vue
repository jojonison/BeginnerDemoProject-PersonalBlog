<script setup lang="ts">
import type {AxiosResponse} from "axios";
import type {Category} from "~/utils/types/models/Category";
import * as z from "zod";
const api = useNuxtApp().$api;
const emits = defineEmits<{
  (event: 'created', category: Category): void;
}>();
function createCategory(name: string | undefined ) {
  api.post('/create-category', {
    name: name,
  })
      .then((response: AxiosResponse) => {
        emits('created', response.data);
      })
}
const schema = z.object({
  name: z.string().min(1, 'Must be at least 8 characters'),
})
type Schema = z.output<typeof schema>
const state = reactive<Partial<Schema>>({
  name: undefined,
})
</script>

<template>
  <main>
    <UForm :schema="schema" :state="state" @submit="createCategory(state.name)">
      <UFormField>
        <UInput placeholder="Category Name" v-model="state.name"/>
      </UFormField>
      <UButton type="submit">Create Category</UButton>
    </UForm>
  </main>
</template>