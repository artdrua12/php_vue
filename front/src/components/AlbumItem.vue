<template>
  <div class="albumItem full" @click="albumItem">
    <images-view class="full images"></images-view>
    <form class="form full">
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
  </div>
</template>

<script setup>
import ImagesView from "@/components/ImagesView.vue";
import { useAlbumStore } from "@/store/AlbumStore";
const albumStore = useAlbumStore();

</script>

<style scoped>
.albumItem {
  display: flex;
  flex-direction: column;
  height: 100%;
  margin: 40px;
}
.images{
  margin: 50px 50px 30px 50px;
}
</style>