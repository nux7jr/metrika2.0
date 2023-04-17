import "./bootstrap";
import { createApp } from "vue/dist/vue.esm-bundler";

import login from "./components/template/login.vue";
import telegram from "./components/template/telegram.vue";
import register from "./components/template/register.vue";

import metrikabasic from "./components/basic/basic.vue";

import metrikaheader from "./components/include/header.vue";

import metrikamenu from "./components/include/menu.vue";

createApp({
    components: {
        metrikaheader,
        login,
        telegram,
        register,
        metrikabasic,
        metrikamenu,
    },
}).mount("#app");
