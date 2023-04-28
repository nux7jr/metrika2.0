<template>
    <div
        v-if="user"
        ref="menu"
        class="menu"
        v-bind:class="{ isActive: isActive, isDisable: !isActive }"
    >
        <div class="option">
            <input type="checkbox" class="menu__checkbox" id="menu__checkbox" />
            <label
                class="menu__label menu__item menu__toggle"
                @click="toggle_menu()"
                for="menu__checkbox"
            >
                <div class="burder__item"></div>
                <div class="burder__item"></div>
                <div class="burder__item"></div>
            </label>
            <Transition name="slide-fade">
                <a v-if="isActive" class="menu__logo" href="/">
                    <img
                        class="logo__img"
                        src="../../../images/logos/logo.webp"
                        alt="logo"
                    />
                </a>
            </Transition>
        </div>
        <a class="menu__item" href="/">
            <img
                class="menu__img"
                src="../../../images/icons/home.svg"
                alt="icon"
            />
            <Transition name="slide-fade">
                <span class="menu__text" v-if="isActive"
                    >Страница с лидами</span
                >
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
    mounted() {
        setTimeout(() => {
            this.$refs.menu.classList.remove(".preload");
        }, 500);
    },
    computed: {},
    methods: {
        toggle_menu() {
            this.isActive = !this.isActive;
        },
    },
};
</script>

<style scoped>
.slide-fade-enter-active {
    transition: all 2s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.3s ease-out;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
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
    background-color: #181d1f;
    width: 56px;
    color: white;

    padding-top: 26px;
    transition: width 0.7s;
}
.menu__item {
    display: flex;
    gap: 5px;
    flex-wrap: nowrap;
    align-items: center;
    padding: 4px 10px;

    background: transparent;

    background: linear-gradient(to left, #181d1f 50%, #2196f3 50%) right;
    background-size: 200%;
    transition: 0.4s ease-out;

    color: rgb(255, 255, 255);
    margin: 5px;
    border-radius: 5px;
    height: 46px;

    gap: 10px;
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
    font-size: 14px;
    font-weight: 600;
    text-align: left;
}
.menu.isActive {
    width: 210px;
}
.menu.isDisable {
    width: 56px;
}
.menu__checkbox {
    display: none;
}

.menu__label {
    display: block;
    width: 40px;
    height: 30px;
    transform: translateY(-50%);
    transition: 0.3s ease transform;
    cursor: pointer;
}

.menu__label .burder__item {
    width: 6px;
    height: 4px;
    background-color: #ffffff;
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
    background-color: #2196f3;
}
.menu__logo {
    width: 100px;
}
.option {
    display: flex;
}
</style>
