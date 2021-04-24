<template>
    <div class="container--small">
        <h2 class="title text-center">新規投稿</h2>
        <div class="panel">
            <div v-show="loading" class="panel">
                <Loader>Sending your Book...</Loader>
            </div>
            <form v-show="! loading" class="form" @submit.prevent="submit">
                <div class="errors" v-if="errors">
                    <ul v-if="errors.title">
                        <li v-for="msg in errors.title" :key="msg">{{ msg }}</li>
                    </ul>
                    <ul v-if="errors.author">
                        <li v-for="msg in errors.author" :key="msg">{{ msg }}</li>
                    </ul>
                    <ul v-if="errors.recommend">
                        <li v-for="msg in errors.recommend" :key="msg">{{ msg }}</li>
                    </ul>
                    <ul v-if="errors.text">
                        <li v-for="msg in errors.text" :key="msg">{{ msg }}</li>
                    </ul>
                    <ul v-if="errors.product_image">
                        <li v-for="msg in errors.product_image" :key="msg">{{ msg }}</li>
                    </ul>
                </div>
                <label for="title">タイトル</label>
                <input type="text" class="form__item" id="title" v-model="title">

                <label for="author">著者</label>
                <input type="text" class="form__item" id="author" v-model="author">

                <label for="recommend">おすすめ度</label>
                <select name="recommend" class="form-control mb-3" id="recommend" v-model="recommend">
                    <option value="-">選択してください</option>
                    <option value="★★★★★">★★★★★</option>
                    <option value="★★★★">★★★★</option>
                    <option value="★★★">★★★</option>
                    <option value="★★">★★</option>
                    <option value="★">★</option>
                </select>

                <label for="text">メモ</label>
                <textarea type="text" class="form__item" id="text" v-model="text" rows="10"></textarea>

                <label for="product_image">本の画像</label>
                <input type="file" id="product_image" class="form__item" @change="onFileChange">
                <output class="form__output" v-if="preview">
                    <img :src="preview">
                </output>
                
                <div class="form__button text-center my-3">
                    <button type="submit" class="btn button--inverse btn-lg">投稿する</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from '../util'
import Loader from '../components/Loader.vue'

export default {
    components: {
        Loader
    },
    
    data() {
        return {
            loading: false,
            preview: null,
            title: '',
            author: '',
            recommend: '',
            text: '',
            product_image: null,
            errors: null,
        }
    },
    methods: {
        // フォームでファイルが選択された場合実行
        onFileChange(event) {
            // 何も選択されたいなかったら処理中断
            if(event.target.files.length === 0) {
                this.reset()
                return false
            }
            // ファイルが画像ではなかったら処理中断
            if (! event.target.files[0].type.match('image.*')) {
                this.reset()
                return false
            }
            // FileReaderクラスのインスタンスを取得
            const reader = new FileReader()

            // ファイルを読み込み終わったタイミングで実行する処理
            reader.onload = e => {
                //previewに読み込み結果を代入
                this.preview = e.target.result
            }
            // ファイルを読み込む
            reader.readAsDataURL(event.target.files[0])
            this.product_image = event.target.files[0]
        },
        
        // 入力欄の値とプレビュー表示をクリアするメソッド
        reset() {
            this.preview = ''
            this.product_image = null
            this.$el.querySelector('input[type="file"]').value = null //this.$elはコンポーネントそのもののDOM要素
        },
        async submit() {
            this.loading = true

            const formData = new FormData()
            formData.append('title', this.title)
            formData.append('author', this.author)
            formData.append('recommend', this.recommend)
            formData.append('text', this.text)
            formData.append('product_image', this.product_image)
            const response = await axios.post('/api/products', formData)

            this.loading = false

            if(response.status === UNPROCESSABLE_ENTITY) {
                this.errors = response.data.errors
                return false
            }

            this.reset()

            if(response.status !== CREATED) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            // メッセージ登録
            this.$store.commit('message/setContent', {
                content: '本棚に飾りました！',
                timeout: 6000
            })

            this.$router.push('/')
            },
    },
}
</script>