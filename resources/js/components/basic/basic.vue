<template>
    <div ref="info" class="info">
        <div class="info-option">
            <div class="info-filters">
                <div class="filter__input">
                    <label for="date-off">date-off</label>
                    <input type="date" name="date-off" id="date-off" />
                </div>
                <div class="filter__input">
                    <label for="date-on">date-off</label>
                    <input type="date" name="date-on" id="date-on" />
                </div>
            </div>
            <div class="info-option__button button-group">
                <button class="def__button" v-on:click="clearFilters()">
                    Показать данные
                </button>
                <button class="def__button" v-on:click="clearFilters()">
                    Сброс всех фильтров
                </button>
                <button
                    class="def__button"
                    v-on:click="this.gridApi.exportDataAsExcel()"
                >
                    Скачать выбранные лиды (exel)
                </button>
                <button @click="getFilterModel()">getFilterModel</button>
                <!-- <button @click="getUserFilter()">getFilterModel</button> -->
            </div>
            <!-- <div class="info-option__text">text-info:</div>
            <div @click="justShow()">justShow</div> -->
        </div>
        <div class="main-table">
            <ag-grid-vue
                style="width: 100%; height: calc(100vh - 124px)"
                class="ag-theme-alpine-dark main-table__wrapper"
                :columnDefs="columnDefs"
                @grid-ready="onGridReady"
                :localeText="localeText"
                :defaultColDef="defaultColDef"
                :rowDragManaged="true"
                :rowDragMultiRow="true"
                :rowSelection="rowSelection"
                :animateRows="true"
                :rowData="rowData"
                :copyHeadersToClipboard="true"
                :sideBar="sideBar"
            ></ag-grid-vue>
            <!-- debounceVerticalScrollbar="true" -->
        </div>
    </div>
</template>

<script>
// import "../../adGrid/ag-grid-enterprise";

import { AgGridVue } from "ag-grid-vue3";
import "ag-grid-community/styles//ag-grid.css";
import "ag-grid-community/styles//ag-theme-alpine.css";
import { lang } from "../../locale/ru.js";

import "ag-grid-enterprise";

export default {
    name: "metrikaBasic",
    data() {
        return {
            localeText: null,
            columnDefs: [
                {
                    field: "ID",
                    headerName: "id",
                    rowDrag: false,
                    width: 120,
                    filter: "agNumberColumnFilter",
                    sortable: false,
                },
                {
                    field: "DATE",
                    headerName: "Дата",
                    filter: "agDateColumnFilter",
                    filterParams: filterParams,
                },
                { field: "PHONE", headerName: "Телефон" },
                { field: "EMAIL", headerName: "Емейл" },
                { field: "UTM_SOURCE" },
                { field: "UTM_MEDIUM" },
                { field: "UTM_CAMPAIGN" },
                { field: "UTM_TERM" },
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
            sideBar: null,

            model: {},
        };
    },

    created() {
        this.rowSelection = "multiple";
        this.localeText = lang;

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
            defaultToolPanel: "filters",
        };

        this.getDateNow();
    },
    methods: {
        saveFilter() {
            if (localStorage.getItem("filter")) {
                console.log("est");
            } else {
                console.log("nety");
            }
        },
        justShow() {
            console.log(this.model);
        },
        clearFilters() {
            this.model = {};
            localStorage.setItem("filter", "");
            this.gridApi.setFilterModel(null);
        },

        getDateNow() {
            let now = new Date();
            let prevDate = new Date(
                now.getFullYear(),
                now.getMonth(),
                now.getDate() - 21
            )
                .toISOString()
                .split("T")[0];
            let thisDate = now.toISOString().split("T")[0];
            // localStorage.setItem("prevWeek", prevDate);
            // localStorage.setItem("thisWeek", thisDate);
            const date = {
                date_off: thisDate,
                date_on: prevDate,
            };
            localStorage.setItem("date", JSON.stringify(date));
        },
        onGridReady(params) {
            this.loading = true;
            this.gridApi = params.api;
            this.gridColumnApi = params.columnApi;

            const updateData = (data) => params.api.setRowData(data);
            let token = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            const localDate = JSON.parse(localStorage.getItem("date"));

            console.log(localDate.date_on);
            console.log(localDate.date_off);

            const userFormDate = new FormData();
            userFormDate.append("date_on", localDate.date_on);
            userFormDate.append("date_off", localDate.date_off);

            fetch("/get_leads", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": token,
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    // Accept: "application/json, text-plain, */*",
                },
                body: JSON.stringify({
                    "date_on":localDate.date_on,
                    "date_off":localDate.date_off
                }),
            })
                .then((resp) => resp.json())
                .then((data) => updateData(data));
        },
    },
    components: {
        "ag-grid-vue": AgGridVue,
    },
};
// filter
const filterParams = {
    comparator: (filterLocalDateAtMidnight, cellValue) => {
        const dateAsString = cellValue;
        if (dateAsString == null) return -1;
        const dateParts = dateAsString.split("/");
        const cellDate = new Date(
            Number(dateParts[2]),
            Number(dateParts[1]) - 1,
            Number(dateParts[0])
        );
        if (filterLocalDateAtMidnight.getTime() === cellDate.getTime()) {
            return 0;
        }
        if (cellDate < filterLocalDateAtMidnight) {
            return -1;
        }
        if (cellDate > filterLocalDateAtMidnight) {
            return 1;
        }
        return 0;
    },
    minValidYear: 2015,
    inRangeFloatingFilterDateFormat: "Do MMM YYYY",
};
// end filter
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

.ag-root-wrapper.ag-layout-normal {
    border-radius: 5px;
    border-color: whitesmoke;
}

.ag-body-viewport-wrapper.ag-layout-normal {
    overflow-x: scroll;
    overflow-y: scroll;
}
::-webkit-scrollbar {
    -webkit-appearance: none;
    width: 8px;
    height: 8px;
    background: whitesmoke;
}
::-webkit-scrollbar-thumb {
    border-radius: 4px;
    background-color: rgba(33, 33, 33, 0.5);
    box-shadow: 0 0 1px rgba(202, 199, 199, 0.953);
}
.main-table {
    margin-right: 7px;
    margin-bottom: 7px;
}

.main-table__wrapper {
    width: 100%;
    height: calc(100vh - 108px);
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
    gap: 16px;

    font-style: normal;
    font-weight: 600;
    font-size: 16px;
    color: #fcfcfc;
    background: #8dccec;

    border-radius: 3px;
    border-color: transparent;

    flex: none;
    order: 0;
    flex-grow: 1;

    cursor: pointer;

    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
        border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.ag-side-button-label {
    font-family: "Montserrat", sans-serif;
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
</style>
