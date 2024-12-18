<template>
  <div class="baseFile pa-3">
    <div
      class="dropbox"
      @dragenter="stopPrevent"
      @dragover="stopPrevent"
      @drop="drop"
    >
      <label>
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
    </div>

    <div
      v-for="(item, index) in images"
      :key="index"
      style="position: relative"
    >
      <span class="imgTitle">{{ item.image_name }}</span>
      <img :src="item.image_data" class="img" @click="openModal(index)" />
      <span class="imgSize">{{ Math.ceil(item.image_size / 1000) }} Kb</span>
      <!-- <img :src="URL.createObjectURL(item.value)" class="img" @click="openModal(index)" /> -->
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        class="imgDelete"
        @click="removingImg(index)"
      >
        <path
          d="M20.37,8.91L19.37,10.64L7.24,3.64L8.24,1.91L11.28,3.66L12.64,3.29L16.97,5.79L17.34,7.16L20.37,8.91M6,19V7H11.07L18,11V19A2,2 0 0,1 16,21H8A2,2 0 0,1 6,19Z"
        />
      </svg>
    </div>

    <base-modal
      v-model="isOpen"
      transition="dialog-top-transition"
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
          <button class="modalButtonImg" @click="rotate(-90)">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path
                d="M21 21H17V13.5C17 11.57 15.43 10 13.5 10H11V14L4 8L11 2V6H13.5C17.64 6 21 9.36 21 13.5V21Z"
              />
            </svg>
          </button>

          <button class="modalButtonImg" @click="rotate(90)">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path
                d="M3 13.5C3 9.36 6.36 6 10.5 6H13V2L20 8L13 14V10H10.5C8.57 10 7 11.57 7 13.5V21H3V13.5Z"
              />
            </svg>
          </button>
        </div>
      </div>
    </base-modal>
    <button @click="test">test</button>
    <div id="test">test</div>
  </div>
</template>
  
  <script setup>
import { ref, nextTick } from "vue";
import { useAlbumStore } from "@/store/AlbumStore";
const albumStore = useAlbumStore();
import BaseModal from "@/components/BaseModal.vue";
const modalCanvas = ref(null); // canvas который находится в модальном окне
const isOpen = ref(false);
const currentIndex = ref();
const scale = ref(1);

const images = ref([]);

async function test() {
  // const response = await fetch("http://localhost/app/imagesApiTEST.php", {
  //   method: "GET",
  // });

  // if (response.ok) {
  //   const answer = await response.json();
  //   // const answer = await response;
  //   console.log("answer", answer.data);
  //   // const imageObjectURL = URL.createObjectURL(answer);
  //   for (let i = 0; i < answer.data.length; i++) {
  //     images.value.push({
  //       image_name: answer.data[i][1],
  //       image_size: answer.data[i][2],
  //       image_type: answer.data[i][3],
  //       image_data: "data:image/jpeg;base64," + answer.data[i][0],
  //     });
  //   }
  // } else {
  //   console.log("no answer correctly");
  // }
  const answer = await albumStore.getImages();
  for (let i = 0; i < answer.data.length; i++) {
    images.value.push({
      image_name: answer.data[i][1],
      image_size: answer.data[i][2],
      image_type: answer.data[i][3],
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
        images.value.push({
          image_name: files[i].name,
          image_size: files[i].size,
          image_type: files[i].type,
          image_data: reader.result,
        });

        albumStore.saveImage({
          image_name: files[i].name,
          image_size: files[i].size,
          image_type: files[i].type,
          image_data: reader.result,
        });
      };
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
  images.value.splice(index, 1);
}
</script>
  
  <style scoped>
.baseFile {
  display: flex;
  align-content: flex-start;
  flex-wrap: wrap;
  gap: 20px 25px;
  margin: 20px 0px 0px 20px;
  /* background-color: rgba(246, 194, 160, 0.122); */
}
.dropbox {
  width: 170px;
  height: 120px;
  display: grid;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  border: 3px dashed #2c4957;
  padding: 5px;
  border-radius: 10px;
  margin-bottom: 25px;
}
.dropboxIntro {
  display: block;
  margin: auto;
  text-align: center;
  font-size: 14px;
  line-height: 1;
  font-family: serif;
  color: #2c4957;
}
.img {
  max-width: 100%;
  height: 120px;
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
.modalButtonImg {
  background-color: white;
  color: #546e7a;
}
.canvasC {
  border: 2px solid white;
  margin: auto;
}
</style>