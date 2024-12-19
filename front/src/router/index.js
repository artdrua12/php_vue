import { createRouter, createWebHistory } from 'vue-router'
import TheAlbums from '@/views/TheAlbums.vue'

const routes = [
  {
    path: '/',
    name: 'albums',
    component: TheAlbums
  },
  {
    path: '/album/:id',
    name: 'album',
    params: true,
    component: () => import("../components/AlbumItem.vue")
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
