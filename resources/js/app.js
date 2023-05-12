import "./bootstrap";
import { createApp } from "vue/dist/vue.esm-bundler";

// Общие компоненты
import login from "./components/template/login.vue";
import telegram from "./components/template/telegram.vue";
import metrikabasic from "./components/tables/basic.vue";
import metrikaweek from "./components/tables/week.vue";

import metrikaidentifiedvisitors from "./components/tables/identifiedVisitors.vue";
import metrikaheader from "./components/include/header.vue";

// компоненты только для супер админа
import metrikamenu from "./components/include/menu.vue";
import register from "./components/template/register.vue";
import createuser from "./components/template/create.vue";

// компоненты для user
import usermenu from "./components/include/usermenu.vue";

// для партнера
import partnersmenu from "./components/include/partnersmenu.vue";
import partners from "./components/tables/partners.vue";

// пока не знаю
import userlist from "./components/tables/users.vue";
import conversion from "./components/diagram/conversion.vue";

import conversionbeta from "./components/diagram/conversionbeta.vue";

import day from "./components/tables/day.vue";

createApp({
    components: {
        metrikaheader,
        login,
        telegram,
        register,
        createuser,
        metrikabasic,
        metrikamenu,
        metrikaidentifiedvisitors,
        usermenu,
        conversion,
        metrikaweek,
        userlist,
        day,
        partnersmenu,

        partners,
        conversionbeta,
    },
}).mount("#app");
