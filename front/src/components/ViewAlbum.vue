<template>
  <div class="albumFull" @click="viewAlbum">
    <img src="../assets/logo.png" class="img" />
    <h1>{{ albumStore.album.album_name }}</h1>
    <h1>{{ albumStore.album.album_coment }}</h1>
  </div>
</template>

<script setup>
import {useAlbumStore } from "@/store/AlbumStore"
const albumStore = useAlbumStore()

</script>

<style scoped>
.albumFull {
  display: flex;
  flex-direction: column;
  height: 100vh;
  margin: 40px;
}
.img {
  object-fit: cover;
  width: 50%;
  height: 50%;
}
</style>