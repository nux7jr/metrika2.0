import { BeanStub, ChartModel, ChartType, SeriesChartType } from "ag-grid-community";
import { ChartDataModel, ColState } from "./chartDataModel";
import { ChartProxy, UpdateChartParams } from "./chartProxies/chartProxy";
import { AgChartThemePalette } from "ag-charts-community";
import { ChartSeriesType } from "./utils/seriesTypeMapper";
export declare const DEFAULT_THEMES: string[];
export declare class ChartController extends BeanStub {
    private readonly model;
    static EVENT_CHART_UPDATED: string;
    static EVENT_CHART_MODEL_UPDATE: string;
    static EVENT_CHART_TYPE_CHANGED: string;
    static EVENT_CHART_SERIES_CHART_TYPE_CHANGED: string;
    private readonly rangeService;
    private chartProxy;
    constructor(model: ChartDataModel);
    private init;
    updateForGridChange(): void;
    updateForDataChange(): void;
    updateForRangeChange(): void;
    updateForPanelChange(updatedCol: ColState): void;
    getChartUpdateParams(): UpdateChartParams;
    getChartModel(): ChartModel;
    getChartId(): string;
    getChartData(): any[];
    getChartType(): ChartType;
    setChartType(chartType: ChartType): void;
    setChartThemeName(chartThemeName: string): void;
    getChartThemeName(): string;
    isPivotChart(): boolean;
    isPivotMode(): boolean;
    isGrouping(): boolean;
    getThemes(): string[];
    getPalettes(): AgChartThemePalette[];
    getValueColState(): ColState[];
    getSelectedValueColState(): {
        colId: string;
        displayName: string | null;
    }[];
    getDimensionColState(): ColState[];
    getSelectedDimension(): ColState;
    private displayNameMapper;
    getColStateForMenu(): {
        dimensionCols: ColState[];
        valueCols: ColState[];
    };
    isDefaultCategorySelected(): boolean;
    setChartRange(silent?: boolean): void;
    detachChartRange(): void;
    setChartProxy(chartProxy: ChartProxy): void;
    getChartProxy(): ChartProxy;
    isActiveXYChart(): boolean;
    isChartLinked(): boolean;
    customComboExists(): boolean;
    getSeriesChartTypes(): SeriesChartType[];
    isComboChart(): boolean;
    updateSeriesChartType(colId: string, chartType?: ChartType, secondaryAxis?: boolean): void;
    getActiveSeriesChartTypes(): SeriesChartType[];
    getChartSeriesTypes(): ChartSeriesType[];
    private getCellRanges;
    private getCellRangeParams;
    private raiseChartModelUpdateEvent;
    raiseChartUpdatedEvent(): void;
    private raiseChartOptionsChangedEvent;
    private raiseChartRangeSelectionChangedEvent;
    protected destroy(): void;
}