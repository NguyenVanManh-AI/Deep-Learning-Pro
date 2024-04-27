import { createApp } from 'vue'
import App from './App.vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
import router from './router/routes'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'jquery/src/jquery.js'
import 'bootstrap/dist/js/bootstrap.min.js'
import Paginate from "vuejs-paginate-next";
import storeConfigs from './store/store'
import { quillEditor } from "vue3-quill";
import Notifications from "vue3-vt-notifications";
import "tailwindcss/dist/tailwind.css";
import DisableAutocomplete from 'vue-disable-autocomplete';
import print from 'vue3-print-nb'
import Particles from "particles.vue3";
import '../node_modules/nprogress/nprogress.css'
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import VueTippy from 'vue-tippy'
import 'tippy.js/dist/tippy.css';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const app = createApp(App);
app.use(router);
app.use(VueAxios, axios)
app.use(storeConfigs)
app.use(Paginate)
app.use(quillEditor)
app.use(Notifications)
app.use(DisableAutocomplete)
app.use(print)
app.use(Particles)
app.use(VueDatePicker)
app.use(VueTippy)
app.use(VueSweetalert2)
app.mount('#app')
