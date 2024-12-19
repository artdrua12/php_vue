<template>
  <router-view />
  <transition-group name="slide" tag="div" class="snackbar-wrapper">
    <div
      v-for="item in snack"
      :key="item.id"
      class="snackbar"
      :class="item.type"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        v-if="item.type == 'info'"
        width="35"
        fill="white"
      >
        <path
          d="M13,9H11V7H13M13,17H11V11H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"
        />
      </svg>

      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        v-if="item.type == 'error'"
        width="35"
        fill="white"
      >
        <path
          d="M20 6.91L17.09 4L12 9.09L6.91 4L4 6.91L9.09 12L4 17.09L6.91 20L12 14.91L17.09 20L20 17.09L14.91 12L20 6.91Z"
        />
      </svg>

      <span>{{ item.text }}</span>
    </div>
  </transition-group>
</template>


<script setup>
import { useSnackStore } from "@/store/snackStore";
import { storeToRefs } from "pinia"; // что бы объект был реактивным
const store = useSnackStore(); // для работы с уведовлениями
const { snack } = storeToRefs(store); // для работы с уведомлениями что бы они были реактивны
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

nav {
  padding: 30px;
}

nav a {
  font-weight: bold;
  color: #2c3e50;
}

nav a.router-link-exact-active {
  color: #42b983;
}

button {
  border: none;
  padding: 7px 15px;
}

/* snackbar */
.snackbar-wrapper {
  grid-area: 1/1/-1/-1;
  position: fixed;
  right: 25px;
  bottom: 45px;
  display: flex;
  flex-direction: column-reverse;
  gap: 25px;
  z-index: 11;
}
.snackbar {
  width: 320px;
  z-index: 11;
  display: grid;
  gap: 7px;
  grid-template-columns: 35px 1fr;
  align-items: center;
  color: white;
  padding: 10px 12px 10px 10px;
  font-size: 14px;
  font-family: sans-serif;
  letter-spacing: 1px;
  transition: transform 0.35s;
  box-shadow: 0px 0px 7px -3px #2c4957;
  border-left: 3px solid white;
}
.info {
  background-color: rgba(0, 128, 0, 0.9);
}
.error {
  background-color: rgba(194, 0, 0, 0.9);
}
.snack-icon {
  display: block;
  color: white;
  width: 35px;
  height: 35px;
}

.slide-enter-from,
.slide-leave-to {
  transform: translateX(100%);
}

.slide-leave-active {
  position: absolute;
}
</style>
