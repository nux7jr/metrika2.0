<template>
    <form class="register" ref="register" @submit.prevent="send_data(evt)">
        <div class="register__wrapper">
            <!-- hidden -->
            <input type="hidden" name="_token" :value="csrf" />
            <!-- /hidden -->
            <div class="register__header">
                <h2 class="register__heading">Регистрация</h2>
            </div>
            <basicInput
                name="name"
                typeInput="text"
                placeholder="Name"
                :error="info.errors.name"
            />
            <basicInput
                name="login"
                typeInput="text"
                placeholder="Login"
                :error="info.errors.login"
            />
            <basicInput
                name="password"
                typeInput="password"
                placeholder="Password"
                :error="info.errors.password"
            />
            <basicInput
                name="password_confirmation"
                typeInput="password"
                placeholder="Password again"
                :error="info.errors.password"
            />
            <basicInput
                name="telegram_chat_id"
                typeInput="number"
                placeholder="Telegram chat id"
                :error="info.errors.telegram_chat_id"
            />
            <small class="telegram-mess">
                Ваш ID
                <strong>
                    <a
                        href="https://t.me/getmyid_bot?start=botostore"
                        target="_blank"
                        rel="noopener noreferrer"
                        >ТУТ</a
                    >
                </strong>
                (напишите боту /start)
            </small>
            <error :error_mess="info.errors.register" />
            <button class="register__sumbit" type="submit">
                <span class="register__loader" v-if="loading"></span>
                <div class="register__btn-text" v-else>Регистрация</div>
            </button>
        </div>
    </form>
</template>

<script>
import axios from "axios";
import error from "./error.vue";
import basicInput from "./basicInput.vue";
export default {
    name: "register",

    data() {
        return {
            csrf: document.head.querySelector('meta[name="csrf-token"]')
                .content,
            info: {
                errors: {
                    email: "",
                    password: "",
                    register: "",
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
            const data = await axios.post("/register", this.$refs.register);
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
.register {
    display: block;
    margin: 0 auto;
    max-width: 480px;
}
.register__loader {
    width: 17px;
    height: 17px;
    border: 2px solid #fff;
    border-bottom-color: transparent;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
}
.register__wrapper {
    background: #fcfcfc;
    box-shadow: 0px 35px 40px rgba(0, 0, 0, 0.06);
    border-radius: 40px;
    padding: 56px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.register__header {
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
.register__sumbit {
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
.register__sumbit:hover {
    background: #80c0df;
}
.register__btn-text {
    font-weight: 600;
    font-size: 16px;
    line-height: 28px;
    letter-spacing: 0.75px;
    color: #fcfcfc;
}
.telegram-mess {
    text-align: center;
}
</style>
