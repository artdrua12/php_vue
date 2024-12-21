<template>
  <div class="baseFile pa-3">
    <label
      class="dropbox"
      @dragenter="stopPrevent"
      @dragover="stopPrevent"
      @drop="drop"
    >
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path
          d="M20,4H16.83L15,2H9L7.17,4H4A2,2 0 0,0 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6A2,2 0 0,0 20,4M20,18H4V6H8.05L9.88,4H14.12L15.95,6H20V18M12,7A5,5 0 0,0 7,12A5,5 0 0,0 12,17A5,5 0 0,0 17,12A5,5 0 0,0 12,7M12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15Z"
        />
      </svg>
      <span class="dropboxIntro mt-2"> переместить или загрузить </span>
      <input
        type="file"
        multiple
        style="display: none"
        @change="(e) => uploadFile(e?.target?.files)"
      />
    </label>

    <div
      v-for="(item, index) in images"
      :key="index"
      style="position: relative"
    >
      <span class="imgTitle">{{ item.image_name }}</span>
      <img :src="item.image_data" class="img" @click="openModal(index)" />
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        class="imgDelete"
        @click="removingImg(index)"
        fill="red"
      >
        <path
          d="M20.37,8.91L19.37,10.64L7.24,3.64L8.24,1.91L11.28,3.66L12.64,3.29L16.97,5.79L17.34,7.16L20.37,8.91M6,19V7H11.07L18,11V19A2,2 0 0,1 16,21H8A2,2 0 0,1 6,19Z"
        />
      </svg>
      <p>{{ item.like }}</p>
    </div>

    <base-modal
      v-model="isOpen"
      transition="dialog-top-transition"
      :title="images[currentIndex]?.image_name"
      :okFunction="closeModal"
    >
      <div class="wrapperDialog">
        <canvas
          ref="modalCanvas"
          class="canvasC"
          @click="closeModal"
          @wheel="onwheel"
        ></canvas>

        <div class="modalButtons">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            @click="rotate(-90)"
            width="30"
          >
            <path
              d="M21 21H17V13.5C17 11.57 15.43 10 13.5 10H11V14L4 8L11 2V6H13.5C17.64 6 21 9.36 21 13.5V21Z"
            />
          </svg>

          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="black"
            @click="rotate(90)"
            width="30"
          >
            <path
              d="M3 13.5C3 9.36 6.36 6 10.5 6H13V2L20 8L13 14V10H10.5C8.57 10 7 11.57 7 13.5V21H3V13.5Z"
            />
          </svg>

          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            width="30"
            fill="green"
          >
            <path
              d="M23,10C23,8.89 22.1,8 21,8H14.68L15.64,3.43C15.66,3.33 15.67,3.22 15.67,3.11C15.67,2.7 15.5,2.32 15.23,2.05L14.17,1L7.59,7.58C7.22,7.95 7,8.45 7,9V19A2,2 0 0,0 9,21H18C18.83,21 19.54,20.5 19.84,19.78L22.86,12.73C22.95,12.5 23,12.26 23,12V10M1,21H5V9H1V21Z"
            />
          </svg>
          <p>{{ images[currentIndex].image_like }}</p>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            width="30"
            fill="orange"
          >
            <path
              d="M19,15H23V3H19M15,3H6C5.17,3 4.46,3.5 4.16,4.22L1.14,11.27C1.05,11.5 1,11.74 1,12V14A2,2 0 0,0 3,16H9.31L8.36,20.57C8.34,20.67 8.33,20.77 8.33,20.88C8.33,21.3 8.5,21.67 8.77,21.94L9.83,23L16.41,16.41C16.78,16.05 17,15.55 17,15V5C17,3.89 16.1,3 15,3Z"
            />
          </svg>
        </div>
      </div>
    </base-modal>
    <!-- <button @click="loadImages">загрузить</button> -->
  </div>
</template>
  
  <script setup>
import { ref, nextTick } from "vue";
import { useRoute } from "vue-router";
import { useAlbumStore } from "@/store/AlbumStore";
import { useSnackStore } from "@/store/SnackStore";
const snack = useSnackStore();
const albumStore = useAlbumStore();
const route = useRoute();
import BaseModal from "@/components/BaseModal.vue";
const modalCanvas = ref(null); // canvas который находится в модальном окне
const isOpen = ref(false);
const currentIndex = ref();
const scale = ref(1);

const images = ref([]);
loadImages();

async function loadImages() {
  const answer = await albumStore.getImages(route.params.id);
  console.log("answer", answer);

  for (let i = 0; i < answer.data.length; i++) {
    images.value.push({
      image_name: answer.data[i][1],
      image_like: answer.data[i][2],
      album_id: route.params.id,
      image_data: "data:image/jpeg;base64," + answer.data[i][0],
    });
  }
}

function rotate(angle) {
  let canvas = modalCanvas.value;
  let context = canvas.getContext("2d");
  let canvasImg = canvas.toDataURL();

  let img = new Image();
  img.src = canvasImg;

  img.onload = function () {
    drawRotated(canvas, context, angle, img);
    // requestAnimationFrame(rotate)
  };
}

function drawRotated(canvas, context, angle, img) {
  if (angle % 180 !== 0) {
    canvas.width = img.height;
    canvas.height = img.width;
  } else {
    canvas.width = img.width;
    canvas.height = img.height;
  }
  // Запуск анимации
  context.clearRect(0, 0, canvas.width, canvas.height); // Очищаем холст
  context.save(); // Сохраняем текущее состояние контекста
  context.translate(canvas.width / 2, canvas.height / 2); // Переходим в центр изображения
  context.rotate((angle * Math.PI) / 180); // Поворачиваем изображение на заданный угол, приведенный к радианам
  context.drawImage(img, -img.width / 2, -img.height / 2); // Рисуем изображение
  context.restore(); // Возвращаем настройки контекста в исходное состояние
}

function onwheel(e) {
  if (e.deltaY > 0) {
    scale.value -= 0.15;
    modalCanvas.value.style.transform = `scale(${
      scale.value > 0.15 ? scale.value : (scale.value = 0.15)
    })`;
  } else {
    scale.value += 0.15;
    modalCanvas.value.style.transform = `scale(${scale.value})`;
  }
  return false;
}

function closeModal() {
  // передаем новые координаты в массив картинок
  let canvas = modalCanvas.value;
  images.value[currentIndex.value].image_data = canvas.toDataURL();
  scale.value = 1;
  isOpen.value = false;
}

function uploadFile(files) {
  if (!files) return;
  for (let i = 0; i < files.length; i++) {
    // let urlBase64 = URL.createObjectURL(files[i])

    if (/\.(jpe?g|png|gif)$/i.test(files[i].name)) {
      const reader = new FileReader();
      reader.readAsDataURL(files[i]);
      reader.onloadend = () => {
        const newObject = {
          image_name: files[i].name,
          image_like: 0,
          album_id: route.params.id,
          image_data: reader.result,
        };
        images.value.push(newObject);
        albumStore.saveImage(newObject);
      };
    } else {
      snack.setSnack({
        text: "Вы можете загружать только изображения формата jpeg, png или gif.",
        type: "error",
      });
    }
  }
}

function openModal(index) {
  isOpen.value = !isOpen.value;
  currentIndex.value = index;
  initCanvas();
}

async function initCanvas() {
  await nextTick(); // Dom обновился и появилось модальное окно с modalCanvas
  let canvas = modalCanvas.value;
  let context = canvas.getContext("2d");

  const img = new Image();
  img.onload = () => {
    canvas.width = img.naturalWidth;
    canvas.height = img.naturalHeight;
    context.drawImage(img, 0, 0);
  };
  img.src = images.value[currentIndex.value].image_data;
  context.closePath();
}

function stopPrevent(e) {
  e.stopPropagation();
  e.preventDefault();
}

function drop(e) {
  stopPrevent(e);
  uploadFile(e.dataTransfer.files);
}

function removingImg(index) {
  console.log("images.value[index]", images.value[index]);
  albumStore.deleteImage(images.value[index]);
  images.value.splice(index, 1);
}
</script>
  
  <style scoped>
.baseFile {
  display: flex;
  align-content: center;
  flex-wrap: wrap;
  gap: 45px 35px;
  margin: 20px 0px 0px 20px;
}
.dropbox {
  width: 170px;
  height: 120px;
  display: flex;
  flex-direction: column;
  align-items: center;
  border: 3px dashed #2c4957;
  padding: 5px;
  border-radius: 10px;
  justify-content: center;
}
.dropboxIntro {
  font-size: 14px;
  line-height: 1;
  font-family: serif;
  color: #2c4957;
}
.img {
  max-width: 100%;
  height: 135px;
  object-fit: contain;
  border-radius: 10px;
}
.imgTitle {
  position: absolute;
  top: -17px;
  left: 0px;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
}

.imgDelete {
  position: relative;
  bottom: 0px;
  left: 0px;
  width: 34px;
  color: red;
}
/* .modalImg {
    border: 4px solid white;
    box-shadow: 0px 10px 20px 2px rgba(0, 0, 0, 0.25);
    transition: transform 0.4s ease-in-out;
  } */
.wrapperDialog {
  width: 100%;
  height: 80%;
  display: grid;
  grid-template-columns: 1fr;
  grid-template-rows: 1fr auto;
  align-items: center;
  align-content: center;
  overflow: hidden;
}
.modalButtons {
  width: 100%;
  display: flex;
  justify-content: center;
  gap: 10px;

  bottom: 40px;
}
.canvasC {
  border: 2px solid white;
  margin: auto;
  max-height: 70vh;
}
</style>