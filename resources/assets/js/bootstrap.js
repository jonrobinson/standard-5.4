import axios from 'axios'
import lodash from 'lodash'

import Vue from 'vue'
import VueEvents from 'vue-events'
import Vuelidate from 'vuelidate'

window._ = lodash
window.Vue = Vue
window.axios = axios

Vue.use(VueEvents)
Vue.use(Vuelidate)