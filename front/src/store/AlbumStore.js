import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAlbumStore = defineStore('album', () => {

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

      console.log("response: ", response);

      if (response.ok) {
        const answer = await response.json();
        const data = answer.data;
        console.log(data);

      }
      await getAllAlbums();
    } catch (error) {
      console.log("error", error);
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
      console.log(response);

      if (response.ok) {
        await getAllAlbums();
      }
    } catch (error) {
      console.log("errror", error);
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
        console.log('Данные удалены');
        await getAllAlbums();
      }
    } catch (error) {
      console.log("errror", error);
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
      }
    } catch (error) {
      console.log(error);

    }
  }

  async function getImages() {
    try {
      const response = await fetch("http://localhost/app/imagesApi.php", {
        method: "GET",
      });

      if (response.ok) {
        const answer = await response.json();
        console.log('answer', answer);
        return answer;
      }
    } catch (error) {
      console.log(error);

    }
  }

  async function saveImage(image) {
    console.log('paramimage', image);

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
        // body: image,
      });

      console.log("response: ", response);

      if (response.ok) {
        const answer = await response.json();
        const data = answer.data;
        console.log(data);
      }
      await getAllAlbums();
    } catch (error) {
      console.log("error", error);
      await getAllAlbums();
    }
  }


  return { album, allAlbums, createAlbum, updateAlbum, removeAlbum, getAllAlbums, getImages, saveImage }
})
