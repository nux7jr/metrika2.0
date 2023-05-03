<template>
    <div>
        <label
            @focusin="openInput()"
            ref="wrapper"
            :for="name"
            class="wrapper"
            v-bind:class="{
                active: isActive,
                error: error.length,
            }"
        >
            <span class="basic-input__text">{{ placeholder }}</span>
            <input
                ref="basicInput"
                class="basic-input visually-hidden"
                autocomplete="off"
                readonly
                onfocus="this.removeAttribute('readonly')"
                :type="typeInput"
                :name="name"
                :id="name"
                v-model="value"
                required
            />
            <div
                ref="basic_close"
                @click="clear_input()"
                class="basic-input__clear"
                type="button"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                >
                    <path
                        d="M6 6.00002L18.7742 18.7742M6 18.7742L18.7742 6"
                        stroke="#14142B"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
            </div>
        </label>
        <div v-if="error.length" class="error-mess">{{ error }}</div>
    </div>
</template>

<script>
export default {
    name: "error",

    data() {
        return {
            isActive: false,
            value: "",
        };
    },

    mounted() {
        this.$refs.wrapper.addEventListener("click", (evt) => {
            this.$refs.basicInput.classList.remove("visually-hidden");
            this.$refs.basicInput.classList.add("bounce-enter-active");
            setTimeout(() => {
                this.$refs.basicInput.classList.remove("bounce-enter-active");
            }, 400);
            this.isActive = true;
            this.$refs.basic_close.style.display = "flex";
        });
    },
    props: {
        typeInput: {
            type: String,
            default: "text",
        },
        name: {
            type: String,
            default: "some_text",
        },
        placeholder: {
            type: String,
            default: "defalut_placeholder",
        },
        error: {
            type: String,
            default: "",
        },
    },
    created() {},
    methods: {
        clear_input() {
            this.value = "";
            this.isActive = false;
            setTimeout(() => {
                this.isActive = true;
            }, 1);
        },
        openInput() {
            this.$refs.basicInput.classList.remove("visually-hidden");
            this.$refs.basic_close.style.display = "flex";
            this.isActive = true;
        },
    },
};
</script>

<style scoped>
.visually-hidden {
    position: absolute;
    width: 1px;
    height: 1px;
    margin: -1px;
    border: 0;
    padding: 0;
    white-space: nowrap;
    clip-path: inset(100%);
    clip: rect(0 0 0 0);
    overflow: hidden;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
@keyframes bounce-in {
    0% {
        transform: scale(0);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}
.bounce-enter-active {
    animation: bounce-in 0.5s;
}
.bounce-leave-active {
    animation: bounce-in 0.5s reverse;
}
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus,
input:-webkit-autofill:active {
    transition: background-color 5000s ease-in-out 0s;
}
.wrapper {
    background: #eff0f6;
    height: 64px;
    padding: 2px 60px;
    padding-left: 20px;
    border-color: transparent;
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
}
.basic-input__text {
    font-style: normal;
    font-weight: 500;
    font-size: 20px;
    line-height: 20px;
    color: #6e7191;
    transition-duration: 0.5s;
    font-family: "Poppins", sans-serif;
}
.basic-input {
    background: transparent;
    font-family: "Poppins", sans-serif;
    width: -webkit-fill-available;
    border: none;
    outline: none;
    display: flex;

    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    line-height: 28px;
    transition: 0.5s all;
}
.basic-input:-webkit-autofill,
.basic-input:-webkit-autofill:hover,
.basic-input:-webkit-autofill:focus,
.basic-input:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 30px transparent inset !important;
}

.basic-input__clear {
    cursor: pointer;
    width: 40px;
    height: 40px;
    display: none;
    justify-content: center;
    align-items: center;
    background: transparent;
    border-color: transparent;
    position: absolute;
    right: 10px;
    animation: fadeIn 1s linear;
}
.active {
    border: 2px solid #14142b;
    background: white;
}
.active-b > .basic-input-b {
    display: block;
}
.active .basic-input {
    display: block;
}
.active > .basic-input__text {
    font-size: 14px;
    font-weight: 200;
}
.error {
    background: #ffecfc;
    border: 2px solid #ca024f;
}
.error-mess {
    font-weight: 500;
    font-size: 14px;
    line-height: 20px;
    color: #9e0038;
    margin-top: 5px;
}
.error > .basic-input__text {
    color: #9e0038;
}
</style>
