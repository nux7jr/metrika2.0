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
                        name="date-on"
                        id="date-on"
                        v-model="date.date_one"
                        @change="set_user_date()"
                    />
                </div>
            </div>
            <div class="info-option__button button-group">
                <button class="def__button" @click="get_date_grid()">
                    <span v-if="loading" class="loader"></span>
                    Показать данные
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
                :headerHeight="headerHeight"
                :floatingFiltersHeight="floatingFiltersHeight"
                :pivotGroupHeaderHeight="pivotGroupHeaderHeight"
                :pivotHeaderHeight="pivotHeaderHeight"
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
    name: "metrikaDay",
    data() {
        return {
            loading: false,

            columnDefs: [
                { field: "state", headerName: "Реклама" },
                {
                    field: "krsk_foolrs_xl_pipe",
                    headerName: "Красноярск ТП xl-pipe",
                    cellStyle: {
                        color: "black",
                        "background-color": "#efefef",
                    },
                },
                {
                    field: "krsk_foolrs_daewoo",
                    headerName: "Красноярск ТП daewoo",
                    cellStyle: {
                        color: "black",
                        "background-color": "#efefef",
                    },
                },
                {
                    field: "krsk_boilers",
                    headerName: "Красноярск Котлы",
                    cellStyle: {
                        color: "black",
                        "background-color": "#efefef",
                    },
                },
                {
                    field: "krsk_promboilers",
                    headerName: "Красноярск промкотлы",
                    cellStyle: {
                        color: "black",
                        "background-color": "#efefef",
                    },
                },
                {
                    field: "krsk_engineering",
                    headerName: "Красноярск Инжиниринг",
                    cellStyle: {
                        color: "black",
                        "background-color": "#efefef",
                    },
                },
                {
                    field: "msk_foolrs_xl_pipe",
                    headerName: "Москва ТП xl-pipe",
                    cellStyle: {
                        color: "black",
                        "background-color": "#EFCDB8",
                    },
                },
                {
                    field: "msk_foolrs_daewoo",
                    headerName: "Москва ТП daewoo",
                    cellStyle: {
                        color: "black",
                        "background-color": "#EFCDB8",
                    },
                },
                {
                    field: "msk_boilers",
                    headerName: "Москва Котлы",
                    cellStyle: {
                        color: "black",
                        "background-color": "#EFCDB8",
                    },
                },

                {
                    field: "dealers_foolrs_xl_pipe",
                    headerName: "Дилеры ТП xl-pipe",
                    cellStyle: {
                        color: "black",
                        "background-color": "#F5F5DC",
                    },
                },
                {
                    field: "dealers_foolrs_daewoo",
                    headerName: "Дилеры ТП daewoo",
                    cellStyle: {
                        color: "black",
                        "background-color": "#F5F5DC",
                    },
                },
                {
                    field: "dealers_franchisees",
                    headerName: "Дилерство полы",
                    cellStyle: {
                        color: "black",
                        "background-color": "#F5F5DC",
                    },
                },

                {
                    field: "nanofiber_franchisees_sng",
                    headerName: "NanoFiber Франшиза СНГ",
                    cellStyle: {
                        color: "black",
                        "background-color": "#C7FFFD",
                    },
                },
                {
                    field: "nanofiber_franchisees_world",
                    headerName: "NanoFiber Франшиза Зарубежные страны",
                    cellStyle: {
                        color: "black",
                        "background-color": "#C7FFFD",
                    },
                },

                {
                    field: "krsk_etaji",
                    headerName: "Малые этажи Красноярск",
                    cellStyle: {
                        color: "black",
                        "background-color": "#CCBCDC",
                    },
                },
                {
                    field: "dealers_etaji",
                    headerName: "Малые этажи Регионы",
                    cellStyle: {
                        color: "black",
                        "background-color": "#CCBCDC",
                    },
                },
                {
                    field: "tumen_etaji",
                    headerName: "Малые этажи Тюмень",
                    cellStyle: {
                        color: "black",
                        "background-color": "#CCBCDC",
                    },
                },
                {
                    field: "irkutsk_etaji",
                    headerName: "Малые этажи Иркутск",
                    cellStyle: {
                        color: "black",
                        "background-color": "#CCBCDC",
                    },
                },
                {
                    field: "vladivostok_etaji",
                    headerName: "Малые этажи Владивосток",
                    cellStyle: {
                        color: "black",
                        "background-color": "#CCBCDC",
                    },
                },
                {
                    field: "perm_etaji",
                    headerName: "Малые этажи Пермь",
                    cellStyle: {
                        color: "black",
                        "background-color": "#CCBCDC",
                    },
                },
                {
                    field: "ekb_etaji",
                    headerName: "Малые этажи Екатеринбург",
                    cellStyle: {
                        color: "black",
                        "background-color": "#CCBCDC",
                    },
                },
                {
                    field: "barnaul_etaji",
                    headerName: "Малые этажи Барнаул",
                    cellStyle: {
                        color: "black",
                        "background-color": "#CCBCDC",
                    },
                },

                {
                    field: "tiksan_auto",
                    headerName: "Тиксан авто только LP1",
                    cellStyle: {
                        color: "black",
                        "background-color": "#F3C9CA",
                    },
                },
                {
                    field: "tiksan_auto_main",
                    headerName: "Тиксан авто Федеральный",
                    cellStyle: {
                        color: "black",
                        "background-color": "#F3C9CA",
                    },
                },
            ],
            localeText: null,

            date: {
                date_one: "",
            },
            model: {},

            gridApi: null,
            columnApi: null,
            rowData: null,
            rowSelection: null,
            getRowId: null,

            overlayLoadingTemplate: null,

            headerHeight: null,
            floatingFiltersHeight: null,
            pivotGroupHeaderHeight: null,
            pivotHeaderHeight: null,
        };
    },

    computed: {},
    created() {
        this.overlayLoadingTemplate =
            '<span class="ag-overlay-loading-center loader"></span>';

        this.headerHeight = 350;
        this.floatingFiltersHeight = 50;
        this.pivotGroupHeaderHeight = 50;
        this.pivotHeaderHeight = 100;

        this.check_user_date();
        this.rowSelection = "multiple";
        this.localeText = lang;
        this.get_date_grid();
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
                date_one: targetDate.toISOString().split("T")[0],
            };
            sessionStorage.setItem("date_one", JSON.stringify(this.date));
        },
        check_user_date() {
            if (sessionStorage.getItem("date_one")) {
                const localDate = JSON.parse(
                    sessionStorage.getItem("date_one")
                );
                this.date = {
                    date_one: localDate.date_one,
                };
            } else {
                this.get_date_now();
            }
        },
        set_user_date() {
            sessionStorage.setItem("date_one", JSON.stringify(this.date));
        },
        get_date_grid() {
            this.loading = true;
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
                    date_on: this.date.date_one,
                }),
            })
                .then((resp) => resp.json())
                .then((data) => {
                    if (data.error) {
                        this.gridApi.setRowData([]);
                        this.loading = false;
                    } else {
                        this.gridApi.setRowData(data);
                        this.loading = false;
                    }
                });
        },
        on_grid_ready(params) {
            this.gridApi = params.api;
            this.gridColumnApi = params.columnApi;
        },
    },
    components: {
        "ag-grid-vue": AgGridVue,
    },
};
</script>

<style scoped>
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
.info ~ .ag-group-title-bar {
    background-color: transparent;
}
.info ~ .ag-side-button-label,
.info ~ .ag-text-field-input {
    font-family: "Montserrat", sans-serif;
}
.info ~ .ag-standard-button {
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

.info ~ .ag-header-cell-label {
    height: 100%;
    padding: 0 !important;
}

.ag-header-cell-label .ag-header-cell-text {
    width: 55px;
    writing-mode: vertical-lr !important;
    -ms-writing-mode: tb-lr !important;
    line-height: 2em;
    margin-top: 60px;
}
.info ~ .ag-pivot-off .ag-header-group-cell {
    font-size: 50px;
}
.info ~ .ag-pivot-on .ag-header-group-cell {
    font-size: 10px;
    color: green;
}
.info ~ .ag-pivot-off .ag-header-cell-label {
    color: #ffffff;
}
.info ~ .ag-pivot-on .ag-header-cell-label {
    font-size: 10px;
    height: 90px;
    padding-top: 36px;
    margin-left: 0px;
    color: #1b6d85;
    font-weight: bold;
}

.info ~ .ag-pivot-on .ag-header-cell-label .ag-header-cell-text {
    margin-top: 25px;
}
.info ~ .ag-floating-filter-body {
    height: 50px;
}
</style>
