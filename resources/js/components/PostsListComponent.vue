<template>
    <section class="posts row">
        <div class="post col-md-4" v-for="post in posts">
            <div class="card">
                <div class="card-body">
                    <h3>{{ post.title }}</h3>
                    <p class="text-muted">{{ post.title }}</p>
                </div>
            </div>
         </div>
    </section>
</template>

<script>
    export default {
        data(){
            return{
                loading: true,
                posts: {},
                meta: {},
                links: {},
            };
        },
        mounted(){
            axios
                .get('api/posts')
                .then(response => {
                    console.log(response.data.data);
                    this.posts = response.data.data;
                    this.meta = response.data.meta;
                    this.links = response.data.links;
                    this.loading = false;
                })
        }
    }
</script>

<style>

</style> 