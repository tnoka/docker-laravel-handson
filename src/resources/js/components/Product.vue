<template>
    <div class="product col-6 col-sm-4">
        <div v-if="isLogin">
            <img
            class="img-fluid mb-4"
            :src="item.url"
            ref="image"
            >

        <a :href="'/products/'+item.id" class="product__overlay">
        <div class="product__controls">
            <button class="product__action product__action--favorite"
                    :class="{ 'product__action--favorited': item.favorited_by_user }"
                    title="読みたい本"
                    @click.prevent="favorite">
            <i class="fa fa-heart"></i> {{ item.favorite_count }}
            </button>
        </div>
        </a>
        </div>
        <div v-else>
            <img
            class="img-fluid mb-4"
            :src="item.url"
            ref="image"
            >

        <router-link to="/login" class="product__overlay">
        <div class="product__controls">
            <button class="product__action product__action--favorite"
                    :class="{ 'product__action--favorited': item.favorited_by_user }"
                    title="読みたい本"
                    @click.prevent="favorite">
            <i class="fa fa-heart"></i> {{ item.favorite_count }}
            </button>
        </div>
        </router-link>
    </div>

    </div>

</template>

<script>
export default {
    props: {
        item: {
            type: Object,
            required: true
        }
    },
    data () {
        return {
        landscape: false,
        portrait: false
        }
    },
    computed: {
        isLogin() {
            return this.$store.getters['auth/check']
        }
    },
    methods: {
        favorite() {
            this.$emit('favorite', {
                id: this.item.id,
                favorited: this.item.favorited_by_user
            })
        }
    },
    
    watch: {
        $route () {
        // ページが切り替わってから画像が読み込まれるまでの間に
        // 前のページの同じ位置にあった画像の表示が残ってしまうことを防ぐ
        this.landscape = false
        this.portrait = false
        }
    }
    }

</script>