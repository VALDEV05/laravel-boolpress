<template>
  <div class="blog">
      <h1 class="text-center">Single game</h1>
      <p class="text-center mb-0">{{ $route.params.slug }}</p>
  </div>
</template>

<script>
export default {
  data(){
    return{
      game:{}
    }
  },
  mounted(){
    axios.get('/api/posts/' + this.$route.params.slug )
    .then((response) => {
      console.log(response.data.data)
    }).catch(error =>{
      console.error(error);
    });
  }
}
</script>

<style>

</style>