<template>
    <div
        v-if="user"
        ref="menu"
        class="menu"
        v-bind:class="{ isActive: isActive }"
    >
        <input type="checkbox" class="menu__checkbox" id="menu__checkbox" />
        <label
            class="menu__label menu__item menu__toggle"
            @click="isActive = !isActive"
            for="menu__checkbox"
        >
            <div class="burder__item"></div>
            <div class="burder__item"></div>
            <div class="burder__item"></div>
        </label>
        <a class="menu__item" href="/">
            <img
                class="menu__img"
                src="../../../images/icons/home.svg"
                alt="icon"
            />
            <Transition name="slide-fade">
                <span class="menu__text" v-if="isActive">home</span>
            </Transition>
        </a>
        <a class="menu__item" href="/visitors">
            <img
                class="menu__img"
                src="../../../images/icons/dashboard.svg"
                alt="icon"
            />
            <Transition name="slide-fade">
                <span class="menu__text" v-if="isActive">dashboard</span>
            </Transition>
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
@keyframes slideIn {
    0% {
        width: 56px;
    }
    100% {
        width: 210px;
    }
}
@keyframes slideOut {
    0% {
        width: 210px;
    }
    100% {
        width: 56px;
    }
}
.slide-fade-enter-active {
    transition: all 0.2s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.1s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
.toggle-img {
    width: 20px;
}
.menu__img {
    width: 25px;
}
.menu {
    display: flex;
    flex-direction: column;
    gap: 7px;
    margin: 7px;
    border-radius: 5px;

    background-color: whitesmoke;
    width: 56px;
    color: white;

    padding-top: 26px;
    animation: slideOut 0.7s forwards;
}
.menu__item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 4px 10px;

    background: transparent;

    background: linear-gradient(to left, whitesmoke 50%, #8dccec8c 50%) right;
    background-size: 200%;
    transition: 0.4s ease-out;

    color: black;
    margin: 5px;
    border-radius: 5px;
    height: 46px;
}
.menu__item:hover {
    background-position: left;
}
.menu__toggle {
    border: none;
    background: transparent;
    cursor: pointer;
}
.menu__text {
    font-weight: 600;
    text-align: end;
}
.menu.isActive {
    animation: slideIn 0.7s forwards;
}

.menu__checkbox {
    display: none;
}

.menu__label {
    margin: 0 auto;
    display: block;
    width: 30px;
    height: 30px;
    transform: translateY(-50%);
    transition: 0.3s ease transform;
    cursor: pointer;
}

.menu__label .burder__item {
    width: 6px;
    height: 4px;
    background-color: #143240;
    margin-left: 0;
    margin-bottom: 6px;
    border-radius: 4px;
    transition: 0.3s ease width, 0.3s ease margin-left, 0.3s ease margin-bottom,
        0.3s ease background-color;
}

.menu__label .burder__item:first-child {
    width: 22px;
}

.menu__label .burder__item:nth-child(2) {
    width: 15px;
}

.menu__label .burder__item:last-child {
    width: 30px;
    margin-bottom: 0;
}

.menu__checkbox:checked + .menu__label {
    transform: translateY(-50%) rotateZ(-90deg);
}

.menu__checkbox:checked + .menu__label .burder__item {
    width: 8px;
    height: 8px;
    margin-left: 12px;
    margin-bottom: 3px;
    background-color: #8dccec;
}
</style>
