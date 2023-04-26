"use strict";
var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var __values = (this && this.__values) || function(o) {
    var s = typeof Symbol === "function" && Symbol.iterator, m = s && o[s], i = 0;
    if (m) return m.call(o);
    if (o && typeof o.length === "number") return {
        next: function () {
            if (o && i >= o.length) o = void 0;
            return { value: o && o[i++], done: !o };
        }
    };
    throw new TypeError(s ? "Object is not iterable." : "Symbol.iterator is not defined.");
};
var __read = (this && this.__read) || function (o, n) {
    var m = typeof Symbol === "function" && o[Symbol.iterator];
    if (!m) return o;
    var i = m.call(o), r, ar = [], e;
    try {
        while ((n === void 0 || n-- > 0) && !(r = i.next()).done) ar.push(r.value);
    }
    catch (error) { e = { error: error }; }
    finally {
        try {
            if (r && !r.done && (m = i["return"])) m.call(i);
        }
        finally { if (e) throw e.error; }
    }
    return ar;
};
var __spread = (this && this.__spread) || function () {
    for (var ar = [], i = 0; i < arguments.length; i++) ar = ar.concat(__read(arguments[i]));
    return ar;
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.prepareOptions = exports.noDataCloneMergeOptions = exports.isAgPolarChartOptions = exports.isAgHierarchyChartOptions = exports.isAgCartesianChartOptions = exports.optionsType = void 0;
var defaults_1 = require("./defaults");
var json_1 = require("../../util/json");
var transforms_1 = require("./transforms");
var themes_1 = require("./themes");
var prepareSeries_1 = require("./prepareSeries");
var logger_1 = require("../../util/logger");
var chartTypes_1 = require("../chartTypes");
var chartAxesTypes_1 = require("../chartAxesTypes");
function optionsType(input) {
    var _a, _b, _c, _d;
    return (_d = (_a = input.type) !== null && _a !== void 0 ? _a : (_c = (_b = input.series) === null || _b === void 0 ? void 0 : _b[0]) === null || _c === void 0 ? void 0 : _c.type) !== null && _d !== void 0 ? _d : 'line';
}
exports.optionsType = optionsType;
function isAgCartesianChartOptions(input) {
    var specifiedType = optionsType(input);
    if (specifiedType == null) {
        return true;
    }
    if (specifiedType === 'cartesian') {
        logger_1.Logger.warnOnce("type '" + specifiedType + "' is deprecated, use a series type instead");
        return true;
    }
    return chartTypes_1.CHART_TYPES.isCartesian(specifiedType);
}
exports.isAgCartesianChartOptions = isAgCartesianChartOptions;
function isAgHierarchyChartOptions(input) {
    var specifiedType = optionsType(input);
    if (specifiedType == null) {
        return false;
    }
    if (specifiedType === 'hierarchy') {
        logger_1.Logger.warnOnce("type '" + specifiedType + "' is deprecated, use a series type instead");
        return true;
    }
    return chartTypes_1.CHART_TYPES.isHierarchy(specifiedType);
}
exports.isAgHierarchyChartOptions = isAgHierarchyChartOptions;
function isAgPolarChartOptions(input) {
    var specifiedType = optionsType(input);
    if (specifiedType == null) {
        return false;
    }
    if (specifiedType === 'polar') {
        logger_1.Logger.warnOnce("type '" + specifiedType + "' is deprecated, use a series type instead");
        return true;
    }
    return chartTypes_1.CHART_TYPES.isPolar(specifiedType);
}
exports.isAgPolarChartOptions = isAgPolarChartOptions;
function isSeriesOptionType(input) {
    if (input == null) {
        return false;
    }
    return chartTypes_1.CHART_TYPES.has(input);
}
function isAxisOptionType(input) {
    if (input == null) {
        return false;
    }
    return chartAxesTypes_1.CHART_AXES_TYPES.has(input);
}
function countArrayElements(input) {
    var e_1, _a;
    var count = 0;
    try {
        for (var input_1 = __values(input), input_1_1 = input_1.next(); !input_1_1.done; input_1_1 = input_1.next()) {
            var next = input_1_1.value;
            if (next instanceof Array) {
                count += countArrayElements(next);
            }
            if (next != null) {
                count++;
            }
        }
    }
    catch (e_1_1) { e_1 = { error: e_1_1 }; }
    finally {
        try {
            if (input_1_1 && !input_1_1.done && (_a = input_1.return)) _a.call(input_1);
        }
        finally { if (e_1) throw e_1.error; }
    }
    return count;
}
function takeColours(context, colours, maxCount) {
    var result = [];
    for (var count = 0; count < maxCount; count++) {
        result.push(colours[(count + context.colourIndex) % colours.length]);
    }
    return result;
}
exports.noDataCloneMergeOptions = {
    avoidDeepClone: ['data'],
};
function prepareOptions(newOptions, fallbackOptions, seriesDefaults) {
    var e_2, _a, e_3, _b;
    var _c, _d, _e, _f;
    var options = json_1.jsonMerge([fallbackOptions, newOptions], exports.noDataCloneMergeOptions);
    sanityCheckOptions(options);
    // Determine type and ensure it's explicit in the options config.
    var userSuppliedOptionsType = options.type;
    var type = optionsType(options);
    var globalTooltipPositionOptions = ((_c = options.tooltip) === null || _c === void 0 ? void 0 : _c.position) || {};
    var checkSeriesType = function (type) {
        if (type != null && !(isSeriesOptionType(type) || (seriesDefaults === null || seriesDefaults === void 0 ? void 0 : seriesDefaults[type]))) {
            throw new Error("AG Charts - unknown series type: " + type + "; expected one of: " + chartTypes_1.CHART_TYPES.seriesTypes);
        }
    };
    checkSeriesType(type);
    try {
        for (var _g = __values((_d = options.series) !== null && _d !== void 0 ? _d : []), _h = _g.next(); !_h.done; _h = _g.next()) {
            var seriesType = _h.value.type;
            if (seriesType == null)
                continue;
            checkSeriesType(seriesType);
        }
    }
    catch (e_2_1) { e_2 = { error: e_2_1 }; }
    finally {
        try {
            if (_h && !_h.done && (_a = _g.return)) _a.call(_g);
        }
        finally { if (e_2) throw e_2.error; }
    }
    options = __assign(__assign({}, options), { type: type });
    var defaultSeriesType = 'line';
    if (isAgCartesianChartOptions(options)) {
        defaultSeriesType = 'line';
    }
    else if (isAgHierarchyChartOptions(options)) {
        defaultSeriesType = 'treemap';
    }
    else if (isAgPolarChartOptions(options)) {
        defaultSeriesType = 'pie';
    }
    var defaultOverrides = {};
    if (seriesDefaults && Object.prototype.hasOwnProperty.call(seriesDefaults, type)) {
        defaultOverrides = seriesDefaults[type];
    }
    else if (type === 'bar') {
        defaultOverrides = defaults_1.DEFAULT_BAR_CHART_OVERRIDES;
    }
    else if (type === 'scatter' || type === 'histogram') {
        defaultOverrides = defaults_1.DEFAULT_SCATTER_HISTOGRAM_CHART_OVERRIDES;
    }
    else if (isAgCartesianChartOptions(options)) {
        defaultOverrides = defaults_1.DEFAULT_CARTESIAN_CHART_OVERRIDES;
    }
    var _j = prepareMainOptions(defaultOverrides, options), context = _j.context, mergedOptions = _j.mergedOptions, axesThemes = _j.axesThemes, seriesThemes = _j.seriesThemes;
    // Special cases where we have arrays of elements which need their own defaults.
    // Apply series themes before calling processSeriesOptions() as it reduces and renames some
    // properties, and in that case then cannot correctly have themes applied.
    mergedOptions.series = prepareSeries_1.processSeriesOptions((mergedOptions.series || []).map(function (s) {
        var type = defaultSeriesType;
        if (s.type) {
            type = s.type;
        }
        else if (isSeriesOptionType(userSuppliedOptionsType)) {
            type = userSuppliedOptionsType;
        }
        var mergedSeries = mergeSeriesOptions(s, type, seriesThemes, globalTooltipPositionOptions);
        if (type === 'pie') {
            preparePieOptions(seriesThemes.pie, s, mergedSeries);
        }
        return mergedSeries;
    })).map(function (s) { return prepareSeries(context, s); });
    var checkAxisType = function (type) {
        var isAxisType = isAxisOptionType(type);
        if (!isAxisType) {
            logger_1.Logger.warnOnce("AG Charts - unknown axis type: " + type + "; expected one of: " + chartAxesTypes_1.CHART_AXES_TYPES.axesTypes + ", ignoring.");
        }
        return isAxisType;
    };
    if (isAgCartesianChartOptions(mergedOptions)) {
        var validAxesTypes = true;
        try {
            for (var _k = __values((_e = mergedOptions.axes) !== null && _e !== void 0 ? _e : []), _l = _k.next(); !_l.done; _l = _k.next()) {
                var axisType = _l.value.type;
                if (!checkAxisType(axisType)) {
                    validAxesTypes = false;
                }
            }
        }
        catch (e_3_1) { e_3 = { error: e_3_1 }; }
        finally {
            try {
                if (_l && !_l.done && (_b = _k.return)) _b.call(_k);
            }
            finally { if (e_3) throw e_3.error; }
        }
        if (!validAxesTypes) {
            mergedOptions.axes = defaultOverrides.axes;
        }
        else {
            mergedOptions.axes = (_f = mergedOptions.axes) === null || _f === void 0 ? void 0 : _f.map(function (axis) {
                var axisType = axis.type;
                var axesTheme = json_1.jsonMerge([
                    axesThemes[axisType],
                    axesThemes[axisType][axis.position || 'unknown'] || {},
                ]);
                return prepareAxis(axis, axesTheme);
            });
        }
    }
    prepareEnabledOptions(options, mergedOptions);
    return mergedOptions;
}
exports.prepareOptions = prepareOptions;
function sanityCheckOptions(options) {
    var deprecatedArrayProps = {
        yKeys: 'yKey',
        yNames: 'yName',
    };
    Object.entries(deprecatedArrayProps).forEach(function (_a) {
        var _b;
        var _c = __read(_a, 2), oldProp = _c[0], newProp = _c[1];
        if ((_b = options.series) === null || _b === void 0 ? void 0 : _b.some(function (s) { return s[oldProp] != null; })) {
            logger_1.Logger.warnOnce("property [series." + oldProp + "] is deprecated, please use [series." + newProp + "] and multiple series instead.");
        }
    });
}
function mergeSeriesOptions(series, type, seriesThemes, globalTooltipPositionOptions) {
    var _a;
    var mergedTooltipPosition = json_1.jsonMerge([__assign({}, globalTooltipPositionOptions), (_a = series.tooltip) === null || _a === void 0 ? void 0 : _a.position], exports.noDataCloneMergeOptions);
    var mergedSeries = json_1.jsonMerge([
        seriesThemes[type] || {},
        __assign(__assign({}, series), { type: type, tooltip: __assign(__assign({}, series.tooltip), { position: mergedTooltipPosition }) }),
    ], exports.noDataCloneMergeOptions);
    return mergedSeries;
}
function prepareMainOptions(defaultOverrides, options) {
    var _a = prepareTheme(options), theme = _a.theme, cleanedTheme = _a.cleanedTheme, axesThemes = _a.axesThemes, seriesThemes = _a.seriesThemes;
    var context = { colourIndex: 0, palette: theme.palette };
    var mergedOptions = json_1.jsonMerge([defaultOverrides, cleanedTheme, options], exports.noDataCloneMergeOptions);
    return { context: context, mergedOptions: mergedOptions, axesThemes: axesThemes, seriesThemes: seriesThemes };
}
function prepareTheme(options) {
    var theme = themes_1.getChartTheme(options.theme);
    var themeConfig = theme.config[optionsType(options) || 'cartesian'];
    var seriesThemes = Object.entries(theme.config).reduce(function (result, _a) {
        var _b = __read(_a, 2), seriesType = _b[0], series = _b[1].series;
        result[seriesType] = series === null || series === void 0 ? void 0 : series[seriesType];
        return result;
    }, {});
    return {
        theme: theme,
        axesThemes: themeConfig['axes'] || {},
        seriesThemes: seriesThemes,
        cleanedTheme: json_1.jsonMerge([themeConfig, { axes: json_1.DELETE, series: json_1.DELETE }]),
    };
}
function prepareSeries(context, input) {
    var defaults = [];
    for (var _i = 2; _i < arguments.length; _i++) {
        defaults[_i - 2] = arguments[_i];
    }
    var paletteOptions = calculateSeriesPalette(context, input);
    // Part of the options interface, but not directly consumed by the series implementations.
    var removeOptions = { stacked: json_1.DELETE };
    var mergedResult = json_1.jsonMerge(__spread(defaults, [paletteOptions, input, removeOptions]), exports.noDataCloneMergeOptions);
    return transforms_1.applySeriesTransform(mergedResult);
}
function calculateSeriesPalette(context, input) {
    var paletteOptions = {};
    var _a = context.palette, fills = _a.fills, strokes = _a.strokes;
    var inputAny = input;
    var colourCount = countArrayElements(inputAny['yKeys'] || []) || 1; // Defaults to 1 if no yKeys.
    switch (input.type) {
        case 'pie':
            colourCount = Math.max(fills.length, strokes.length);
        // eslint-disable-next-line no-fallthrough
        case 'area':
        case 'bar':
        case 'column':
            paletteOptions.fills = takeColours(context, fills, colourCount);
            paletteOptions.strokes = takeColours(context, strokes, colourCount);
            break;
        case 'histogram':
            paletteOptions.fill = takeColours(context, fills, 1)[0];
            paletteOptions.stroke = takeColours(context, strokes, 1)[0];
            break;
        case 'scatter':
            paletteOptions.marker = {
                stroke: takeColours(context, strokes, 1)[0],
                fill: takeColours(context, fills, 1)[0],
            };
            break;
        case 'line':
            paletteOptions.stroke = takeColours(context, fills, 1)[0];
            paletteOptions.marker = {
                stroke: takeColours(context, strokes, 1)[0],
                fill: takeColours(context, fills, 1)[0],
            };
            break;
    }
    context.colourIndex += colourCount;
    return paletteOptions;
}
function prepareAxis(axis, axisTheme) {
    // Remove redundant theme overload keys.
    var removeOptions = { top: json_1.DELETE, bottom: json_1.DELETE, left: json_1.DELETE, right: json_1.DELETE };
    // Special cross lines case where we have an array of cross line elements which need their own defaults.
    if (axis.crossLines) {
        if (!Array.isArray(axis.crossLines)) {
            logger_1.Logger.warn('axis[].crossLines should be an array.');
            axis.crossLines = [];
        }
        var crossLinesTheme_1 = axisTheme.crossLines;
        axis.crossLines = axis.crossLines.map(function (crossLine) { return json_1.jsonMerge([crossLinesTheme_1, crossLine]); });
    }
    var cleanTheme = { crossLines: json_1.DELETE };
    return json_1.jsonMerge([axisTheme, cleanTheme, axis, removeOptions], exports.noDataCloneMergeOptions);
}
function prepareEnabledOptions(options, mergedOptions) {
    // Set `enabled: true` for all option objects where the user has provided values.
    json_1.jsonWalk(options, function (_, visitingUserOpts, visitingMergedOpts) {
        if (!visitingMergedOpts)
            return;
        var _enabledFromTheme = visitingMergedOpts._enabledFromTheme;
        if (_enabledFromTheme != null) {
            // Do not apply special handling, base enablement on theme.
            delete visitingMergedOpts._enabledFromTheme;
        }
        if (!('enabled' in visitingMergedOpts))
            return;
        if (_enabledFromTheme)
            return;
        if (visitingUserOpts.enabled == null) {
            visitingMergedOpts.enabled = true;
        }
    }, { skip: ['data', 'theme'] }, mergedOptions);
    // Cleanup any special properties.
    json_1.jsonWalk(mergedOptions, function (_, visitingMergedOpts) {
        if (visitingMergedOpts._enabledFromTheme != null) {
            // Do not apply special handling, base enablement on theme.
            delete visitingMergedOpts._enabledFromTheme;
        }
    }, { skip: ['data', 'theme'] });
}
function preparePieOptions(pieSeriesTheme, seriesOptions, mergedSeries) {
    if (Array.isArray(seriesOptions.innerLabels)) {
        mergedSeries.innerLabels = seriesOptions.innerLabels.map(function (ln) {
            return json_1.jsonMerge([pieSeriesTheme.innerLabels, ln]);
        });
    }
    else {
        mergedSeries.innerLabels = json_1.DELETE;
    }
}
//# sourceMappingURL=prepare.js.map