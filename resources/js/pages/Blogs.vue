<template>
  <div class="Blog">
    <h1 class="text-center">Blog-Page</h1>
    <div class="container">
        <div class="row">
            <div class="col-md-4" v-for="post in posts">
                <div class="card mt-5 shadow-lg" style="height:250px">
                    <div class="card-body text-center d-flex flex-column justify-content-between">
                        <h4 class="card-title">{{ post.title }}</h4>
                        <p class="card-text">{{ post.sub_title }}</p>
                        <p>{{ post.slug }}</p>
                        <router-link :to="'/blogs/' + post.slug" class="text-decoration-none text-uppercase text-primary btn btn-outline-primary btn-lg">View More</router-link>
                    </div>    
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return{
      posts:{},
      links:{},
      meta:{},
      loading: false,

    }
  },
  mounted(){
    axios
      .get('api/posts')
      .then(response =>{
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