import "../css/app.scss";

import Vue from "vue"
import axios from "axios"
import vueaxios from "vue-axios"

Vue.use(vueaxios, axios)

new Vue({
    el: "#app",
    mounted() {
        if (document.getElementsByClassName("tinymce-editor").length) {
            tinymce.init({
                selector: ".tinymce-editor",
                height: 400,
                language: "cs_CZ",
                entity_encoding : "raw"
            })
        }
    },
    data () {
        return {}
    },
    methods: {
    }
})