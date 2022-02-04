<template>
  <div class="blog">
      <h1 class="text-center">Single game</h1>
      <p class="text-center mb-0">{{ $route.params.slug }}</p>
      <h4 class="text-center">{{ post.title  }}</h4>
  </div>
</template>

<script>
export default {
  data(){
    return{
      post:{}
    }
  },
  mounted(){
    axios.get('/api/posts/' + this.$route.params.slug )
    .then((response) => {
      console.log(response.data.data)
      this.post = response.data.data;
    }).catch(error =>{
      console.error(error);
    });
  }
}
</script>

<style>

</style>