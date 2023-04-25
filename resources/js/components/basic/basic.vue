<template>
    <div ref="info" class="info">
        <div class="info-option">
            <div class="info-option__button button-group">
                <button class="def__button" v-on:click="clearFilters()">
                    Сброс всех фильтров
                </button>
                <button
                    class="def__button"
                    v-on:click="this.gridApi.exportDataAsExcel()"
                >
                    Скачать выбранные лиды (exel)
                </button>
                <button @click="getFilterModel">getFilterModel</button>
            </div>
            <div class="info-option__text">text-info:</div>
        </div>
        <div class="main-table">
            <ag-grid-vue
                style="width: 100%; height: calc(100vh - 110px)"
                class="ag-theme-alpine main-table__wrapper"
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
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-alpine.css";
import "ag-grid-enterprise";
import { AgGridVue } from "ag-grid-vue3";
import { lang } from "../../locale/ru.js";
export default {
    name: "metrikaBasic",
    data() {
        return {
            info: {},
            numberIndex: 40,
            localeText: null,
            columnDefs: [
                {
                    field: "ID",
                    rowDrag: false,
                    maxWidth: 90,
                    filter: "agNumberColumnFilter",
                    sortable: false,
                },
                {
                    field: "DATE",
                    filter: "agDateColumnFilter",
                    filterParams: filterParams,
                },
                { field: "PHONE" },
                { field: "EMAIL" },
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
        };
    },

    created() {
        // this.sideBar = "filters";
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
            // hiddenByDefault: true,
            position: "left",
            defaultToolPanel: "filters",
        };
    },
    methods: {
        clearFilters() {
            this.gridApi.setFilterModel(null);
        },

        onGridReady(params) {
            this.loading = true;
            this.gridApi = params.api;
            this.gridColumnApi = params.columnApi;

            const updateData = (data) => params.api.setRowData(data);

            fetch("/print.php")
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
    // maxValidYear: 2021,
    inRangeFloatingFilterDateFormat: "Do MMM YYYY",
};

// end filter
</script>

<style>
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
    height: calc(100vh - 70px);
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
</style>
