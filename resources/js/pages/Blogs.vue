<template>
  <div class="Blog">
    <div class=" bg-light">
      <div class="container text-center">
        <h1 class="display-4"><i class="fas fa-newspaper fa-lg fa-fw  "></i> BLOG <i class="fas fa-newspaper fa-lg fa-fw  "></i></h1>
        <p class="lead text-muted text-capitalize">here we will show all the posts of our blog</p>
        <hr class="mt-2">
      </div>
    </div>

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
        <div class="pagination d-flex justify-content-center mt-3">
          <span class="btn text-secondary text-capitalize" @click="prevPage">prev</span>
          <span class="btn btn-outline-primary">{{meta.current_page}}</span>
          <span class="btn text-secondary text-capitalize" @click="nextPage">next</span>
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
      url: 'api/posts'

    }
  },
  mounted(){
    this.fetchPosts(this.url);
  },
  methods:{
    nextPage(){
      console.log('pagina successiva');
    },prevPage(){
      console.log('pagina precendente');
    },
    fetchPosts(url){
      axios
        .get(url)
        .then(response =>{
            this.posts = response.data.data;
            this.meta = response.data.meta;
            this.links = response.data.links;
            this.loading = true;
            console.log(this.posts);
        })
    }

  }
}
</script>

<style>

</style>