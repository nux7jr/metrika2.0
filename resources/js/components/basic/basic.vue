<template>
    <div ref="info" class="info">
        <!-- @wheel="wheelget_data" -->
        <!-- <div class="info__heading">old lead</div>
        <form class="info__search search">
            <label class="search__label" for="search">Поиск по таблице</label>
            <input
                class="search__input"
                type="search"
                name="search"
                id="search"
                placeholder="Поиск"
            />
        </form> -->
        <div class="main-table">
            <ag-grid-vue
                style="width: 100%; height: 600px"
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
                    filterType: "number",
                    sortable: false,
                },
                { field: "DATE", filter: "agDateColumnFilter", width: 100 },
                { field: "CITY", width: 150 },
                { field: "SITE" },
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
            },
            rowData: null,
            statusBar: {
                statusPanels: [
                    {
                        statusPanel: "agTotalAndFilteredRowCountComponent",
                        align: "left",
                    },
                ],
            },
        };
    },

    created() {
        this.rowSelection = "multiple";
        this.localeText = lang;

        this.statusBar = {
            statusPanels: [
                {
                    statusPanel: "agAggregationComponent",
                    statusPanelParams: { aggFuncs: ["sum", "avg"] },
                },
            ],
        };
    },
    methods: {
        // range: function (start, end) {
        //     return Array(end - start + 1)
        //         .fill()
        //         .map((val, i) => start + i);
        // },
        // wheelget_data() {
        //     const fo = document.querySelector(".table");
        //     const tableHeight = fo.scrollHeight;
        //     const scrollTop = fo.scrollTop;
        //     const tableWindowHeight = fo.offsetHeight;
        //     if (tableHeight + tableWindowHeight - scrollTop < 1500) {
        //         this.get_more();
        //     }
        // },
        resetFilter() {
            gridOptions.api.setFilterModel(null);
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
</style>
