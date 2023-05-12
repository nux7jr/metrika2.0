<template>
    <div v-if="name" class="metrikaheader container">
        <nav class="nav">
            <h1 class="nav__heading">{{ title }}</h1>
            <ul class="nav__list">
                <li class="nav__text">
                    Привет,
                    {{ name }}
                </li>
                <li>
                    <a class="nav__link nav__login" href="/logout"> Выход </a>
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
        name: "",
        title: "",
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
    color: #ffffff;
    margin: 10px 0;
}
.nav__list {
    list-style: none;
    gap: 20px;
}
.nav__list,
.nav {
    display: flex;
}
.nav {
    justify-content: space-between;
    width: -webkit-fill-available;

    padding: 10px;
    padding-left: 0px;

    align-items: center;
}
.metrikaheader {
    display: flex;
    align-items: center;
}

.nav__login {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    padding: 8px 14px;
    gap: 16px;
    font-style: normal;
    font-weight: 600;
    font-size: 14px;
    color: #fcfcfc;
    background: #2196f3;
    border-radius: 7px;
    border-color: transparent;
    flex: none;
    order: 0;
    flex-grow: 1;
    cursor: pointer;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
        border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.nav__login:hover {
    background: #1a7bca;
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
    color: #ffffff;
}
</style>
