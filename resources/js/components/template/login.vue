<template>
    <form class="login" ref="login" @submit.prevent="send_data(evt)">
        <div class="login__wrapper">
            <!-- hidden -->
            <input type="hidden" name="_token" :value="csrf" />
            <!-- /hidden -->
            <div class="login__header">
                <h2 class="login__heading">Вход</h2>
            </div>
            <basicInput
                name="login"
                typeInput="text"
                placeholder="Login"
                :error="info.errors.email"
            />
            <basicInput
                name="password"
                typeInput="password"
                placeholder="Password"
                :error="info.errors.password"
            />
            <error :error_mess="info.errors.login" />
            <button class="login__sumbit" type="submit">
                <span class="login__loader" v-if="loading"></span>
                <div class="login__btn-text" v-else>Войти</div>
            </button>
        </div>
    </form>
</template>

<script>
import axios from "axios";
import error from "./error.vue";
import basicInput from "./basicInput.vue";
export default {
    name: "login",

    data() {
        return {
            csrf: document.head.querySelector('meta[name="csrf-token"]')
                .content,
            info: {
                errors: {
                    email: "",
                    password: "",
                    login: "",
                },
                url: {
                    success: "",
                },
            },
            loading: false,
        };
    },
    methods: {
        async send_data() {
            this.loading = true;
            const data = await axios.post("/login", this.$refs.login);
            if (data.data.url) {
                window.location.href = data.data.url;
            } else {
                this.info = data.data;
                this.loading = false;
            }
        },
    },
    components: {
        error,
        basicInput,
    },
};
</script>

<style>
@keyframes rotation {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
.login {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.login__loader {
    width: 17px;
    height: 17px;
    border: 2px solid #fff;
    border-bottom-color: transparent;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
}
.login__wrapper {
    background: #fcfcfc;
    box-shadow: 0px 35px 40px rgba(0, 0, 0, 0.06);
    border-radius: 40px;
    padding: 56px;
    display: flex;
    flex-direction: column;
    gap: 20px;

    flex-grow: 1;
    max-width: 380px;
}
.login__header {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;

    font-style: normal;
    font-weight: 700;
    font-size: 20px;

    letter-spacing: 1px;

    color: #14142b;
}
.login__sumbit {
    color: white;
    background: #8dccec;
    border-radius: 12px;

    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    padding: 18px 32px;
    border-color: transparent;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
        border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    cursor: pointer;
}
.login__sumbit:hover {
    background: #80c0df;
}
.login__btn-text {
    font-weight: 600;
    font-size: 16px;
    line-height: 28px;
    letter-spacing: 0.75px;
    color: #fcfcfc;
}
</style>
