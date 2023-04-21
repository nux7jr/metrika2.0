<template>
    <div v-if="this.user" class="metrikaheader container">
        <nav class="nav">
            <a class="nav__link nav__heading" href="/">
                <img
                    class="nav__logo"
                    src="../../../images/logos/logo.webp"
                    alt="logo"
                />
            </a>
            <!-- <ul class="nav__list">
                <li>
                    <a class="nav__link nav__option" href="/register">
                        Регистрация
                    </a>
                </li>
                <li>
                    <a class="nav__link nav__option nav__login" href="/login">
                        Вход
                    </a>
                </li>
            </ul> -->
            <ul class="nav__list">
                <li class="nav__text">
                    Привет,
                    {{ user.name }}
                </li>
                <li>
                    <a class="nav__link nav__option nav__login" href="/logout">
                        Выход
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
export default {
    name: "metrikaheader",

    data() {
        return {};
    },
    props: {
        is_auth: "",
        user: "",
    },
};
</script>

<style scoped>
.nav__heading {
    font-style: normal;
    font-weight: 700;
    font-size: 30px;
    line-height: 28px;
    letter-spacing: 1px;
    color: #14142b;
}
.nav__list {
    list-style: none;
    gap: 20px;
}
.nav__list,
.nav {
    display: flex;
}
.nav__logo {
    width: 190px;
}
.nav {
    justify-content: space-between;
    width: -webkit-fill-available;

    padding: 10px;

    align-items: center;
}
.metrikaheader {
    display: flex;
    align-items: center;
}

.nav__option {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    padding: 6px 12px;
    gap: 16px;

    font-style: normal;
    font-weight: 600;
    font-size: 16px;
    line-height: 28px;
    color: #fcfcfc;
    background: #8dccec;
    border-radius: 3px;

    flex: none;
    order: 0;
    flex-grow: 1;

    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
        border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.nav__login {
    background: transparent;
    border: 1px solid #8dccec;
    color: #8dccec;
}
.nav__option:hover {
    background: #80c0df;
}
.nav__login:hover {
    background: transparent;
    border: 1px solid #74b1d0;
    color: #74b1d0;
}
.nav__text {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    gap: 16px;

    font-style: normal;
    font-weight: 600;
    font-size: 16px;
    line-height: 28px;
    color: #000000;
}
</style>
