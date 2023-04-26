<template>
    <div class="two-factor">
        <form
            class="two-factor__form"
            @submit.prevent="send_data()"
            ref="userForm"
            action="verify"
        >
            <h3 class="two-factor__heading">Введите код из телеграм</h3>
            <input
                class="two-factor__input"
                ref="telegramCode"
                type="text"
                name="two_factor_code"
                autofocus
                required
                :minlength="limit"
                :maxlength="limit"
            />
            <button class="two-factor__sumbit" type="submit">
                <span class="two-factor__loader" v-if="loading"></span>
                <div v-else>Войти</div>
            </button>
            <error :error_mess="info.error" />
        </form>
    </div>
</template>

<script>
import axios from "axios";
import error from "./error.vue";
export default {
    name: "telegram",
    data() {
        return {
            limit: 6,
            loading: false,
            info: {
                error: "",
            },
        };
    },
    mounted() {
        this.form_is_ready();
    },
    methods: {
        async send_data() {
            this.loading = true;
            try {
                const data = await axios.post("/verify", this.$refs.userForm);
                this.info = data.data;
                if (data.data.url) {
                    window.location.href = data.data.url;
                } else {
                    this.loading = false;
                }
            } catch (e) {
                console.log(e);
                this.info.error = e.message;
                this.loading = false;
            }
        },
        form_is_ready() {
            this.$refs.telegramCode.addEventListener("keyup", (evt) => {
                if (this.$refs.telegramCode.value.length == 6) {
                    this.$refs.telegramCode.blur();
                    this.send_data();
                }
            });
        },
    },
    components: {
        error,
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

.two-factor__loader {
    width: 17px;
    height: 17px;
    border: 2px solid #fff;
    border-bottom-color: transparent;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
}
.two-factor__input {
    padding: 10px 5px;
    border: 1px solid;
    font-family: monospace;
    width: calc(8 * (1ch + 7.7px));
    font-size: 26px;
    letter-spacing: 15px;
    background: repeating-linear-gradient(
            to right,
            #2196f3 0 1ch,
            transparent 1ch calc(1ch + 15px)
        )
        bottom/100% 2px content-box no-repeat;
    outline: none;
    border: none;
    display: block;
    margin: 0 auto;
}

.two-factor__sumbit {
    color: white;
    background: #2196f3;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
    line-height: 28px;
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
.two-factor__sumbit:hover {
    background: #1a8cea;
}
.two-factor {
    height: -webkit-fill-available;
    justify-content: center;
    align-items: center;

    display: flex;
}

.two-factor__form {
    background: #fcfcfc;
    box-shadow: 0px 35px 40px rgba(0, 0, 0, 0.06);
    border-radius: 40px;
    padding: 56px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    max-width: 331px;
}
.two-factor__heading {
    text-align: center;
}
</style>
