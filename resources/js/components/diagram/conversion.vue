<template>
    <div ref="diagram" class="diagram">
        <form class="info-option" @submit.prevent="update_info">
            <div class="info-filters">
                <div class="filter__item">
                    <label class="filter__label" for="date-off"
                        >Показать данные от</label
                    >
                    <input
                        class="filter__input"
                        type="date"
                        name="date-off"
                        id="date-off"
                        v-model="date.date_on"
                    />
                    <!-- @change="set_user_date()" -->
                </div>
                <div class="filter__item">
                    <label class="filter__label" for="date-on">До</label>
                    <input
                        class="filter__input"
                        type="date"
                        name="date-on"
                        id="date-on"
                        v-model="date.date_off"
                    />
                    <!-- @change="set_user_date()" -->
                </div>
                <div class="filter__item filter__site">
                    <div class="multiselect">
                        <div class="select-box">
                            <select class="mtable__select">
                                <option class="mtable__select">
                                    Выберете сайты
                                </option>
                            </select>
                            <div
                                class="over-select"
                                v-on:click="show_checkboxes"
                            ></div>
                        </div>
                        <div class="checkboxes checkboxes_site">
                            <div class="checkboxes__header">
                                <input
                                    class="selectAll"
                                    type="checkbox"
                                    v-on:click="set_all_checkboxes"
                                />
                                <input
                                    class="search"
                                    v-model="search"
                                    type="search"
                                    placeholder="Поиск..."
                                />
                            </div>
                            <label
                                v-for="item in search_handler"
                                :key="item.id"
                            >
                                <div class="site__wrapper">
                                    <input
                                        class="site_input"
                                        type="checkbox"
                                        v-bind:value="item"
                                    />{{ item }}
                                </div>
                            </label>
                        </div>
                    </div>
                    <!-- @change="set_user_date()" -->
                </div>
            </div>
            <div class="info-option__button button-group">
                <button class="def__button" @click="update_info()">
                    <span v-if="loading" class="loader"></span>
                    Показать данные
                </button>
            </div>
        </form>
        <preloader v-if="loading" />
        <div v-else class="diagram__wrapper">
            <div class="conversion__table">
                <div class="conversion__header">
                    <div class="conversion__heading">Сайт</div>
                    <div class="conversion__heading">Визиты</div>
                    <div class="conversion__heading">Лиды</div>
                    <div class="conversion__heading bm-option">
                        Конверция
                        <button
                            @click="filterActive = !filterActive"
                            class="bm-option__button"
                        >
                            <img src="../../../images/icons/bm.svg" />
                        </button>
                    </div>
                </div>
                <div class="conversion__main">
                    <div
                        v-for="item in set_conversion_filter"
                        class="conversion__col"
                    >
                        <div class="conversion__item">{{ item.name }}</div>
                        <div class="conversion__item">{{ item.visits }}</div>
                        <div class="conversion__item">{{ item.leads }}</div>
                        <div class="conversion__item">
                            {{ item.conversion }} %
                        </div>
                    </div>
                </div>
            </div>
            <div class="line__wrapper">
                <Line
                    class="line__item"
                    :data="sitesDate.informationLine"
                    :options="optionsLine"
                />
                <Line
                    class="line__item"
                    :data="sitesDate.informationLine"
                    :options="optionsLine"
                />
            </div>
            <div class="some__info">
                <div>informationLine</div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    Chart,
    ArcElement,
    Tooltip,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Legend,
    BarElement,
} from "chart.js";
import preloader from "../template/preloader.vue";
import { Line } from "vue-chartjs";
import { Doughnut } from "vue-chartjs";
Chart.register(
    ArcElement,
    Tooltip,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Legend
);
Chart.defaults.color = "#ffffff";
export default {
    name: "metrikaDiagram",
    data() {
        return {
            filterActive: false,
            info: [
                {
                    name: "xl-pipe",
                    visits: 123123,
                    leads: 123,
                    conversion: 10,
                },
                {
                    name: "xl-pipe2",
                    visits: 123123,
                    leads: 123,
                    conversion: 12,
                },
                {
                    name: "tiksanauto",
                    visits: 123123,
                    leads: 123,
                    conversion: 15,
                },
                {
                    name: "otopite",
                    visits: 123123,
                    leads: 123,
                    conversion: 5,
                },
            ],
            sites: [
                "xl-pipe",
                "tiksanauto",
                "malie-etaji",
                "xl-pipe",
                "tiksanauto",
                "malie-etaji",
                "xl-pipe",
                "tiksanauto",
                "malie-etaji",
                "xl-pipe",
                "tiksanauto",
                "malie-etaji",
                "xl-pipe",
                "tiksanauto",
                "malie-etaji",
                "xl-pipe",
                "tiksanauto",
                "malie-etaji",
                "xl-pipe",
                "tiksanauto",
                "malie-etaji",
                "xl-pipe",
                "tiksanauto",
                "malie-etaji",
                "xl-pipe",
                "tiksanauto",
                "malie-etaji",
                "xl-pipe",
                "tiksanauto",
                "malie-etaji",
            ],
            search: "",
            loading: false,
            expanded: false,
            date: {
                date_on: "-",
                date_off: "-",
            },
            sitesDate: {
                informationLine: {
                    labels: [
                        "Январь",
                        "Февраль",
                        "Март",
                        "Апрель",
                        "Май",
                        "Июнь",
                        "Июль",
                        "Август",
                        "Сентябрь",
                        "Октябрь",
                        "Ноябрь",
                        "Декабрь",
                    ],
                    datasets: [
                        {
                            label: "XL-PIPE",
                            backgroundColor: "#f87979",
                            data: [40, 39, 10, 40, 39, 80, 40],
                        },
                        {
                            label: "TIKSANAUTO",
                            backgroundColor: "#fffff4",
                            data: [140, 39, 10, 240, 29, 80, 10],
                        },
                        {
                            label: "dw-dealer",
                            backgroundColor: "red",
                            data: [140, 39, 10, 240, 29, 80, 10],
                        },
                    ],
                },
                informationDoughnutVisits: {
                    labels: ["UGU", "TIKSANAUTO", "XL_PIPE"],
                    datasets: [
                        {
                            backgroundColor: ["#41B883", "#E46651", "#00D8FF"],
                            data: [40, 20, 80],
                        },
                    ],
                },
                informationDoughnutLeads: {
                    labels: ["UGU", "TIKSANAUTO", "XL_PIPE"],
                    datasets: [
                        {
                            backgroundColor: ["#41B883", "#E46651", "#00D8FF"],
                            data: [510, 120, 380],
                        },
                    ],
                },
                textInfo: {},
            },
            optionsLine: {
                responsive: true,
                maintainAspectRatio: false,
            },
            optionsDoughnutVisits: {
                legend: {
                    position: "top",
                },
                plugins: {
                    title: {
                        display: true,
                        text: "Все визиты за период",
                        color: "white",
                        font: {
                            size: 14,
                            family: "'Montserrat', sans-serif;",
                            weight: "bold",
                        },
                        padding: {
                            top: 10,
                            bottom: 3,
                        },
                    },
                },
                responsive: true,
                maintainAspectRatio: false,
            },
            optionsDoughnutLeads: {
                plugins: {
                    title: {
                        display: true,
                        text: "Все лиды за период",
                        color: "white",
                        font: {
                            size: 14,
                            family: "'Montserrat', sans-serif;",
                            weight: "bold",
                        },
                        padding: {
                            top: 10,
                            bottom: 3,
                        },
                    },
                },
                responsive: true,
                maintainAspectRatio: false,
            },
        };
    },
    components: {
        Doughnut,
        Line,
        preloader,
    },
    computed: {
        search_handler() {
            return this.sites.filter((elem) => {
                return elem.toLowerCase().includes(this.search.toLowerCase());
            });
        },
        set_conversion_filter() {
            return this.info.sort((a, b) => a.conversion - b.conversion);
        },
    },
    created() {
        this.check_user_date();
    },
    methods: {
        get_date_now() {
            const supDate = new Date(),
                targetDay = 1,
                targetDate = new Date(),
                lastDate = new Date(),
                delta = targetDay - supDate.getDay();
            if (delta >= 0) {
                targetDate.setDate(supDate.getDate() + delta);
                lastDate.setDate(supDate.getDate() + 5);
            } else {
                targetDate.setDate(supDate.getDate() - 7 + delta);
                lastDate.setDate(supDate.getDate() - 1 + delta);
            }
            this.date = {
                date_on: targetDate.toISOString().split("T")[0],
                date_off: lastDate.toISOString().split("T")[0],
            };

            sessionStorage.setItem("date", JSON.stringify(this.date));
        },
        check_user_date() {
            if (sessionStorage.getItem("date")) {
                const localDate = JSON.parse(sessionStorage.getItem("date"));
                this.date = {
                    date_on: localDate.date_on,
                    date_off: localDate.date_off,
                };
            } else {
                this.get_date_now();
            }
        },
        set_user_date() {
            sessionStorage.setItem("date", JSON.stringify(this.date));
        },
        async update_info(evt) {
            console.log(evt);
            this.loading = true;
            const user_form = new FormData(evt.target);
            const input_elements = evt.target.querySelectorAll(".site_input");
            const checked_value_sities = [];
            for (let i = 0; input_elements[i]; ++i) {
                if (input_elements[i].checked) {
                    checked_value_sities.push(input_elements[i].value);
                }
            }
            user_form.append("cities", JSON.stringify(checked_value_sities));

            const res = await fetch("/giagram", {
                method: "POST",
                body: user_form,
            });
            console.log(res.status);
            // setTimeout(() => {
            // }, 2000);
            this.loading = false;
        },
        set_all_checkboxes: function (evt) {
            const all_checkboxes =
                evt.target.parentElement.parentElement.querySelectorAll(
                    ".site_input"
                );
            if (evt.target.checked) {
                all_checkboxes.forEach((elem) => {
                    elem.setAttribute("checked", "checked");
                });
            } else {
                all_checkboxes.forEach((elem) => {
                    elem.removeAttribute("checked");
                });
            }
        },
        show_checkboxes: function (evt) {
            const checkboxes =
                evt.target.parentElement.parentElement.querySelector(
                    ".checkboxes"
                );
            if (!this.expanded) {
                checkboxes.style.display = "block";
                this.expanded = true;
            } else {
                checkboxes.style.display = "none";
                this.expanded = false;
            }
        },
    },
};
</script>
<style>
.line__wrapper {
    display: flex;
    border-radius: 7px;
    width: 500px;
    height: 300px;
}

.line__item {
    background-color: rgba(255, 255, 255, 0.327);
    width: 500px;
    height: 300px;
    border-radius: 7px;
    margin-right: 7px;
    margin-bottom: 7px;

    display: flex;
    justify-content: center;
    align-items: center;
}
.doughnut__wrapper {
    background-color: rgba(255, 255, 255, 0.327);
    border-radius: 7px;
    padding-bottom: 10px;
    width: 230px;
}
.doughnut {
    width: 230px;
    height: 230px;
}
.diagramssss {
    height: calc(100vh - 70px);
}
.diagram__wrapper {
    margin-top: 7px;
    margin-right: 7px;

    height: calc(100vh - 124px);
    overflow: scroll;

    display: flex;
}
.filter__site {
    border: 1px #2196f3 solid;
    border-radius: 7px;
}
.mtable__option {
    display: flex;
    gap: 10px;
    padding-right: 10px;
}
.multiselect {
    position: relative;
}

.select-box {
    position: relative;
    width: 207px;
}

.select-box .mtable__select {
    width: 100%;
    font-weight: bold;
}
.over-select {
    position: absolute;
    left: 0;
    right: 0;
    top: 1px;
    bottom: 0;

    cursor: pointer;
}

.checkboxes {
    display: none;
    border: 1px #2196f3 solid;
    border-top: none;
    border-radius: 4px;
    background-color: #3b3f41;
    z-index: 1;
    position: absolute;

    height: 500px;
    overflow-y: scroll;
}

.checkboxes .filter__label {
    display: block;
    font-family: "Montserrat", sans-serif;
}
.over-select,
.city_input,
.mtable__select,
.mtable__option {
    font-family: "Montserrat", sans-serif;
    font-style: normal;
    font-weight: 500 !important;
}
.mtable__select {
    background-color: transparent;
    color: white;
    border: none;
    padding: 5px 3px;
    margin-left: -1px;
}
.mtable__heading-name {
    width: 104px;
}
.checkboxes .filter__label:hover {
    background-color: #1e90ff;
}
.checkboxes .checkboxes__label:nth-child(2) {
    padding-top: 10px;
}
.checkboxes__header {
    display: flex;
    padding: 5px;
    position: sticky;
    top: 0;
    background: #222628;
}
.checkboxes__label {
    padding: 5px;
}
.checkboxes-site {
    width: 267px;
    overflow-y: scroll;
}
.site__wrapper {
    color: white;
    padding: 5px;
    display: flex;
    align-items: center;
}
.search {
    outline: none;
    font-family: "Montserrat", sans-serif;
    border-radius: 4px;
    border-color: transparent;
}
.conversion__table {
    width: 500px;
    color: rgb(255, 255, 255);
    background: transition;
    border-radius: 7px;
    margin-bottom: 7px;
    margin-right: 7px;
    border: 1px solid rgba(255, 255, 255, 0.235);
}
.conversion__header,
.conversion__col {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    border-bottom: 1px solid rgba(255, 255, 255, 0.235);
    padding-left: 10px;
    transition: 0.2s;
    padding: 10px;
}
.conversion__col:hover {
    background: rgba(26, 103, 228, 0.231);
}
.conversion__header {
    background: #222628;
    border-radius: 7px;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}
.bm-option__button {
    background-color: transparent;
    border: none;
    cursor: pointer;

    margin-top: 2px;
}
.bm-option {
    display: flex;
    align-items: center;
}
</style>
