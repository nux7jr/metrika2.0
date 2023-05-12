<template>
    <Transition>
        <div v-if="show" class="warning">
            <div class="warngin__window">
                <form class="warning__content" @submit.prevent="removeUser">
                    <div @click="$emit('close')" class="warning__close">×</div>
                    <h3 class="warning__heading">
                        Вы уверенны, что хотите удалить пользователя
                        {{ warningInfo.warningInfo.login }}?
                    </h3>
                    <input
                        type="hidden"
                        name="id"
                        v-bind:value="warningInfo.warningInfo.id"
                    />
                    <input
                        type="hidden"
                        name="telegramID"
                        v-bind:value="warningInfo.warningInfo.telegramID"
                    />
                    <input
                        type="hidden"
                        name="login"
                        v-bind:value="warningInfo.warningInfo.login"
                    />
                    <div class="warning__option">
                        <button
                            type="submit"
                            class="warning__button warngin__del"
                        >
                            <span v-if="loader" class="loader"></span>
                            <span ref="submitter">Удалить</span>
                        </button>
                        <div
                            @click="$emit('close')"
                            class="warning__button warngin__exit"
                        >
                            Закрыть
                        </div>
                    </div>
                    <div v-if="error" class="warning__message">{{ error }}</div>
                </form>
            </div>
        </div>
    </Transition>
</template>

<script>
export default {
    name: "MetrikaWarning",
    data() {
        return {
            loader: false,
            error: null,
        };
    },
    props: {
        onLogin: [],
        warningInfo: null,
        show: "",
    },
    mounted() {},

    methods: {
        async removeUser(evt) {
            this.$refs.submitter.innerText = "";
            this.loader = true;
            const res = await fetch("/create", {
                method: "DELETE",
                body: new FormData(evt.target),
            });

            if (res.status !== 200) {
                this.$emit("removeUser", {
                    info: this.warningInfo,
                });
                this.$emit("close");
            } else {
                this.error = "Error whit status: " + res.status;
                setTimeout(() => {
                    this.error = null;
                }, 3000);
            }
            this.loader = false;
            this.$refs.submitter.innerText = "Удалить";
        },
    },
};
</script>

<style scoped>
.loader {
    width: 13px;
    height: 13px;
    border: 2px solid #fff;
    border-bottom-color: transparent;
    border-radius: 50%;
    display: flex;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
}

.v-enter-active,
.v-leave-active {
    transition: opacity 0.3s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
.warning {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    display: flex;
    justify-content: center;

    background-color: rgba(0, 0, 0, 0.448);

    z-index: 4;
}
.warngin__window {
    display: flex;
    justify-content: center;
    align-items: center;
}
.warning__content {
    padding: 15px;
    width: 350px;

    background-color: #e6e3e3;
    color: #000000;
    border-radius: 7px;

    border: 1px #a8a5a5 solid;
    position: relative;

    display: flex;
    flex-direction: column;
    gap: 20px;
}
.warning__heading {
    margin: 0;
}
.warning__close {
    position: absolute;
    top: 0;
    right: 0;
    font-size: 30px;
    margin: 0;
    padding: 2px;
    margin-top: 3px;
    line-height: 10px;
    background: transparent;
    border: none;

    cursor: pointer;
}
.warning__button {
    background: transparent;
    border: none;

    cursor: pointer;
    padding: 6px 12px;
    line-height: 15px;
    border-radius: 7px;

    font-size: 13px;
}
.warning__button:hover {
    opacity: 0.6;
}
.warngin__del {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}
.warngin__exit {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}
.warning__option {
    display: flex;
    gap: 10px;
}
.warning__message {
    color: #000000;
    font-weight: 600;
}
</style>
