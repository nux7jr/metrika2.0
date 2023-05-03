<template>
    <button @click="add_user" class="def__button">
        + Создать пользователя
    </button>
    <div class="mtable">
        <div class="mtable__header">
            <div class="mtable__heading">ID</div>
            <div class="mtable__heading mtable__heading-name">Имя</div>
            <div class="mtable__heading">Логин</div>
            <div class="mtable__heading">ID телеграмма</div>
            <div class="mtable__heading">Роль</div>
            <div class="mtable__heading">Города</div>
            <div class="mtable__heading">Дата создания</div>
            <div class="mtable__heading">Дата изменения</div>
            <div class="mtable__heading">Сохранить/Удалить</div>
        </div>
        <div class="mtable__wrapper">
            <form
                v-for="user in users"
                :key="user.id"
                class="mtable__col"
                method="POST"
                action="user"
                @submit.prevent="send_info"
            >
                <div class="mtable__item">{{ user.id }}</div>
                <input name="id" type="hidden" v-bind:value="user.id" />
                <input
                    name="name"
                    class="mtable__item mtable__name"
                    contenteditable="true"
                    v-bind:value="user.name"
                    required
                />
                <input
                    name="login"
                    class="mtable__item mtable__login"
                    contenteditable="true"
                    v-bind:value="user.login"
                    type="text"
                    required
                />
                <input
                    name="telegramID"
                    type="number"
                    class="mtable__item mtable__telega"
                    contenteditable="true"
                    v-bind:value="user.telegramID"
                    required
                />
                <div class="multiselect mtable__secondary">
                    <div class="selectBox">
                        <select class="mtable__select">
                            <option class="mtable__select">
                                Выберете роль
                            </option>
                        </select>
                        <div
                            class="overSelect"
                            v-on:click="show_checkboxes"
                        ></div>
                    </div>
                    <div class="checkboxes checkboxes_role">
                        <label v-for="(value, name, index) in user.role">
                            <div v-if="value">
                                <input
                                    class="role_input"
                                    type="checkbox"
                                    v-bind:value="name"
                                    checked
                                />{{ name }}
                            </div>
                            <div v-else>
                                <input
                                    class="role_input"
                                    type="checkbox"
                                    v-bind:value="name"
                                />{{ name }}
                            </div>
                        </label>
                    </div>
                </div>

                <div class="multiselect mtable__secondary">
                    <div class="selectBox">
                        <select class="mtable__select">
                            <option class="mtable__option">
                                Выберете город
                            </option>
                        </select>
                        <div
                            class="overSelect"
                            v-on:click="show_checkboxes"
                        ></div>
                    </div>
                    <div class="checkboxes checkboxes-site">
                        <div class="checkboxes__header">
                            <input
                                class="selectAll"
                                type="checkbox"
                                v-on:click="set_all_checkboxes"
                            />
                            <input
                                class="search"
                                v-model="search"
                                type="search"
                                placeholder="Поиск..."
                            />
                        </div>
                        <label
                            class="checkboxes__label"
                            v-for="item in search_handler"
                        >
                            <div
                                class="city__item"
                                v-if="is_city_active(item, user.city)"
                            >
                                <input
                                    type="checkbox"
                                    class="city_input"
                                    v-bind:value="item"
                                    checked
                                />{{ item }}
                            </div>
                            <div class="city__item" v-else>
                                <input
                                    type="checkbox"
                                    class="city_input"
                                    v-bind:value="item"
                                />
                                {{ item }}
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mtable__item">
                    {{ user.birthtime }}
                </div>
                <div class="mtable__item">
                    {{ user.edittime }}
                </div>
                <div class="mtable__item mtable__option">
                    <button type="submit" class="def__button">
                        <span class="loader"></span>
                        Сохранить
                    </button>
                    <button class="def__button del__button">
                        <span class="loader"></span>
                        Удалить
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { city } from "../../helper/getSity";

export default {
    name: "MetrikaUsers",
    data() {
        return {
            info: [],
            expanded: false,
            users: [
                {
                    id: 0,
                    login: "admin",
                    name: "bar",
                    role: {
                        admin: false,
                        user: true,
                        partner: true,
                    },
                    city: ["Абакан", "Красняорск", "Канск", "ЧТО_ТО"],
                    birthtime: new Date().toISOString().split("T")[0],
                    edittime: new Date().toISOString().split("T")[0],
                    telegramID: 228228,
                },
                {
                    id: 1,
                    login: "real_admin",
                    name: "uff",
                    role: {
                        admin: true,
                        user: true,
                        partner: true,
                    },
                    city: ["MSK"],
                    birthtime: new Date().toISOString().split("T")[0],
                    edittime: new Date().toISOString().split("T")[0],
                    telegramID: 123123123,
                },
                {
                    id: 2,
                    login: "userA",
                    name: "TEST",
                    role: {
                        admin: false,
                        user: true,
                        partner: true,
                    },
                    city: ["MSKA", "Альметьевск"],
                    birthtime: new Date().toISOString().split("T")[0],
                    edittime: new Date().toISOString().split("T")[0],
                    telegramID: 123123123,
                },
                {
                    id: 3,
                    login: "userA",
                    name: "TEST",
                    role: {
                        admin: false,
                        user: true,
                        partner: true,
                    },
                    city: [""],
                    birthtime: new Date().toISOString().split("T")[0],
                    edittime: new Date().toISOString().split("T")[0],
                    telegramID: 123123123,
                },
            ],
            city: city,
            search: "",
        };
    },
    computed: {
        search_handler() {
            return this.city.filter((elem) => {
                return elem.toLowerCase().includes(this.search.toLowerCase());
            });
        },
    },
    created() {
        this.get_info();
        // console.log(this.city);
    },
    methods: {
        async get_info() {
            // const res = await fetch("http://127.0.0.1:8000/user");
            // this.info = await res.json();
        },
        send_info: function (evt) {
            // site
            const input_elements_sites =
                evt.target.querySelectorAll(".city_input");
            const checked_value_sities = [];
            for (let i = 0; input_elements_sites[i]; ++i) {
                if (input_elements_sites[i].checked) {
                    checked_value_sities.push(input_elements_sites[i].value);
                }
            }
            // role
            const input_elements_role =
                evt.target.querySelectorAll(".role_input");
            const checked_value_role = [];
            for (let i = 0; input_elements_role[i]; ++i) {
                if (input_elements_role[i].checked) {
                    checked_value_role.push(input_elements_role[i].value);
                }
            }
            const user_form = new FormData(evt.target);
            user_form.append("cities", JSON.stringify(checked_value_sities));
            user_form.append("role", JSON.stringify(checked_value_role));

            fetch("/asd", {
                method: "POST",
                body: user_form,
            });
        },
        is_city_active(cityArr, userCity) {
            for (let i = 0; i < userCity.length; i++) {
                if (userCity[i].includes(cityArr)) {
                    return true;
                }
            }
        },
        add_user() {
            this.users.push({
                id: this.users.length,
                login: "Логин",
                name: "Имя",
                role: {
                    admin: false,
                    user: true,
                    partner: false,
                },
                city: [""],
                birthtime: new Date().toISOString().split("T")[0],
                edittime: new Date().toISOString().split("T")[0],
                telegramID: 9999999999,
            });
        },
        set_all_checkboxes: function (evt) {
            const all_checkboxes =
                evt.target.parentElement.parentElement.querySelectorAll(
                    ".city_input"
                );
            if (evt.target.checked) {
                all_checkboxes.forEach((elem) => {
                    elem.setAttribute("checked", "checked");
                });
            } else {
                all_checkboxes.forEach((elem) => {
                    elem.removeAttribute("checked");
                });
            }
        },
        show_checkboxes: function (evt) {
            const checkboxes =
                evt.target.parentElement.parentElement.querySelector(
                    ".checkboxes"
                );
            if (!this.expanded) {
                checkboxes.style.display = "block";
                this.expanded = true;
            } else {
                checkboxes.style.display = "none";
                this.expanded = false;
            }
        },
    },
};
</script>

<style scoped>
.loader {
    width: 12px;
    height: 12px;
    border: 2px solid #fff;
    border-bottom-color: transparent;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
}
.mtable {
    color: white;
    font-weight: 300;

    border: 1px solid rgba(255, 255, 255, 0.235);
    margin-top: 7px;
    margin-right: 7px;
    border-radius: 7px;

    height: calc(100vh - 126px);
    overflow-y: scroll;
}
.mtable__col,
.mtable__header {
    padding-left: 10px;
    display: grid;
    grid-template-columns: 0.3fr 0.4fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
}
.mtable__header {
    position: sticky;
    top: 0;
    z-index: 4;
}
.mtable__col {
    border-bottom: 1px solid rgba(255, 255, 255, 0.235);
    padding-left: 10px;
    transition: 0.2s;
}
.mtable__col:hover {
    background: rgba(26, 103, 228, 0.231);
}
.mtable__col:last-child {
    margin-bottom: 700px;
}
.mtable__item {
    margin: 3px 0;
    max-width: 100px;
    align-items: center;

    display: flex;
}
.mtable__secondary {
    margin: 10px 0;
    max-width: 100px;
    align-items: center;
}
.mtable__login,
.mtable__telega,
.mtable__name {
    background-color: transparent;
    border: none;
    font-family: "Montserrat", sans-serif;
    color: white;
}
.mtable__login:focus,
.mtable__telega:focus,
.mtable__name:focus {
    outline: 1px #2196f3 solid;
}
.mtable__header {
    background: #222628;
    padding: 10px 0;
    font-weight: 600;
    border-radius: 7px;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    padding-left: 10px;
}
.def__button {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    padding: 8px 10px;
    gap: 9px;
    font-style: normal;
    font-weight: 600;
    font-size: 12px;
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
.del__button {
    background-color: #dc3545;
}
.mtable__option {
    display: flex;
    gap: 10px;
    padding-right: 10px;
}
.multiselect {
    width: 200px;
    position: relative;
}

.selectBox {
    position: relative;
    width: 160px;
}

.selectBox select {
    width: 100%;
    font-weight: bold;
}

.overSelect {
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;

    cursor: pointer;
}

.checkboxes {
    display: none;
    border: 1px #dadada5f solid;
    border-radius: 4px;
    background-color: #3b3f41;
    z-index: 1;
    position: absolute;

    height: 500px;
}
.checkboxes_role {
    height: 200px;
    width: 160px;
}
.checkboxes label {
    display: block;
    font-family: "Montserrat", sans-serif;
}
.overSelect,
.city_input,
.mtable__select,
.mtable__option {
    font-family: "Montserrat", sans-serif;
    font-style: normal;
    font-weight: 500 !important;
}
.mtable__select {
    background-color: transparent;
    color: white;
    border: none;
    padding: 5px 0px;
    margin-left: -7px;
}
.mtable__heading-name {
    width: 104px;
}
.checkboxes label:hover {
    background-color: #1e90ff;
}
.checkboxes .checkboxes__label:nth-child(2) {
    padding-top: 10px;
}
.checkboxes__header {
    display: flex;
    padding: 5px;
    position: sticky;
    top: 0;
    background: #222628;
}
.checkboxes__label {
    padding: 5px;
}
.checkboxes-site {
    width: 267px;
    overflow-y: scroll;
}
.search {
    width: -webkit-fill-available;
    outline: none;

    font-family: "Montserrat", sans-serif;
    border-radius: 2px;
    border-color: transparent;
}
</style>
