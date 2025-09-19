<template>
  <UToaster/>
  <div class="flex justify-center space-x-12 bg-blue-400 border-[6px] border-b-blue-950 rounded-xl shadow-lg p-2 m-2">
    <NuxtLink to="/home" class="hover:underline hover:text-blue-950">Home</NuxtLink>
    <NuxtLink v-if="auth.isLoggedIn && auth.isAdmin" class="hover:underline hover:text-blue-950" to="/posts">My Articles</NuxtLink>
    <NuxtLink v-if="auth.isLoggedIn && auth.isAdmin" class="hover:underline hover:text-blue-950" to="/manage">Manage</NuxtLink>
    <NuxtLink v-if="!auth.isLoggedIn" to="/login" class="hover:underline hover:text-blue-950">🎫 Log In</NuxtLink>
    <NuxtLink v-if="auth.isLoggedIn" to="/" class="hover:underline hover:text-blue-950" @click="auth.logout()">⬅️ Log Out</NuxtLink>
    <NuxtLink to="/about" class="hover:underline hover:text-blue-950">About Me</NuxtLink>
  </div>
  <NuxtPage/>
</template>
<script setup lang="ts">
import { useAuthStore } from "~/store/auth";
const auth = useAuthStore();
</script>