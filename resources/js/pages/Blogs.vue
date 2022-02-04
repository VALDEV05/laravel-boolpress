<template>
  <div class="Blog">
      <h1 class="text-center">Blog-Page</h1>
      <div class="container">
        <div class="row">
          <div class="col-md-4" v-for="post in posts">
            <div class="card mt-5 shadow-lg" style="height:200px">
              <div class="card-body text-center d-flex flex-column justify-content-between">
                <h4 class="card-title">{{ post.title }}</h4>
                <p class="card-text">{{ post.sub_title }}</p>
                <a href="#" class="text-decoration-none text-uppercase text-primary btn btn-outline-primary btn-lg">View More</a>
              </div>
            </div>
          </div>
          
        </div>
      </div>
      <!-- <div class="post" v-for="post in posts"></div> -->
  </div>
</template>

<script>
export default {
  data(){
    return{
      posts:{},

    }
  },
  mounted(){
    axios
      .get('api/posts')
      .then(response =>{
          console.log(response.data.data);
          this.posts = response.data.data;
      })
  }
}
</script>

<style>

</style>