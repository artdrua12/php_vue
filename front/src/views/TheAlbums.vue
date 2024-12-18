<template>
  <div class="wrapper">
    <h2 class="full">
      Albums
      <button
        type="button"
        style="background-color: green; color: white"
        class="btn btn-outline-success"
        @click="createAlbum"
      >
        Add new Album
      </button>
    </h2>

    <table class="full">
      <thead>
        <tr>
          <th>ID</th>
          <th>Album Name</th>
          <th>Album Description</th>
          <th>See</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in albumStore.allAlbums" :key="item.id">
          <td>{{ item.id }}</td>
          <td>{{ item.album_name }}</td>
          <td>{{ item.album_description }}</td>
          <td>
            <button
              type="button"
              style="background-color: lightblue"
              @click="viewAlbum(item)"
            >
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                  d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"
                />
              </svg>
              View
            </button>
          </td>
          <td>
            <button
              type="button"
              style="background-color: orange"
              @click="updateAlbum(item)"
            >
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                  d="M21,10.12H14.22L16.96,7.3C14.23,4.6 9.81,4.5 7.08,7.2C4.35,9.91 4.35,14.28 7.08,17C9.81,19.7 14.23,19.7 16.96,17C18.32,15.65 19,14.08 19,12.1H21C21,14.08 20.12,16.65 18.36,18.39C14.85,21.87 9.15,21.87 5.64,18.39C2.14,14.92 2.11,9.28 5.62,5.81C9.13,2.34 14.76,2.34 18.27,5.81L21,3V10.12M12.5,8V12.25L16,14.33L15.28,15.54L11,13V8H12.5Z"
                />
              </svg>

              Update
            </button>
          </td>
          <td>
            <button
              type="button"
              style="background-color: red; color: white"
              @click="removeAlbum(item)"
            >
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                  fill="white"
                  d="M20.37,8.91L19.37,10.64L7.24,3.64L8.24,1.91L11.28,3.66L12.64,3.29L16.97,5.79L17.34,7.16L20.37,8.91M6,19V7H11.07L18,11V19A2,2 0 0,1 16,21H8A2,2 0 0,1 6,19Z"
                />
              </svg>
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <CreateAlbumModal v-model="isCreateAlbum" />
    <UpdateAlbumModal v-model="isUpdateAlbum" />
    <DeleteAlbumModal v-model="isDeleteAlbum" />
  </div>
</template>
  
  <script setup>
import { ref } from "vue";
import CreateAlbumModal from "@/components/CreateAlbumModal.vue";
import UpdateAlbumModal from "@/components/UpdateAlbumModal.vue";
import DeleteAlbumModal from "@/components/DeleteAlbumModal.vue";

import { useRouter } from "vue-router";
import { useAlbumStore } from "@/store/AlbumStore";
const albumStore = useAlbumStore();
const router = useRouter();

let isCreateAlbum = ref(false);
let isUpdateAlbum = ref(false);
let isDeleteAlbum = ref(false);

function viewAlbum(item) {
  albumStore.album = item;
  router.push({ name: "album", params: { id: item.id } });
}

function createAlbum() {
  albumStore.album = { album_name: "", album_description: "" };
  isCreateAlbum.value = !isCreateAlbum.value;
}

function updateAlbum(item) {
  albumStore.album = item;
  isUpdateAlbum.value = !isUpdateAlbum.value;
}

function removeAlbum(item) {
  albumStore.album = item;
  isDeleteAlbum.value = !isDeleteAlbum.value;
}

albumStore.getAllAlbums();
</script>
  
  <style>
.wrapper {
  width: 90vw;
  margin: auto;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  grid-gap: 20px;
}
.full {
  grid-column: 1/-1;
}
h2 {
  display: flex;
  justify-content: space-between;
  margin-top: 70px;
}

table {
  width: 100%;
  /* border-collapse: collapse; */
  border: 2px solid lightgray;
  padding: 10px;
  border-radius: 7px;
  padding: 25px;
}
th {
  background-color: lightgray;
  padding: 20px;
}
td {
  border-bottom: 1px solid lightgray;
  padding: 7px;
}

tr:nth-child(2n) {
  background-color: #f2f2f2;
}
button {
  border-radius: 7px;
  padding: 7px;
  border-color: inherit;
}
svg {
  width: 20px;
  height: 20px;
  margin-bottom: -3px;
}
</style>