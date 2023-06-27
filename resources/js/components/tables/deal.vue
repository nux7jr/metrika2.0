<template>
    <div ref="info" class="info">
        <div class="info-option">
            <div class="info-filters">
                <div class="filter__item">
                    <label class="filter__label" for="funnels">Показать данные</label>
                    <select v-model="funnel_current" @change="set_user_direction()"> 
                        <option value="" selected disabled hidden>Выберите воронку</option>
                        <option v-for="funnel in funnels">{{ funnel }}</option>
                    </select> 
                </div>                 
                <div class="filter__item">
                    <div class="filter__item_title">Выберите период</div>
                    <div class="filter__input_container">
                        <input class="filter__input" type="date" name="date-off" id="date-off" v-model="date.date_on" @change="set_user_date()" />
                        <span> - </span>
                        <input class="filter__input" type="date" name="date-on" id="date-on" @change="set_user_date()" v-model="date.date_off" />
                    </div>
                </div>
            </div>
            <div class="info-option__button button-group">
                <button class="def__button" @click="get_date_grid()"><span v-if="loading" class="loader"></span>Показать данные</button>
                <button class="def__button download_check" style="display: none;" @click="get_export_checked_box()">Скачать выбранные строки</button>
                <button class="def__button download_all" @click="get_export_all()">Скачать всё</button>
                <input class="option-hidden" id="selectedOnly" type="checkbox" checked />
            </div>
        </div>
        <div class="deal_table_block"> 
            <div class="deal_control_table_main_block">
                <div class="deal_control_table_block">
                    <button class="deal_control" v-bind:class="{ deal_active: allActive }" @click="utm_filter('all');"><span v-if="loading" class="loader"></span>все utm</button>
                    <button class="deal_control" v-bind:class="{ deal_active: sourceActive }" @click="utm_filter('utm_source');"><span v-if="loading" class="loader"></span>utm_source</button>
                    <button class="deal_control" v-bind:class="{ deal_active: mediumActive }" @click="utm_filter('utm_medium');"><span v-if="loading" class="loader"></span>utm_medium</button>
                    <button class="deal_control" v-bind:class="{ deal_active: campaignActive }" @click="utm_filter('utm_campaign');"><span v-if="loading" class="loader"></span>utm_campaign</button>
                    <button class="deal_control" v-bind:class="{ deal_active: contentActive }" @click="utm_filter('utm_content');"><span v-if="loading" class="loader"></span>utm_content</button>
                    <button class="deal_control" v-bind:class="{ deal_active: termActive }" @click="utm_filter('utm_term');"><span v-if="loading" class="loader"></span>utm_term</button>
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
                    :headerHeight="headerHeight"
                    :suppressRowClickSelection="true"
                    :overlayLoadingTemplate="overlayLoadingTemplate"
                    @row-selected="onRowSelected"
                ></ag-grid-vue>
            </div>
            <div class="deal_reason_closed_main_block">
                <div class="deal_reason_closed_title">Причины закрытия</div>
                <div class="deal_reason_closed_all"><span>Итого закрытых за период:</span><span>{{ reason_closed_all }}</span></div>
                <div class="deal_reason_closed_block">
                    <table>
                        <tbody class="tbody">
                            <tr class="deal_reason_element" v-for="reason in reason_closed">
                                <span class="deal_reason_element_name"><name>{{ reason.name }}</name> :</span>
                                <span class="deal_reason_lement_number"><number>{{ reason.count }}</number></span>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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
            columnDefs: [],
            gridApi: null,
            columnApi: null,
            defaultColDef: {
                editable: true,
                sortable: true,
                wrapHeaderText: true,
                autoHeaderHeight: true,
                filter: true,
                filterParams: { buttons: ["reset", "apply"] },
            },
            rowData: null,
            sideBar: null,
            funnels: "",
            funnel_current: "",
            columns: [],
            reason_closed: "",
            reason_closed_all: 0,
            utm_filters: "",
            utm_filters_active: "",
            headerHeight: null,
            date: {
                date_on: "",
                date_off: "",
            },
            model: {},
        };
    },
    computed: {},
    created() {
        

        this.rowData = [];
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
        this.overlayLoadingTemplate =
            '<span class="ag-overlay-loading-center loader"></span>';
    },
    methods: {
        onRowSelected(event){
            let checked = event.node.isSelected();
            let checked_count_derty = document.querySelectorAll('.ag-checkbox-input');
            let checked_count = [];
            for (let i = 0; i < checked_count_derty.length; i++) { if(checked_count_derty[i].checked){ checked_count.push(i); } }
            if(checked == true){ if(checked_count.length > 0){ document.querySelector('.download_check').style.display = 'block'; } }
            if(checked == false){ if(checked_count.length == 0){ document.querySelector('.download_check').style.display = 'none'; } }
        },
        get_export_all() {
            let file = this.gridApi.getDataAsExcel();
            this.open_file(file); 
        },
        get_export_checked_box() {
            if(document.querySelector("#selectedOnly").checked){
                let file = this.gridApi.getDataAsExcel({ onlySelected: document.querySelector("#selectedOnly").checked, });
                this.open_file(file);
            }
        },
        open_file(file){
            let url = URL.createObjectURL(file);
            window.open(url); 
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
        set_user_direction() {
            sessionStorage.setItem("direction", JSON.stringify(this.funnel_current));
        },        
        set_user_date() {
            sessionStorage.setItem("date", JSON.stringify(this.date));
        },
        utm_filter(filter){
            this.check_filter(filter);
            this.utm_filters = filter;
            sessionStorage.setItem("filter", JSON.stringify(this.utm_filters));
            this.get_date_grid();
        },
        check_filter(filter){
            this.allActive = false;this.sourceActive = false;this.mediumActive = false;this.campaignActive = false;this.contentActive = false;this.termActive = false;        
            if(!filter){ filter = 'all'; this.allActive = true; }

            if(filter == 'all')         { this.allActive = true; }
            if(filter == 'utm_source')  { this.sourceActive = true; }
            if(filter == 'utm_medium')  { this.mediumActive = true; }
            if(filter == 'utm_campaign'){ this.campaignActive = true; }
            if(filter == 'utm_content') { this.contentActive = true; }
            if(filter == 'utm_term')    { this.termActive = true;  }
        },
        get_date_grid() {
            // this.loading = true; 
            this.gridApi.showLoadingOverlay();
            document.getElementById("selectedOnly").checked = true;
            let token = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            const localDate = JSON.parse(sessionStorage.getItem("date"));
            const localDirection = JSON.parse(sessionStorage.getItem("direction"));
            let localFilter = JSON.parse(sessionStorage.getItem("filter"));
            if(!localFilter){ localFilter = 'all'; this.utm_filter('all'); }
            this.check_filter(localFilter);
            fetch('/get_grid_deals?' + new URLSearchParams({
                date_on: localDate.date_on, 
                date_off: localDate.date_off,
                direction: localDirection,
                filter: localFilter,
            }))
                .then((resp) => resp.json())
                .then((data) => {
                    if(data.directions.length != 2){ this.funnels = data.directions; }
                    this.reason_closed = data.reason_closed;
                    this.reason_closed_all = 0;
                    for (let s = 0; s < data.reason_closed.length; s++) {
                        this.reason_closed_all = Number(this.reason_closed_all) + Number(data.reason_closed[s].count);
                    }                    
                    let columns = [];
                    for (let i = 0; i < data.columns.length; i++) {
                        columns[i] = {
                            field: data.columns[i],
                            headerName: data.columns[i],
                            rowDrag: false,
                            rowGroup: false,
                            hide: false,
                        }; 
                        if(i == 0){
                            columns[i] = {
                                field: data.columns[i],
                                headerName: data.columns[i],                                 
                                headerCheckboxSelection: true,
                                checkboxSelection: true,  
                                rowDrag: false,
                                rowGroup: false,
                                hide: false,                           
                            }; 
                        }                                              
                    } 
                    this.gridApi.setColumnDefs(columns);
                    this.gridApi.setRowData(data.utms);
                    this.gridApi.hideOverlay();
                });
        }, 
        on_grid_ready(params) { 
            document.getElementById("selectedOnly").checked = true;
            this.gridApi = params.api;
            this.gridApi.showLoadingOverlay();
            this.gridColumnApi = params.columnApi;
            const updateData = (data) => params.api.setRowData(data);
            let localFilter = JSON.parse(sessionStorage.getItem("filter"));
            if(!localFilter){ localFilter = 'all'; this.utm_filter('all'); }
            this.check_filter(localFilter);            
            let token = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            fetch('/get_grid_deals?' + new URLSearchParams({
                date_on: this.date.date_on, 
                date_off: this.date.date_off,
                filter: localFilter,
            }))
                .then((resp) => resp.json())
                .then((data) => {
                    this.funnels = data.directions; 
                    this.reason_closed = data.reason_closed;
                    for (let s = 0; s < data.reason_closed.length; s++) {
                        this.reason_closed_all = Number(this.reason_closed_all) + Number(data.reason_closed[s].count);
                    }
                    let columns = [];
                    for (let i = 0; i < data.columns.length; i++) {
                        columns[i] = {
                            field: data.columns[i],
                            headerName: data.columns[i],                          
                            rowDrag: false,
                            rowGroup: false,
                            hide: false,
                        };    
                        if(i == 0){
                            columns[i] = {
                                field: data.columns[i],
                                headerName: data.columns[i],                                 
                                headerCheckboxSelection: true,
                                checkboxSelection: true,  
                                rowDrag: false,
                                rowGroup: false,
                                hide: false,                        
                            }; 
                        }

                    } 
                    this.gridApi.setColumnDefs(columns);
                    updateData(data.utms);
                }); 
        },
    },
    beforeMount(){},
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
select {
    width: 301px;
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
option{
    width: 301px;
    font-family: "Montserrat", sans-serif;
    padding: 8px;
    border-radius: 7px;
    border-color: transparent;
    border: 1px #2196f3 solid;
    outline: none;
    color: white;
    background: #3b3f41;
    color-scheme: dark;    
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
    width: 100%;
    margin-right: 7px;
    margin-bottom: 7px;
}

.main-table__wrapper {
    width: 100%;
    height: calc(100vh - 182px);
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
    align-items: flex-end;
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
    align-items: flex-start;
    flex-direction: column;
    justify-content: flex-end;
}
.filter__item_title{
    color: white;
}
.filter__input_container{

}
.info-filters span{
    color: white;
    padding: 0 15px;    
}
.option-hidden {
    display: none;
}
.deal_table_block{
   display: flex; 
}
.deal_control_table_main_block{
    display: flex;
    margin-right: 5px;
}
.deal_control_table_block{
    width: 185px;
    height: max-content;
    padding: 10px;
    display: flex;
    flex-direction: column;
    background: #323336;
    border-radius: 5px;
    border: 1px solid #68686e;
    gap: 10px;
}
.deal_control{
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
    background: #222628;
    border-radius: 7px;
    border-color: transparent;
    flex: none;
    order: 0;
    flex-grow: 1;
    cursor: pointer;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.deal_control:hover {
    background: #1a7bca;
}
.deal_reason_closed_main_block{
    width: 600px;
    height: calc(100vh - 235px);
    padding: 25px;
    display: flex;
    flex-direction: column;
    background: #323336;
    border-radius: 5px;
    border: 1px solid #68686e;
    gap: 5px;
    justify-content: flex-start;
    align-items: flex-start;
    margin-right: 10px;
}
.deal_reason_closed_title{
    color: #2196f3;
    font-size: 16px;
}
.deal_reason_closed_block{
    width: 100%;
    overflow-y: scroll;
    height: auto;
    color: white;
    font-size: 14px;
    gap: 10px;
    padding-right: 13px;
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    flex-direction: column;
    align-items: stretch;
}
.deal_reason_element{
    display: flex;
    justify-content: space-between;   
}
.deal_reason_element_name{
    width: 100%;
    max-width: 240px;
}
.deal_reason_lement_number{

}
.deal_active{
    background: #2196f3;
}

tr {
    background: transparent;
    cursor: pointer;
    transition: 0.3s all;
}

tr:hover{
    background: black;
}

tbody tr:nth-child(odd) {
    display: flex;
    background-color: #222628;
    padding: 10px 13px;
    border-radius: 5px;
    align-items: center;
}

tbody tr:nth-child(odd):hover{
    background: #303d49;
}

tbody tr:nth-child(even) {
    display: flex;
    padding: 10px 13px;
    border-radius: 5px;
    align-items: center;
}

tbody tr:nth-child(even):hover{
    background: #303d49;
}
.deal_reason_closed_all{
    display: flex;
    justify-content: space-between;
    width: calc(100% - 13px);
    color: white;
    font-size: 14px;
}
</style>
