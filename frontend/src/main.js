import { createApp } from 'vue'
import './style.css'
import './index.css'
import router from '../src/router/index.js'
import store from '../src/store '
import App from './App.vue'

createApp(App).use(store).use(router).mount('#app')
