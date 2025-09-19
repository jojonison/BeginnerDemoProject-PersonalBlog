<script setup lang="ts">
import { useAuthStore } from "~/store/auth";
import {z} from "zod";

const auth = useAuthStore();
const email = ref<string>('');
const password = ref<string>('');
const toast = useToast();
const router = useRouter();

const schema = z.object({
  email: z.email('invalid email'),
  password: z.string().min(1)
})

type Schema = z.output<typeof schema>

const state = reactive<Partial<Schema>>({
  email: undefined,
  password: undefined
})

function authenticate() {
  const result = schema.safeParse({email: email.value, password: password.value});
  if (!result.success) {
    toast.add({
      title: z.flattenError(result.error).fieldErrors.email?.at(0),
      description: 'please enter an email address',
      color: 'warning',
    })
  } else {
    auth.login(email.value, password.value)
        .then(() => {
          router.push({name:'home'});
        })
        .catch(() => {})
        .finally(() => {})
  }
}
</script>

<template>
  <main class="flex items-center justify-center h-screen bg-cover bg-center font-mono">
    <UForm
        :schema="schema"
        :state="state"
        class="bg-blue-400 border-[6px] border-b-blue-950 rounded-xl shadow-lg m-2 p-6 w-80"
        @submit.prevent="authenticate"
    >
      <UFormField label="Email" name="Email">
        <UInput v-model="email" placeholder="example@example.com" color="neutral" class="w-full border-4 bg-blue-200 border-b-blue-950 rounded-xl shadow-lg m-2"/>
      </UFormField>
      <UFormField label="Password" name="Password">
        <UInput v-model="password" placeholder="Enter your password" color="neutral" type="password" class="w-full border-4 bg-blue-200 border-b-blue-950 rounded-xl shadow-lg m-2"/>
      </UFormField>
      <UButton
          type="submit"
          color="neutral"
          class="w-full bg-blue-200 border-[6px] border-b-blue-950 rounded-xl shadow-lg m-2 hover:bg-blue-800 hover:text-white"
          @click="authenticate"
      >
        ➡️ Log In
      </UButton>
    </UForm>
  </main>
</template>