<template>
    <div v-if="user" class="menu" v-bind:class="{ menu__active: isActive }">
        <button
            class="menu__item menu__toggle"
            v-bind:class="{ button__active: isActive }"
            @click="isActive = !isActive"
        >
            <img
                class="toggle-img"
                src="../../../images/icons/menu_icon.svg"
                alt="icon"
            />
        </button>
        <a class="menu__item" href="/">
            <img
                class="menu__img"
                src="../../../images/icons/days.svg"
                alt="icon"
            />
        </a>
        <a class="menu__item" href="/asdasd">
            <img
                class="menu__img"
                src="../../../images/icons/days.svg"
                alt="icon"
            />
        </a>
        <a class="menu__item" href="/asdasd">
            <img
                class="menu__img"
                src="../../../images/icons/days.svg"
                alt="icon"
            />
        </a>
        <a class="menu__item" href="/somelink">
            <img
                class="menu__img"
                src="../../../images/icons/days.svg"
                alt="icon"
            />
        </a>
    </div>
</template>

<script>
export default {
    name: "MetrikaMenu",

    data() {
        return {
            isActive: 0,
        };
    },

    mounted() {},
    props: {
        user: "",
    },
    computed: {},
    methods: {},
};
</script>

<style scoped>
.toggle-img {
    width: 20px;
}
.menu__img {
    width: 50px;
}
.menu {
    display: flex;
    flex-direction: column;
    gap: 7px;

    background-color: #3c3c3c;
    width: 4vw;
    color: white;

    padding-top: 30px;
}
.menu__item {
    display: flex;
    justify-content: center;
}
.menu__toggle {
    border: none;
    background: transparent;
    cursor: pointer;
}
</style>
