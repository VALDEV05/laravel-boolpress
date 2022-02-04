<template>
  <div class="blog container">

    <div class="post text-center">
      <h1>{{ post.title }}</h1>
      <h5 class="text-muted">{{ post.title }}</h5>
      <div id="img">
        <img :src="'/storage/' + post.cover" alt="post.title" style="max-width: 100%;">
      </div>
      <div class="body mt-3">
        <p class="mb-0">
          {{ post.body }}
        </p>
      </div>
    </div>

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