<template>
    <div class="info">
        <div>СТАРЫЕ ЛИДЫ</div>
        <div v-for="item in info" :key="id">
            <p>{{ item.PHONE }}</p>
            <p>{{ item.ID }}</p>
            <p>{{ item.CITY }}</p>
        </div>
    </div>
</template>

<script>
export default {
    name: "metrikaBasic",

    data() {
        return {
            info: {},
        };
    },

    mounted() {
        this.getData();
    },
    methods: {
        async getData() {
            // const res = await axios.get("http://127.0.0.1:8000/print.php");
            // this.info = res.data;
        },
    },
};
</script>

<style scoped></style>
