<template>
    <div ref="diagram" class="diagram">
        <!-- <Bar :data="data" :options="options" /> -->
    </div>
</template>

<script>
import { Chart, DoughnutController, ArcElement, Tooltip } from "chart.js";
Chart.register(DoughnutController, ArcElement, Tooltip);

export default {
    name: "metrikaDiagram",
    data() {
        return {};
    },

    created() {},
    methods: {},
};
</script>

<style></style>
