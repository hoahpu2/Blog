<template>
    <div>
      <div class="text-center">
        <h1>Blog Tutorial</h1>
       <p> This is the description of the blog built with Cake2, Vue.js and MySql</p>

       <div v-if="posts.length === 0">
            <h2> No post found at the moment </h2>
        </div>
      </div>
       
        <div class="row">
           <div class="col-md-4" v-for="post in posts" :key="post.id">
              <div class="card mb-4 shadow-sm">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-2">
                      <img style="border-radius: 50%;" v-if="post.file != null" :src="'data:image/jpeg;base64,'+post.file" alt="Logo" width="100px" height="100px">
                    </div>
                    <div class="col-md-4">
                      <h2 class="card-img-top">{{ post.title }}</h2>
                      <p class="card-text">{{ post.description }}</p>
                    </div>
                  </div>
                  <br>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group" style="margin-bottom: 20px;">
                      <router-link :to="{name: 'Post', params: {id: post.id}}" class="btn btn-sm btn-outline-secondary">View Post </router-link>
                        <router-link :to="{name: 'Edit', params: {id: post.id}}" class="btn btn-sm btn-outline-secondary">Edit Post </router-link>
                        <button class="btn btn-sm btn-outline-secondary" v-on:click="deletePost(post.id)">Delete Post</button>
                    </div>
                  </div>

                  <div class="card-footer">
                    <small class="text-muted">Posted on: {{ post.date_posted}}</small><br/>
                    <small class="text-muted">by: {{ post.author}}</small>
                  </div>
                   
                </div>
              </div>
            </div>
      </div>
      <div style="text-align:center">
        <a href="#" v-for="page in totalPage" :key="page" v-on:click="ganCurrent(page)"> {{ page }} </a> 
      </div>
    </div>
</template>

<script>
// @ is an alias to /src
import { server } from "@/utils/helper";
import axios from "axios";
import Paginate from 'vuejs-paginate'

export default {
  data() {
    return {
      posts: [],
      currentPage:1,
      totalPage:0
    };
  },
  created() {
    this.fetchPosts();
  },
  watch: {
    currentPage: function(){
      this.fetchPosts();
    }
  },
  methods: {
    ganCurrent(page){
      this.currentPage = page;
    },
    fetchPosts() {
      axios
        .get(`${server.baseURL}/posts/?page=`+this.currentPage)
        .then(reponse => {
          this.posts = reponse.data.data;
          this.currentPage = reponse.data.currentPage;
          this.totalPage = reponse.data.totalPage;
          });
    },
    deletePost(id) {
      if(confirm('Are you sure delete?')){
        axios.get(`${server.baseURL}/posts/deletePost/${id}`).then(data => {
          console.log(data);
          this.fetchPosts();
        });
      }
    }
  }
};
</script>

