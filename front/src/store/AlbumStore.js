import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useSnackStore } from './SnackStore'

export const useAlbumStore = defineStore('album', () => {
  const snack = useSnackStore()
  // state
  const album = ref({ album_name: '', album_description: '' })
  const allAlbums = ref()

  // action
  async function getAllAlbums() {
    try {
      const response = await fetch("http://localhost/app/api.php", {
        method: "GET",
      });

      if (response.ok) {
        const answer = await response.json();
        allAlbums.value = answer.data;
        // snack.setSnack({ text: answer.message, type: 'info' })
      }
    } catch (error) {
      snack.setSnack({ text: 'Ошибка чтения альбома ', type: 'error' })
    }
  }

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
      snack.setSnack({ text: 'Ошибка обновления альбома ', type: 'error' })
    }
  }

  async function removeAlbum() {
    try {
      const response = await fetch("http://localhost/app/api.php", {
        method: "DELETE",
        body: JSON.stringify(album.value),
      });

      if (response.ok) {
        const answer = await response.json();
        snack.setSnack({ text: answer.message, type: 'info' })
        await getAllAlbums();
      }
    } catch (error) {
      snack.setSnack({ text: 'Ошибка удаления альбома ', type: 'error' })
    }
  }



  async function getImages(id) {
    console.log('id', id);

    try {
      const response = await fetch(`http://localhost/app/imagesApi.php?id=${id}`, {
        method: "GET",
      });

      if (response.ok) {
        const answer = await response.json();
        snack.setSnack({ text: answer.message, type: 'info' })
        return answer;
      }
    } catch (error) {
      snack.setSnack({ text: 'Ошибка получения картинок ', type: 'error' })
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


    } catch (error) {
      console.log("error", error);
      snack.setSnack({ text: 'Ошибка сохранения картинки ', type: 'error' })
    }
  }

  async function deleteImage(image) {

    try {
      const response = await fetch("http://localhost/app/imagesApi.php", {
        method: "DELETE",
        body: JSON.stringify(image),
      });

      if (response.ok) {
        const answer = await response.json();
        await snack.setSnack({ text: answer.message, type: 'info' })
      }
    } catch (error) {
      snack.setSnack({ text: 'Ошибка удаления картинки ', type: 'error' })
    }
  }


  return { album, allAlbums, createAlbum, updateAlbum, removeAlbum, getAllAlbums, getImages, saveImage, deleteImage }
})
