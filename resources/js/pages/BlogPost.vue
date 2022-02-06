<template>
  <div class="blog container">
    <div class="post text-center">
      <div class="d-flex">
          <h1>{{ post.title }}</h1>
          <div class="btn btn-outline-primary ml-auto d-flex justify-content-center align-items-center" @click="back"><i class="fa fa-backward" aria-hidden="true"></i></div>
      </div>
      
      <h5 class="text-muted text-left">{{ post.sub_title }}</h5>
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
      post:{},
    }
  },
  mounted(){
    axios.get('/api/posts/' + this.$route.params.slug )
    .then((response) => {
      this.post = response.data.data;
    }).catch(error =>{
      console.error(error);
    });

  },
  methods:{
    back(){
      this.$router.go(-1)
    }
  }
}
</script>

<style>

</style>