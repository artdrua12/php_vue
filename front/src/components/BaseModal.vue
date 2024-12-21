<template>
  <teleport to="#port">
    <div v-if="isOpen" class="modal-wrapper">
      <div class="modal">
        <div class="modal-title">
          <p>
            {{ props.title }}
          </p>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            @click="close"
            width="20px"
            height="20px"
            fill="white"
          >
            <path
              d="M13.46,12L19,17.54V19H17.54L12,13.46L6.46,19H5V17.54L10.54,12L5,6.46V5H6.46L12,10.54L17.54,5H19V6.46L13.46,12Z"
            />
          </svg>
        </div>
        <div class="modal-content">
          <slot></slot>
        </div>
        <div class="modal-button">
          <button type="button" class="secondary" @click="close">
            {{ props.cancelTitle }}
          </button>
          <slot name="button"></slot>
          <button
            type="button"
            class="success"
            :disabled="okDisable"
            @click="ok"
          >
            {{ props.okTitle }}
          </button>
        </div>
      </div>
    </div>
  </teleport>
</template>
  
  <script setup>
import { defineProps, defineModel } from "vue";
const props = defineProps({
  title: {
    type: String,
    default: "",
  },
  //название кнопки
  okTitle: {
    type: String,
    default: "OK",
  },
  //fun - функция которая вызывается
  okFunction: {
    type: [Function, Object],
    default: () => {},
  },
  okDisable: {
    type: Boolean,
    default: false,
  },
  // isCloseAfterClick - закрывать ли модальное окно после вызова функции
  isCloseAfterClick: {
    type: Boolean,
    default: true,
  },
  cancelTitle: {
    type: String,
    default: "Закрыть",
  },
});
const isOpen = defineModel({ type: Boolean });
function close() {
  isOpen.value = !isOpen.value;
}
function ok() {
  if (props.okFunction) {
    props.okFunction();
  }
  if (props.isCloseAfterClick) {
    close();
  }
}
</script>
  
  <style>
.modal-wrapper {
  position: fixed;
  display: flex;
  align-items: center;
  align-content: center;
  justify-content: center;
  top: 0px;
  left: 0px;
  width: 100vw;
  height: 100vh;
  z-index: 5;
  background-color: rgba(33, 33, 33, 0.33);
  backdrop-filter: grayscale(1) blur(1px);
}

.modal {
  display: flex;
  flex-direction: column;
  width: auto;
  height: auto;
  background-color: #f0f0f0;
  box-shadow: 0 11px 15px -7px rgba(0, 0, 0, 0.2),
    0 24px 38px 3px rgba(0, 0, 0, 0.14), 0 9px 46px 8px rgba(0, 0, 0, 0.12);
  border-radius: 5px;
}
.modal-title {
  min-height: 30px;
  background-color: #546e7a;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: white;
  font-size: 18px;
  padding: 0px 15px 0px 15px;
  border-radius: 4px 4px 0 0;
}
.modal-content {
  max-width: 96vw;
  max-height: 90vh;
  overflow: auto;
  scrollbar-width: thin;
  padding: 20px 20px 10px 20px;
}

.modal-button {
  width: 100%;
  display: flex;
  justify-content: space-between;
  gap: 15px;
}
.modal p {
  font-size: 20px;
}
.secondary {
  background-color: #546e7a;
  color: white;
}
.success {
  background-color: #3bd015;
  color: white;
}
</style>