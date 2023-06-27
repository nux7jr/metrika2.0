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
                <div class="filter__item">
                    <label class="filter__label" for="date-on">До</label>
                    <input
                        class="filter__input"
                        type="date"
                        name="date-on"
                        id="date-on"
                        @change="set_user_date()"
                        v-model="date.date_off"
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
                <button class="def__button" @click="get_export_checked_box()">
                    Скачать выбранные строки
                </button>
                <button class="def__button" @click="get_export_all()">
                    Скачать всё
                </button>
                <input
                    class="option-hidden"
                    id="selectedOnly"
                    type="checkbox"
                    checked
                />
                <button v-on:click="onRowDataA()">Row Data A</button>
                <button v-on:click="onRowDataB()">Row Data B</button>
            </div>
        </div>
        <div class="main-table">
            <ag-grid-vue
                class="ag-theme-alpine-dark main-table__wrapper"
                :localeText="localeText"
                :rowDragManaged="true"
                :rowDragMultiRow="true"
                :columnDefs="columnDefs"
                @grid-ready="onGridReady"
                :rowData="rowData"
                :rowSelection="rowSelection"
                :animateRows="true"
                :copyHeadersToClipboard="true"
                :sideBar="sideBar"
                :statusBar="statusBar"
                :overlayLoadingTemplate="overlayLoadingTemplate"
                :defaultColDef="defaultColDef"
            ></ag-grid-vue>
        </div>
    </div>
</template>
<script>
const rowDataA = [
    { make: "Toyota", model: "Celica", price: 35000 },
    { make: "Porsche", model: "Boxster", price: 72000, stageY: "stageY" },
    {
        make: "Aston Martin",
        model: "DBX",
        price: 190000,
        stageX: "stageY",
        model31: "model31",
    },
];

const rowDataB = [
    { make: "Toyota", model: "Celica", price: 35000 },
    { make: "Ford", model: "Mondeo", price: 32000 },
    { make: "Porsche", model: "Boxster", price: 72000, stage2: "stage2" },
    { make: "BMW", model: "M50", price: 60000 },
    {
        make: "Aston Martin",
        model: "DBX",
        price: 190000,
        make: "Aston Martin",
        model: "DBX",
        stage1: "stage1",
    },
];

import { AgGridVue } from "ag-grid-vue3";
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-alpine.css";
import { lang } from "../../locale/ru.js";

import "ag-grid-enterprise";

export default {
    name: "metrikaDeal",
    data() {
        return {
            loading: false,
            localeText: null,
            defaultColDef: {
                // set every column width
                width: 100,
                // make every column editable
                editable: true,
                // make every column use 'text' filter by default
                filter: "agTextColumnFilter",
            },
            columnDefs: [
                { field: "make" },
                { field: "model" },
                { width: 100, editable: true, filter: "agTextColumnFilter" },
                { width: 100, editable: true, filter: "agTextColumnFilter" },
                { width: 100, editable: true, filter: "agTextColumnFilter" },
                { width: 100, editable: true, filter: "agTextColumnFilter" },
                { width: 100, editable: true, filter: "agTextColumnFilter" },
                { width: 100, editable: true, filter: "agTextColumnFilter" },
                // { field: "price" },
                // { field: "stage1" },
                // { field: "stage2" },
                // { field: "stageY" },
                // { field: "stageX" },
            ],
            // columnDefsB: [
            //     { field: "make" },
            //     { field: "model" },
            //     { field: "price" },
            // ],
            gridApi: null,
            columnApi: null,

            rowData: null,
            rowSelection: null,
            date: {
                date_on: "",
                date_off: "",
            },
        };
    },

    computed: {},
    created() {
        this.check_user_date();
        this.rowData = rowDataA;
        this.rowSelection = "single";
        this.localeText = lang;
        this.statusBar = {
            statusPanels: [
                {
                    statusPanel: "agTotalAndFilteredRowCountComponent",
                    align: "left",
                },
                { statusPanel: "agTotalRowCountComponent", align: "center" },
                { statusPanel: "agFilteredRowCountComponent" },
                { statusPanel: "agSelectedRowCountComponent" },
                { statusPanel: "agAggregationComponent" },
            ],
        };
        this.sideBar = {
            toolPanels: [
                {
                    id: "filters",
                    labelDefault: "Filters",
                    labelKey: "filters",
                    iconKey: "filter",
                    toolPanel: "agFiltersToolPanel",
                    minWidth: 180,
                    maxWidth: 400,
                    width: 250,
                },
            ],
            position: "left",
            defaultToolPanel: "",
        };
        this.overlayLoadingTemplate =
            '<span class="ag-overlay-loading-center loader"></span>';
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
        onRowDataA() {
            this.gridApi.setRowData(rowDataA);
        },
        onRowDataB() {
            this.gridApi.setRowData(rowDataB);
        },
        onGridReady(params) {
            this.gridApi = params.api;
            this.gridColumnApi = params.columnApi;
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
