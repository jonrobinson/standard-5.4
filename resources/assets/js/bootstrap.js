import axios from 'axios'
import lodash from 'lodash'

import Vue from 'vue'
import VueEvents from 'vue-events'
import Vuelidate from 'vuelidate'
import Vuex from 'vuex'

window._ = lodash
window.Vue = Vue
window.axios = axios

window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': window.Laravel.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
}

Vue.use(VueEvents)
Vue.use(Vuelidate)
Vue.use(Vuex)