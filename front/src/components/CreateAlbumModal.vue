<template>
  <base-modal
    v-model="isOpen"
    title="Создание нового альбома"
    ok-title="Создать"
    cancel-title="отмена"
    :ok-function="albumStore.createAlbum"
  >
    <form class="form">
      <label for="name" class="form"
        >Имя альбома
        <input
          type="text"
          name="album_name"
          required
          v-model="albumStore.album.album_name"
        />
      </label>

      <label for="coment" class="form"
        >Описание
        <textarea
          name="album_description"
          rows="5"
          v-model="albumStore.album.album_description"
        ></textarea>
      </label>
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
.form {
  display: flex;
  justify-items: end;
  flex-direction: column;
}
form.form {
  gap: 20px;
  margin: 20px 50px;
}
</style>