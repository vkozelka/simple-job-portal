import "../css/app.scss";

import Vue from "vue"
import axios from "axios"
import vueaxios from "vue-axios"

Vue.use(vueaxios, axios)

new Vue({
    el: "#app",
    data () {
        return {}
    },
    methods: {
    }
})