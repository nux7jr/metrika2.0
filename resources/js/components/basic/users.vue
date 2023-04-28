<template>
    <button class="def__button">+ Создать пользователя</button>
    <div class="mtable">
        <div class="mtable__header">
            <div class="mtable__heading">ID</div>
            <div class="mtable__heading">Логин</div>
            <div class="mtable__heading">Имя</div>
            <div class="mtable__heading">Роль</div>
            <div class="mtable__heading">Города</div>
            <div class="mtable__heading">Дата создания</div>
            <div class="mtable__heading">Дата изменения</div>
            <div class="mtable__heading">Сохранить/Удалить</div>
        </div>
        <div class="mtable__wrapper">
            <form class="mtable__col">
                <div class="mtable__item">ID</div>
                <input
                    class="mtable__item mtable__login"
                    contenteditable="true"
                    value="Логин"
                />
                <div class="mtable__item" contenteditable="true">Имя</div>
                <select class="mtable__item">
                    <option>Пункт 1</option>
                    <option>Пункт 2</option>
                </select>

                <div class="multiselect mtable__item">
                    <div class="selectBox" @click="showCheckboxes()">
                        <select>
                            <option>Выберете город</option>
                        </select>
                        <div class="overSelect"></div>
                    </div>
                    <div id="checkboxes">
                        <label for="one">
                            <input type="checkbox" id="one" />Крансоярск</label
                        >
                        <label for="two">
                            <input type="checkbox" id="two" />
                            Что-то</label
                        >
                        <label for="three">
                            <input type="checkbox" id="three" />Питер</label
                        >
                    </div>
                </div>
                <div class="mtable__item" contenteditable="true">avalible1</div>
                <div class="mtable__item" contenteditable="true">avalible1</div>
                <div class="mtable__item mtable__option" contenteditable="true">
                    <button class="def__button">Сохранить</button>
                    <button class="def__button del__button">Удалить</button>
                </div>
            </form>
            <form class="mtable__col">
                <div class="mtable__item" contenteditable="true">ID2</div>
                <div class="mtable__item" contenteditable="true">login2</div>
                <div class="mtable__item" contenteditable="true">name2</div>
                <div class="mtable__item" contenteditable="true">role2</div>
                <div class="mtable__item" contenteditable="true">
                    permiss2on
                </div>
                <div class="mtable__item" contenteditable="true">avalib2le</div>
            </form>
            <form class="mtable__col">
                <div class="mtable__item" contenteditable="true">ID3</div>
                <div class="mtable__item" contenteditable="true">login</div>
                <div class="mtable__item" contenteditable="true">nam3e</div>
                <div class="mtable__item" contenteditable="true">role</div>
                <div class="mtable__item" contenteditable="true">permisson</div>
                <div class="mtable__item" contenteditable="true">ava3lible</div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "MetrikaUsers",

    data() {
        return {
            info: [],
            expanded: false,
        };
    },

    mounted() {},
    created() {
        this.get_info();
    },
    methods: {
        async get_info() {
            const res = await fetch("http://127.0.0.1:8000/user");
            this.info = await res.json();
        },

        showCheckboxes() {
            var checkboxes = document.getElementById("checkboxes");
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
.mtable {
    color: white;
    font-weight: 300;

    border: 1px solid rgba(255, 255, 255, 0.235);
    margin-top: 7px;
    margin-right: 7px;
    border-radius: 7px;
}
.mtable__col,
.mtable__header {
    padding-left: 10px;
    display: grid;
    grid-template-columns: 0.3fr 0.4fr 1fr 1fr 1fr 1fr 1fr 1fr;
}
.mtable__col {
    border-bottom: 1px solid rgba(255, 255, 255, 0.235);
    padding-left: 10px;
}
.mtable__item {
    margin: 10px 0;
    max-width: 100px;
}
.mtable__login {
    background-color: transparent;
    border: none;
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
}

.selectBox {
    position: relative;
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
}

#checkboxes {
    display: none;
    border: 1px #dadada solid;
    background-color: #3b3f41;
    position: absolute;
}

#checkboxes label {
    display: block;
}

#checkboxes label:hover {
    background-color: #1e90ff;
}
</style>
