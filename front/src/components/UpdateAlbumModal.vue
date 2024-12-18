<template>
  <base-modal
    v-model="isOpen"
    title="Выход"
    ok-title="Обновить"
    cancel-title="отмена"
    :ok-function="albumStore.updateAlbum"
  >
    <form>
      <label for="name">Album Name:</label>
      <input
        type="text"
        id="name"
        name="album_name"
        required
        v-model="albumStore.album.album_name"
      />

      <label for="coment">Album Description:</label>
      <textarea
        id="coment"
        name="album_description"
        v-model="albumStore.album.album_description"
      ></textarea>
    </form>
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