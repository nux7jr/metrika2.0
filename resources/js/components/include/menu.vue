<template>
    <div v-if="is_auth" class="menu">menu</div>
</template>

<script>
export default {
    name: "MetrikaMenu",

    data() {
        return {};
    },

    mounted() {},
    props: {
        is_auth: "",
        userdata: "",
    },
    computed: {},
    methods: {},
};
</script>

<style scoped>
.menu {
    display: flex;
    background-color: #3c3c3c;
    width: 80px;
    color: white;
}
</style>
