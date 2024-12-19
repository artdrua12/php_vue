import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useSnackStore } from './snackStore'

export const useAlbumStore = defineStore('album', () => {
  const snack = useSnackStore()
  // state
  const album = ref({ album_name: '', album_description: '' })
  const allAlbums = ref()

  // action

  async function createAlbum() {
    try {
      const response = await fetch("http://localhost/app/api.php", {
        method: "POST",
        mode: "no-cors",
        headers: {
          "Content-Type": "application/json",
          "Cache-Control": "no-cache",
          "API-Key": "secret",
        },
        credentials: "omit",
        body: JSON.stringify(album.value),
      });


      if (response.ok) {
        const answer = await response.json();
        snack.setSnack({ text: answer.data, type: 'info' })
      }
      await getAllAlbums();
    } catch (error) {
      snack.setSnack({ text: 'Ошибка сети ', type: 'error' })
      await getAllAlbums();
    }
  }

  async function updateAlbum() {
    try {
      const response = await fetch("http://localhost/app/api.php", {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
          "Cache-Control": "no-cache",
          "API-Key": "secret",
        },
        body: JSON.stringify(album.value),
      });

      if (response.ok) {
        const answer = await response.json();
        snack.setSnack({ text: answer.message, type: 'info' })
        await getAllAlbums();
      }
    } catch (error) {
      snack.setSnack({ text: 'Ошибка сети ', type: 'error' })
    }
  }

  async function removeAlbum() {
    try {
      const response = await fetch("http://localhost/app/api.php", {
        method: "DELETE",
        body: JSON.stringify(album.value),
      });
      console.log(response);

      if (response.ok) {
        const answer = await response.json();
        snack.setSnack({ text: answer.message, type: 'info' })
        await getAllAlbums();
      }
    } catch (error) {
      snack.setSnack({ text: 'Ошибка сети ', type: 'error' })
    }
  }

  async function getAllAlbums() {
    try {
      const response = await fetch("http://localhost/app/api.php", {
        method: "GET",
      });

      if (response.ok) {
        const answer = await response.json();
        allAlbums.value = answer.data;
        snack.setSnack({ text: answer.message, type: 'info' })
      }
    } catch (error) {
      snack.setSnack({ text: 'Ошибка сети ', type: 'error' })
    }
  }

  async function getImages() {
    try {
      const response = await fetch("http://localhost/app/imagesApi.php", {
        method: "GET",
      });

      if (response.ok) {
        const answer = await response.json();
        snack.setSnack({ text: answer.message, type: 'info' })
        return answer;
      }
    } catch (error) {
      snack.setSnack({ text: 'Ошибка сети ', type: 'error' })
    }
  }

  async function saveImage(image) {
    try {
      const response = await fetch("http://localhost/app/imagesApi.php", {
        method: "POST",
        mode: "no-cors",
        headers: {
          "Content-Type": "image/jpeg",
          "Cache-Control": "no-cache",
          "API-Key": "secret",
        },
        credentials: "omit",
        body: JSON.stringify(image),
      });


      if (response.ok) {
        const answer = await response.json();
        snack.setSnack({ text: answer.message, type: 'info' })
      }
      await getAllAlbums();
    } catch (error) {
      console.log("error", error);
      await getAllAlbums();
      snack.setSnack({ text: 'Ошибка сети ', type: 'error' })
    }
  }


  return { album, allAlbums, createAlbum, updateAlbum, removeAlbum, getAllAlbums, getImages, saveImage }
})
