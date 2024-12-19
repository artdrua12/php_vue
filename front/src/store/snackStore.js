import { reactive } from 'vue'
import { defineStore } from 'pinia'

export const useSnackStore = defineStore('alert', () => {
  const snack = reactive([])

  function setSnack(obj) {
    const newObj = { ...obj, id: getId() }
    snack.push(newObj)
    removeFirstElement()
  }
  function removeFirstElement() {
    setTimeout(() => {
      snack.shift()
    }, 3500)
  }
  function getId() {
    return new Date().getMilliseconds()
  }
  return { snack, setSnack }
})