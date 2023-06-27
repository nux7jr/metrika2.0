<template> 
    <div v-if="user" ref="menu" class="menu" v-bind:class="{ isActive: isActive, isDisable: !isActive }">
        <div class="option">
            <input type="checkbox" class="menu__checkbox" id="menu__checkbox" />
            <label class="menu__label menu__item menu__toggle" @click="toggle_menu()" for="menu__checkbox">
                <div class="burder__item"></div>
                <div class="burder__item"></div>
                <div class="burder__item"></div>
            </label>
            <Transition name="slide-fade">
                <a class="menu__logo" href="/"><img class="logo__img" src="../../../images/logos/logo_metrika.png" alt="logo"/></a>
            </Transition>
        </div>
        <a class="menu__item" href="/">
            <img class="menu__img" src="../../../images/icons/home.svg" alt="icon"/>
            <Transition name="slide-fade"><span class="menu__text" v-if="isActive">Лиды</span></Transition>
        </a>
        <a class="menu__item" href="/visitors">
            <img class="menu__img" src="../../../images/icons/dashboard.svg" alt="icon"/>
            <Transition name="slide-fade"><span class="menu__text" v-if="isActive">Аунтификация посетителей</span></Transition>
        </a>
        <a class="menu__item" href="/day">
            <img class="menu__img" src="../../../images/icons/projects.svg" alt="icon"/>
            <Transition name="slide-fade"><span class="menu__text" v-if="isActive">Ежедневный отчет</span></Transition>
        </a>
        <a class="menu__item" href="/week">
            <img class="menu__img" src="../../../images/icons/check.svg" alt="icon"/>
            <Transition name="slide-fade"><span class="menu__text" v-if="isActive">Еженедельный отчет</span></Transition>
        </a>
        <a class="menu__item" href="/create">
            <img class="menu__img" src="../../../images/icons/userPlus.svg" alt="icon"/>
            <Transition name="slide-fade"><span class="menu__text" v-if="isActive">Создать пользователя</span></Transition>
        </a>
        <a class="menu__item" href="/conversion">
            <img class="menu__img" src="../../../images/icons/conv.svg" alt="icon"/>
            <Transition name="slide-fade"><span class="menu__text" v-if="isActive">Конверсия</span></Transition>
        </a>
        <a class="menu__item" href="/deal">
            <img class="menu__img" src="../../../images/icons/graph.svg" alt="icon"/>
            <Transition name="slide-fade"><span class="menu__text" v-if="isActive">Сделки</span></Transition>
        </a>
    </div>
</template>

<script>
export default {
    name: "MetrikaMenu",

    data() {
        return {
            isActive: this.get_menu(),
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
        ready(){
            
        },
        pre_check(protect){
            setTimeout(function(){ 
                if(protect == true){ 
                    document.querySelector('.menu__logo').style.opacity = 1;
                    document.querySelector('.menu__logo').style.display = 'block'; 
                } 
                if(protect == false){
                    document.querySelector('.menu__label').style.transform = 'rotate(-90deg)'; 
                    let burger = document.querySelectorAll('.burder__item');
                    burger[0].style.background = '#0cd4fb'; 
                        burger[0].style.opacity = '1';
                    burger[1].style.background = '#0cd4fb'; 
                        burger[1].style.opacity = '0.7';
                    burger[2].style.background = '#0cd4fb'; 
                        burger[2].style.opacity = '0.3';
                }
            }, 10);
        },
        toggle_menu(){
            this.isActive = !this.isActive;
            this.save_menu(this.isActive);
            if(this.isActive == false){ this.close_menu(); }
            if(this.isActive == true){ this.active_menu(); }                     
        },
        save_menu(active){
            sessionStorage.setItem("isactive", JSON.stringify(active));
        },
        get_menu(){
            let protect = JSON.parse(sessionStorage.getItem("isactive"));
            this.pre_check(protect);
            return protect;
        },
        close_menu(){
            document.querySelector('.menu__logo').style.opacity = 0;
            document.querySelector('.menu__logo').style.display = 'none'; 
            this.burger_animation('close');
        },
        active_menu(){
            document.querySelector('.menu__logo').style.display = 'none';
            document.querySelector('.menu__label').style.transform = 'rotate(0deg)'; 
            setTimeout(function(){
                document.querySelector('.menu__logo').style.display = 'block';
                setTimeout(function(){ document.querySelector('.menu__logo').style.opacity = 1; }, 50);  
            }, 650);
        },
        burger_animation(type){
            if(type == 'close'){
                document.querySelector('.menu__label').style.transform = 'rotate(-90deg)'; 
                let burger = document.querySelectorAll('.burder__item');
        
                burger[0].style.width = '40%';
                setTimeout(function(){ burger[0].style.width = '80%'; }, 250);
                setTimeout(function(){ burger[0].style.width = '0%'; }, 500);
                setTimeout(function(){ burger[0].style.background = '#0cd4fb'; burger[0].style.opacity = '1'; burger[0].style.width = '80%'; }, 750);
    
                burger[1].style.width = '140%';
                setTimeout(function(){ burger[1].style.width = '40%'; }, 250);
                setTimeout(function(){ burger[1].style.width = '0%'; }, 500);
                setTimeout(function(){ burger[1].style.background = '#0cd4fb'; burger[1].style.opacity = '0.7'; burger[1].style.width = '40%'; }, 750);
    
                burger[2].style.width = '10%';
                setTimeout(function(){ burger[2].style.width = '30%'; }, 250);
                setTimeout(function(){ burger[2].style.width = '0%'; }, 500);
                setTimeout(function(){ burger[2].style.background = '#0cd4fb'; burger[2].style.opacity = '0.3'; burger[2].style.width = '100%'; }, 750);
            }
        },
    },
    beforeMount() {
        this.ready();
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
    margin-left: 10px;
}
.menu {
    display: flex;
    flex-direction: column;
    gap: 7px;
    margin: 7px;
    border-radius: 5px;
    background-color: #181d1f;
    width: 56px;
    color: white;
    padding-top: 10px;
    transition: width 0.7s;
}
.menu__item {
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
    gap: 10px;
    height: 46px;
    background: linear-gradient(to left, #181d1f 50%, #2196f3 50%) right;
    background-size: 200%;
    border-radius: 5px;    
    color: rgb(255, 255, 255);
    margin: 5px;
    transition: 0.4s ease-out;
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
    width: 25px;
    height: 25px;
    cursor: pointer;
    transition: 0.3s all;
}
.menu__label .burder__item {
    width: 6px;
    height: 4px;
    margin-left: 0;
    margin-bottom: 6px;
    border-radius: 4px;
}
.menu__label .burder__item:first-child {
    width: 80%;
    max-width: 30px;
}
.menu__label .burder__item:nth-child(2) {
    width: 40%;
    max-width: 30px;
}

.menu__label .burder__item:last-child {
    width: 100%;
    margin-bottom: 0;
    max-width: 30px;
}
.menu__logo {
    display: none;
    opacity: 0;
    width: 130px;
    transition: 0.3s all;
}
.burder__item{
    opacity: 1;
    width: 100%;
    background: #ffffff;
    max-width: 130px;
    transition: 0.2s all;      
}
.option {
    display: flex;
    justify-content: space-between;
    padding: 0 10px;
    height: 40px;
}
</style>
