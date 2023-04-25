<template>
    <div ref="info" class="info">
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
                :sideBar="sideBar"
            ></ag-grid-vue>
            <!-- debounceVerticalScrollbar="true" -->
        </div>
    </div>
</template>

<script>
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-alpine.css";
// import "ag-grid-enterprise";

// import { ModuleRegistry } from "@ag-grid-community/core";
// import { SideBarModule } from "@ag-grid-enterprise/side-bar";

// ModuleRegistry.registerModules([SideBarModule]);

import { AgGridVue } from "ag-grid-vue3";
import { lang } from "../../locale/ru.js";
export default {
    name: "identifiedvisitors",
    data() {
        return {
            info: {},
            numberIndex: 40,
            localeText: null,
            columnDefs: [
                { field: "ip", headerName: "PIs" },
                { field: "first_name" },
                { field: "phone" },
                { field: "email" },
                { field: "city_browser", headerName: "browser" },
                { field: "city_user" },
                { field: "first_contact_site" },
                { field: "all_sites_visitors" },
                { field: "second_name" },
                // {
                //     field: "SITE",
                // },
                // { field: "PHONE" },
                // { field: "EMAIL" },
                // { field: "UTM_SOURCE" },
                // { field: "UTM_MEDIUM" },
                // { field: "UTM_CAMPAIGN" },
                // { field: "UTM_TERM" },
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
            sideBar: null,
        };
    },

    created() {
        this.sideBar = "filters";
        this.rowSelection = "multiple";
        this.localeText = lang;
    },
    methods: {
        onGridReady(params) {
            this.loading = true;
            this.gridApi = params.api;
            this.gridColumnApi = params.columnApi;

            const updateData = (data) => params.api.setRowData(data);

            fetch("http://api.tiksan.ru/api/test")
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
