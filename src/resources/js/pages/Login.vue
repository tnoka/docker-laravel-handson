<template>
    <div class="container--small">
        <ul class="tab">
            <li
                class="tab__item"
                :class="{'tab__item--active': tab === 1 }"
                @click="tab = 1"
            >ログイン</li>
            <li class="tab__item"
                :class="{'tab__item--active': tab === 2 }"
                @click="tab = 2"
            >新規登録</li>
        </ul>
        <div class="panel" v-show="tab === 1">
            <form class="form" @submit.prevent="login">
                <div v-if="loginErrors" class="errors">
                    <ul v-if="loginErrors.email">
                        <li v-for="msg in loginErrors.email" :key="msg">{{ msg }}</li>
                    </ul>
                    <ul v-if="loginErrors.password">
                        <li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li>
                    </ul>
                </div>
                <label for="login-email">メールアドレス</label>
                <input type="text" class="form__item" id="login-email" v-model="loginForm.email">
                <label for="login-password">パスワード</label>
                <input type="password" class="form__item" id="login-password" v-model="loginForm.password">
                <div class="form__button text-center my-3">
                    <a :href="'/'"><button type="submit" class="btn btn-lg button--inverse">ログイン</button></a>
                </div>
            </form>
        </div>
        <div class="panel" v-show="tab === 2">
            <form class="form" @submit.prevent="register">
                <div v-if="registerErrors" class="errors">
                    <ul v-if="registerErrors.name">
                        <li v-for="msg in registerErrors.name" :key="msg">{{ msg }}</li>
                    </ul>
                    <ul v-if="registerErrors.email">
                        <li v-for="msg in registerErrors.email" :key="msg">{{ msg }}</li>
                    </ul>
                    <ul v-if="registerErrors.password">
                        <li v-for="msg in registerErrors.password" :key="msg">{{ msg }}</li>
                    </ul>
                </div>
                <label for="username">ユーザーネーム</label>
                <input type="text" class="form__item" id="username" v-model="registerForm.name">

                <label for="email">メールアドレス</label>
                <input type="text" class="form__item" id="email" v-model="registerForm.email">

                <label for="password">パスワード（8文字以上）</label>
                <input type="password" class="form__item" id="password" v-model="registerForm.password">

                <label for="password-confirmation">パスワード（再入力）</label>
                <input type="password" class="form__item" id="password-confirmation" v-model="registerForm.password_confirmation">

                <div class="form__button text-center my-3">
                    <button type="submit" class="btn btn-lg button--inverse">新規登録</button>
                </div>
            </form>
        </div>

        <form @submit.prevent="easy" class="py-5 text-center d-flex flex-column">
            <a :href="'auth/twitter'" class="mb-5 text-center mx-auto btn btn-primary btn-lg">Twitterログイン</a>
            <input type="hidden" v-model="easyLogin.email" id="login-email">
            <input type="hidden" v-model="easyLogin.password" id="login-password">
            <a :href="'/'"><button type="submit" class="btn btn-warning btn-outline-dark mt-2" style="font-weight:800;">簡単ログイン（ポートフォリオ閲覧用）</button></a>
        </form>
    </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
    data() {
        return {
            tab: 1,
            loginForm: {
                email: '',
                password: '',
            },
            easyLogin: {
                email: 'a@a.com',
                password: 'aaaaaaaa',
            },
            registerForm: {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            }
        }
    },
    computed: {
        ...mapState({
        apiStatus: state => state.auth.apiStatus,
        loginErrors: state => state.auth.loginErrorMessages,
        registerErrors: state => state.auth.registerErrorMessages
        })
    },

    methods: {
        async login() {
            await this.$store.dispatch('auth/login', this.loginForm)
            if(this.apiStatus){
                this.$router.go('/')
            }
        },
        async easy() {
            await this.$store.dispatch('auth/login', this.easyLogin)
            if(this.apiStatus){
                this.$router.go('/')
            }
        },       
        async register () {
            await this.$store.dispatch('auth/register', this.registerForm)
            if(this.apiStatus){
                this.$router.go('/')
            }
        },
        clearError() {
            this.$store.commit('auth/setLoginErrorMessages', null)
            this.$store.commit('auth/setRegisterErrorMessages', null)
        }
    },
    created() {
        this.clearError()
    }
}
</script>