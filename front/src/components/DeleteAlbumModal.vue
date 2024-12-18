<template>
  <base-modal
    v-model="isOpen"
    title="Выход"
    ok-title="Удалить"
    cancel-title="Отмена"
    :ok-function="albumStore.removeAlbum"
  >
    <p>Вы действительно хотите удалить альбом?</p>
  </base-modal>
</template>
 
 <script setup>
import BaseModal from "@/components/BaseModal";
import { defineModel } from "vue";
import { useAlbumStore } from "@/store/AlbumStore";
const isOpen = defineModel({ type: Boolean });
const albumStore = useAlbumStore();
</script>
 
 <style>
</style>