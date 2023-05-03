<template>
    <div ref="info" class="info">
        <div class="info-option">
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
                        @change="set_user_date()"
                    />
                </div>
            </div>
            <div class="info-option__button button-group">
                <button class="def__button" @click="get_date_grid()">
                    <span v-if="loading" class="loader"></span>
                    Показать данные
                </button>
                <button class="def__button" @click="clear_filters()">
                    Сброс всех фильтров
                </button>

                <button class="def__button" @click="get_export_all()">
                    Скачать всё
                </button>
            </div>
        </div>

        <div class="main-table">
            <ag-grid-vue
                class="ag-theme-alpine-dark main-table__wrapper"
                @grid-ready="on_grid_ready"
                :localeText="localeText"
                :rowDragManaged="true"
                :rowDragMultiRow="true"
                :rowSelection="rowSelection"
                :animateRows="true"
                :copyHeadersToClipboard="true"
                :suppressRowClickSelection="true"
                :overlayLoadingTemplate="overlayLoadingTemplate"
                :rowData="rowData"
                :columnDefs="columnDefs"
            ></ag-grid-vue>
        </div>
    </div>
</template>
<script>
// import "../../adGrid/ag-grid-enterprise";
import { AgGridVue } from "ag-grid-vue3";
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-alpine.css";
import { lang } from "../../locale/ru.js";

import "ag-grid-enterprise";

export default {
    name: "metrikaBasic",
    data() {
        return {
            loading: false,

            rowData: null,
            columnDefs: [
                { field: "state", headerName: "Реклама" },
                { field: "krsk_foolrs", headerName: "krsk_foolrs" },
                { field: "msk_foolrs", headerName: "Москва ТП" },
                { field: "dealers_foolrs", headerName: "Дилеры ТП" },
                { field: "krsk_boilers", headerName: "Красноярск Котлы" },

                {
                    field: "krsk_promboilers",
                    headerName: "Красноярск промкотлы",
                },
                { field: "msk_boilers", headerName: "Москва Котлы" },
                { field: "dealers_franchisees", headerName: "Дилерство полы" },
                {
                    field: "nanofiber_franchisees_sng",
                    headerName: "NanoFiber Франшиза СНГ",
                },
                {
                    field: "nanofiber_franchisees_world",
                    headerName: "NanoFiber Франшиза Зарубежные страны",
                },
                { field: "krsk_etaji", headerName: "Малые этажи Красноярск" },
                { field: "dealers_etaji", headerName: "Малые этажи Регионы" },

                { field: "tumen_etaji", headerName: "Малые этажи Тюмень" },
                { field: "irkutsk_etaji", headerName: "Малые этажи Иркутск" },
                {
                    field: "vladivostok_etaji",
                    headerName: "Малые этажи Владивосток",
                },
                { field: "perm_etaji", headerName: "Малые этажи Пермь" },
                { field: "ekb_etaji", headerName: "Малые этажи Екатеринбург" },
                { field: "barnaul_etaji", headerName: "Малые этажи Барнаул" },
                { field: "tiksan_auto", headerName: "Тиксан авто только LP1" },
            ],
            localeText: null,
            gridApi: null,
            columnApi: null,
            rowSelection: null,
            date: {
                date_on: "",
            },
            model: {},
            bar: {
                header: {
                    krsk_foolrs: "Красноярск ТП",
                    msk_foolrs: "Москва ТП",
                    dealers_foolrs: "Дилеры ТП",
                    krsk_boilers: "Красноярск Котлы",
                    krsk_promboilers: "Красноярск промкотлы",
                    msk_boilers: "Москва Котлы",
                    dealers_franchisees: "Дилерство полы",
                    nanofiber_franchisees_sng: "NanoFiber Франшиза СНГ",
                    nanofiber_franchisees_world:
                        "NanoFiber Франшиза Зарубежные страны",
                    krsk_etaji: "Малые этажи Красноярск",
                    dealers_etaji: "Малые этажи Регионы",
                    tumen_etaji: "Малые этажи Тюмень",
                    irkutsk_etaji: "Малые этажи Иркутск",
                    vladivostok_etaji: "Малые этажи Владивосток",
                    perm_etaji: "Малые этажи Пермь",
                    ekb_etaji: "Малые этажи Екатеринбург",
                    barnaul_etaji: "Малые этажи Барнаул",
                    tiksan_auto: "Тиксан авто только LP1",
                },
                utm: {
                    krsk_foolrs: 8,
                    msk_foolrs: 0,
                    dealers_foolrs: 52,
                    krsk_boilers: 9,
                    krsk_promboilers: 0,
                    msk_boilers: 33,
                    dealers_franchisees: 0,
                    nanofiber_franchisees_sng: 0,
                    nanofiber_franchisees_world: 0,
                    krsk_etaji: 8,
                    dealers_etaji: 21,
                    tumen_etaji: 4,
                    irkutsk_etaji: 2,
                    vladivostok_etaji: 4,
                    perm_etaji: 1,
                    ekb_etaji: 10,
                    barnaul_etaji: 5,
                    tiksan_auto: 1,
                },
                noutm: {
                    krsk_foolrs: 9,
                    msk_foolrs: 0,
                    dealers_foolrs: 52,
                    krsk_boilers: 9,
                    krsk_promboilers: 0,
                    msk_boilers: 33,
                    dealers_franchisees: 0,
                    nanofiber_franchisees_sng: 0,
                    nanofiber_franchisees_world: 0,
                    krsk_etaji: 10,
                    dealers_etaji: 21,
                    tumen_etaji: 4,
                    irkutsk_etaji: 2,
                    vladivostok_etaji: 4,
                    perm_etaji: 1,
                    ekb_etaji: 10,
                    barnaul_etaji: 5,
                    tiksan_auto: 5,
                },
            },
        };
    },

    computed: {},
    created() {
        this.rowData = [
            {
                state: "utm",
                krsk_foolrs: 8,
                msk_foolrs: 0,
                dealers_foolrs: 52,
                krsk_boilers: 9,
                krsk_promboilers: 0,
                msk_boilers: 33,
                dealers_franchisees: 0,
                nanofiber_franchisees_sng: 0,
                nanofiber_franchisees_world: 0,
                krsk_etaji: 8,
                dealers_etaji: 21,
                tumen_etaji: 4,
                irkutsk_etaji: 2,
                vladivostok_etaji: 4,
                perm_etaji: 1,
                ekb_etaji: 10,
                barnaul_etaji: 5,
                tiksan_auto: 1,
            },
            {
                // state: "noutm",
                krsk_foolrs: 9,
                msk_foolrs: 0,
                dealers_foolrs: 52,
                krsk_boilers: 9,
                krsk_promboilers: 0,
                msk_boilers: 33,
                dealers_franchisees: 0,
                nanofiber_franchisees_sng: 0,
                nanofiber_franchisees_world: 0,
                krsk_etaji: 10,
                dealers_etaji: 21,
                tumen_etaji: 4,
                irkutsk_etaji: 2,
                vladivostok_etaji: 4,
                perm_etaji: 1,
                ekb_etaji: 10,
                barnaul_etaji: 5,
                tiksan_auto: 5,
            },
        ];

        // this.check_user_date();
        this.rowSelection = "multiple";
        this.localeText = lang;
        this.overlayLoadingTemplate =
            '<span class="ag-overlay-loading-center loader"></span>';
    },
    methods: {
        get_export_all() {
            this.gridApi.exportDataAsExcel();
        },
        get_export_checked_box() {
            this.gridApi.exportDataAsExcel({
                onlySelected: document.querySelector("#selectedOnly").checked,
            });
        },
        get_date_now() {
            const targetDate = new Date(Date.now() - 86400000);
            this.date = {
                date_on: targetDate.toISOString().split("T")[0],
            };
            sessionStorage.setItem("date", JSON.stringify(this.date));
        },
        check_user_date() {
            if (sessionStorage.getItem("date")) {
                const localDate = JSON.parse(sessionStorage.getItem("date"));
                this.date = {
                    date_on: localDate.date_on,
                };
            } else {
                this.get_date_now();
            }
        },
        set_user_date() {
            sessionStorage.setItem("date", JSON.stringify(this.date));
        },
        get_date_grid() {
            this.gridApi = params.api;
            this.gridColumnApi = params.columnApi;
            // this.loading = true;
            // this.gridApi.showLoadingOverlay();
            // document.getElementById("selectedOnly").checked = true;

            // let token = document
            //     .querySelector('meta[name="csrf-token"]')
            //     .getAttribute("content");
            // const localDate = JSON.parse(sessionStorage.getItem("date"));
            // const userFormDate = new FormData();
            // userFormDate.append("date_on", localDate.date_on);
            // fetch("/get_daily_report", {
            //     method: "POST",
            //     headers: {
            //         "X-CSRF-TOKEN": token,
            //         "Content-Type": "application/json",
            //         "X-Requested-With": "XMLHttpRequest",
            //     },
            //     body: JSON.stringify({
            //         date_on: this.date.date_on,
            //     }),
            // })
            //     .then((resp) => resp.json())
            //     .then((data) => {
            //         this.loading = false;
            //         this.gridApi.hideOverlay();
            //         this.gridApi.setRowData(data);
            //     });
            // this.gridApi.setRowData(this.bar);
        },
        on_grid_ready(params) {
            this.gridApi = params.api;
            this.gridColumnApi = params.columnApi;
            const updateData = (data) => params.api.setRowData(data);
            let token = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            fetch("/get_daily_report", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": token,
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify({
                    date_on: "2023.05.01",
                    // date_on: this.date.date_on,
                }),
            })
                .then((resp) => resp.json())
                .then((data) => updateData(data));

            // fetch("/get_daily_report", {
            //     method: "POST",
            //     headers: {
            //         "X-CSRF-TOKEN": token,
            //         "Content-Type": "application/json",
            //         "X-Requested-With": "XMLHttpRequest",
            //     },
            //     body: JSON.stringify({
            //         date_on: this.date.date_on,
            //     }),
            // })
            //     .then((resp) => resp.json())
            //     .then((data) => updateData(data));
        },
    },
    components: {
        "ag-grid-vue": AgGridVue,
    },
};
</script>

<style>
.ag-theme-alpine-dark {
    --ag-border-radius: 7px;

    --main-color: #3b3f41;
    --second-color: #2b2b2b;
    --third-color: #323336;

    --ag-background-color: #323336;
    --ag-odd-row-background-color: #323336;
}
@keyframes rotation {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
.loader {
    width: 17px;
    height: 17px;
    border: 2px solid #fff;
    border-bottom-color: transparent;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
}

.ag-body-viewport-wrapper.ag-layout-normal {
    overflow-x: scroll;
    overflow-y: scroll;
}
::-webkit-scrollbar {
    -webkit-appearance: none;
    width: 8px;
    height: 8px;
    background: #212425b0;
}
::-webkit-scrollbar-thumb {
    border-radius: 4px;
    background-color: #080909;
    box-shadow: 0 0 1px rgba(26, 103, 228, 0.953);
}
.main-table {
    margin-right: 7px;
    margin-bottom: 7px;
}

.main-table__wrapper {
    width: 100%;
    height: calc(100vh - 124px);
}
.ag-header {
    border-bottom: none;
}
.button-group {
    display: flex;
    gap: 5px;
    max-width: fit-content;
}
.def__button {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    padding: 8px 14px;
    gap: 9px;

    font-style: normal;
    font-weight: 600;
    font-size: 14px;
    color: #fcfcfc;
    background: #2196f3;

    border-radius: 7px;
    border-color: transparent;

    flex: none;
    order: 0;
    flex-grow: 1;

    cursor: pointer;

    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
        border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.def__button:hover {
    background: #1a7bca;
}
.ag-root-wrapper {
    border-radius: 7px;
}
.ag-group-title-bar {
    background-color: transparent;
}
.ag-side-button-label,
.ag-text-field-input {
    font-family: "Montserrat", sans-serif;
}
.ag-standard-button {
    cursor: pointer;
}
.info-option {
    display: flex;
    align-items: center;
    gap: 20px;
    padding-bottom: 7px;
}
.info-filters {
    display: flex;
    gap: 20px;
}
.filter__input {
    font-family: "Montserrat", sans-serif;
    padding: 8px;
    border-radius: 7px;
    border-color: transparent;
    border: 1px #2196f3 solid;

    outline: none;

    color: white;
    background: transparent;

    color-scheme: dark;
}
.filter__label {
    color: white;
}
.filter__item {
    display: flex;
    gap: 10px;
    align-items: center;
}
.option-hidden {
    display: none;
}
</style>
