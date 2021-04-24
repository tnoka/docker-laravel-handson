<template>
    <div class="pagination">
        <div class="mx-auto my-4">
        <RouterLink
        v-if="! isFirstPage"
        :to="`/?page=${currentPage - 1}`"
        class="button"
        >&laquo; 前のページ</RouterLink>
        <RouterLink
        v-if="! isLastPage"
        :to="`/?page=${currentPage + 1}`"
        class="button"
        >次のページ &raquo;</RouterLink>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        currentPage: {
        type: Number,
        required: true
        },
        lastPage: {
        type: Number,
        required: true
        },
    },
    computed: {
        isFirstPage () {
        return this.currentPage === 1
        },
        isLastPage () {
        return this.currentPage === this.lastPage
        }
    },
    }
</script>