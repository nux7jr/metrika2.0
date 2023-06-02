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
            </div>
        </div>
        <div class="main-table">
            <ag-grid-vue
                class="ag-theme-alpine-dark main-table__wrapper"
                :columnDefs="columnDefs"
                @grid-ready="on_grid_ready"
                :localeText="localeText"
                :defaultColDef="defaultColDef"
                :rowDragManaged="true"
                :rowDragMultiRow="true"
                :rowSelection="rowSelection"
                :animateRows="true"
                :rowData="rowData"
                :copyHeadersToClipboard="true"
                :sideBar="sideBar"
                :statusBar="statusBar"
                :suppressRowClickSelection="true"
                :overlayLoadingTemplate="overlayLoadingTemplate"
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
    name: "metrikaVisitons",
    data() {
        return {
            loading: false,

            localeText: null,
            columnDefs: [
                {
                    field: "direction",
                    headerName: "direction",
                    rowDrag: false,
                    rowGroup: true,
                    hide: false,
                    width: 250,
                },
                {
                    field: "utm_source",
                    headerName: "utm_source",
                    rowDrag: false,
                    rowGroup: true,
                    hide: false,
                    width: 140,
                },

                {
                    field: "stage_now",
                    headerName: "stage_now",
                    rowDrag: false,
                    rowGroup: true,
                    hide: false,
                    width: 200,
                },
                {
                    field: "utm_medium",
                    headerName: "utm_medium",
                    rowDrag: false,
                    rowGroup: true,
                    hide: false,
                    width: 140,
                },

                {
                    field: "utm_campaign",
                    headerName: "utm_campaign",
                    rowDrag: false,
                    width: 160,
                    rowGroup: false,
                    hide: false,
                },
                {
                    field: "utm_content",
                    headerName: "utm_content",
                    rowDrag: false,
                    width: 160,
                    rowGroup: false,
                    hide: false,
                },
                {
                    field: "utm_term",
                    headerName: "utm_term",
                    rowDrag: false,
                    width: 160,
                    rowGroup: false,
                    hide: false,
                },
                {
                    field: "url",
                    headerName: "url",
                    rowDrag: false,
                    width: 160,
                    rowGroup: false,
                    hide: false,
                },
                {
                    field: "city",
                    headerName: "city",
                    rowDrag: false,
                    width: 160,
                    rowGroup: false,
                    hide: false,
                },
                {
                    field: "stage_now",
                    headerName: "stage_now",
                    rowDrag: false,
                    width: 160,
                    rowGroup: false,
                    hide: false,
                },
                {
                    field: "income",
                    headerName: "income",
                    rowDrag: false,
                    width: 160,
                    rowGroup: false,
                    hide: false,
                },
                {
                    field: "currency",
                    headerName: "currency",
                    rowDrag: false,
                    width: 160,
                    rowGroup: false,
                    hide: false,
                },
                {
                    field: "phone",
                    headerName: "phone",
                    rowDrag: false,
                    width: 160,
                    rowGroup: false,
                    hide: false,
                },
                {
                    field: "created_at",
                    headerName: "created_at",
                    rowDrag: false,
                    width: 160,
                    rowGroup: false,
                    hide: false,
                },
                {
                    field: "updated_at",
                    headerName: "updated_at",
                    rowDrag: false,
                    width: 160,
                    rowGroup: false,
                    hide: false,
                },
                {
                    field: "is_adv",
                    headerName: "is_adv",
                    rowDrag: false,
                    width: 160,
                    rowGroup: false,
                    hide: false,
                },
            ],
            gridApi: null,
            columnApi: null,
            defaultColDef: {
                editable: true,
                sortable: true,
                filter: true,
                filterParams: { buttons: ["reset", "apply"] },
                width: 250,
            },
            rowData: null,
            rowSelection: null,
            sideBar: null,
            date: {
                date_on: "",
                date_off: "",
            },
            model: {},
        };
    },

    computed: {},
    created() {
        this.check_user_date();
        this.rowSelection = "multiple";
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
        get_export_all() {
            this.gridApi.exportDataAsExcel();
        },
        get_export_checked_box() {
            this.gridApi.exportDataAsExcel({
                onlySelected: document.querySelector("#selectedOnly").checked,
            });
        },
        clear_filters() {
            this.gridApi.setFilterModel(null);
        },
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
        get_date_grid() {
            // this.loading = true;
            this.gridApi.showLoadingOverlay();
            document.getElementById("selectedOnly").checked = true;

            let token = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            const localDate = JSON.parse(sessionStorage.getItem("date"));
            const userFormDate = new FormData();
            userFormDate.append("date_on", localDate.date_on);
            userFormDate.append("date_off", localDate.date_off);
            fetch("/get_deals", {
                method: "GET",
                // headers: {
                //     "X-CSRF-TOKEN": token,
                //     "Content-Type": "application/json",
                //     "X-Requested-With": "XMLHttpRequest",
                // },
                // body: JSON.stringify({
                //     date_on: this.date.date_on,
                //     date_off: this.date.date_off,
                // }),
            })
                .then((resp) => resp.json())
                .then((data) => {
                    this.gridApi.setRowData(data);
                    // this.loading = false;
                    this.gridApi.hideOverlay();
                });
        },
        on_grid_ready(params) {
            document.getElementById("selectedOnly").checked = true;
            this.gridApi = params.api;
            this.gridColumnApi = params.columnApi;
            const updateData = (data) => params.api.setRowData(data);
            let token = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            fetch("/get_deals", {
                method: "GET",
                // headers: {
                //     "X-CSRF-TOKEN": token,
                //     "Content-Type": "application/json",
                //     "X-Requested-With": "XMLHttpRequest",
                // },
                // body: JSON.stringify({
                //     date_on: this.date.date_on,
                //     date_off: this.date.date_off,
                // }),
            })
                .then((resp) => resp.json())
                .then((data) => updateData(data));
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
