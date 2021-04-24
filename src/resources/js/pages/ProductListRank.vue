<template>
        <div class="product-list">
            <loading 
                :active.sync="isLoading" 
                :can-cancel="true" 
                :on-cancel="onCancel"
                :is-full-page="fullPage">
            </loading>
            <div class="container" v-if="isLogin">
                <ul class="tab mb-4 justify-content-center">
                    <li class="tab__item pt-0 px-1"><router-link class="btn btn-outline-secondary btn-lg" to="/">新 着</router-link></li>
                    <li class="tab__item px-0 pt-0"><router-link class="btn btn-outline-secondary btn-lg" to="/indexFeed">フィード</router-link></li>
                    <li class="tab__item tab__item--active px-1 pt-0"><router-link class="btn btn-secondary btn-lg" to="/indexRank">人 気</router-link></li>
                </ul>
                <div  class="row">
                    <Product
                        v-for="product in products"
                        :key="product.id"
                        :item="product"
                        @favorite="onFavoriteClick"
                    />
                </div>
                <PaginationIndexRank :current-page="currentPage" :last-page="lastPage" />
            </div>

            <div class="container" v-else>
                <div  class="jumbotron jumbotron-extend home-header" style="background: url(../img/main.jpg) no-repeat center center; background-size: cover;">
                    <div class="container-fluid jumbotron-container">
                        <h1 class="display-4 site-name text-light text-center mt-5 top-title" style="">仮想本棚</h1>
                        <h3 class="site-name text-light text-center mt-5 top-title">読んだ本を本棚に飾り</h3>
                        <h3 class="site-name text-light text-center top-title">おすすめの本を共有しよう</h3>
                    </div>
                </div>
                <ul class="tab mb-3 justify-content-center">
                    <li class="tab__item"><router-link class="btn btn-outline-secondary btn-lg" to="/">新 着</router-link></li>
                    <li class="tab__item tab__item--active"><router-link class="btn btn-secondary btn-lg" to="/indexRank">人 気</router-link></li>
                </ul>
                <div class="row">
                    <Product
                        v-for="product in products"
                        :key="product.id"
                        :item="product"
                        @favorite="onFavoriteClick"
                    />
                </div>
                <PaginationIndexRank :current-page="currentPage" :last-page="lastPage" />
            </div>
        </div>
</template>


<script>
import Product from '../components/Product.vue'
import PaginationIndexRank from '../components/PaginationIndexRank.vue'
import { OK } from '../util'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    components: {
        Product,
        PaginationIndexRank,
        Loading
    },
    data() {
        return {
            tab: 2,
            products: [],
            currentPage: 0,
            lastPage: 0,
            isLoading: false,
            fullPage: true
        }
    },
    methods: {
        onCancel:function() {
            console.log('User cancelled the loader.')
            },

        async fetchProducts() {
            let self = this;    
            self.isLoading = true;
            // simulate AJAX
            setTimeout(function(){
                self.isLoading = false;
                console.log('load off');
            }, 400);
            const response = await axios.get(`/api/products/indexRank/?page=${this.$route.query.page}`)

            if(response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.products = response.data.data //resonse.dataでレスポンスのJSONを取得
            this.currentPage = response.data.current_page
            this.lastPage = response.data.last_page
        },
        onFavoriteClick({id, favorited}) {
            if(! this.$store.getters['auth/check']) {
                alert('読みたい本に追加する場合はログインしてください')
                return false
            }

            if(favorited) {
                this.unFavorite(id)
            } else {
                this.favorite(id)
            }
        },
        async favorite(id) {
            const response = await axios.put(`/api/products/${id}/favorite`)

            if(response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.products = this.products.map(product => {
                if(product.id === response.data.product_id) {
                    product.favorite_count += 1
                    product.favorited_by_user = true
                }
                return product
            })
        },
        async unFavorite(id) {
            const response = await axios.delete(`/api/products/${id}/favorite`)

            if(response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.products = this.products.map(product => {
                if(product.id === response.data.product_id) {
                    product.favorite_count -= 1
                    product.favorited_by_user = false
                }
                return product
            })
        }
    },
    computed: {
        isLogin() {
            return this.$store.getters['auth/check']
        }
    },
    watch: {
        $route: {
        async handler () {
            await this.fetchProducts()
        },
        immediate: true //コンポーネントが生成されたタイミングでも実行される
            }
        }
}
</script>