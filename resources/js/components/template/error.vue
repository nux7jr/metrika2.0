<template>
    <span v-if="error_mess" class="error-mess">{{ error_mess }}</span>
</template>

<script>
export default {
    name: "error",

    data() {
        return {};
    },

    mounted() {},
    props: {
        error_mess: "",
    },

    methods: {},
};
</script>

<style scoped>
.error-mess {
    background: #ffecfc;
    border: 2px solid #ca024f;
    border-radius: 12px;

    padding: 0.75rem 1.25rem;
    color: #9e0038;
}
</style>
