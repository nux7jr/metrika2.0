<template>
    <div class="preloader">
        <span class="loader"></span>
    </div>
</template>

<script>
export default {
    name: "MetrikaPreloader",

    data() {
        return {};
    },

    mounted() {},

    methods: {},
};
</script>

<style scoped>
.preloader {
    width: 100%;
    height: 100%;

    display: flex;
    align-items: center;
    justify-content: center;
}
.loader {
    width: 40px;
    height: 40px;
    border: 5px solid whitesmoke;
    border-bottom-color: #8dccec;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
}

@keyframes rotation {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
