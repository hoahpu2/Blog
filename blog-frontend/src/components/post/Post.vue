<template>
    <div class="text-center">
      <div class="col-sm-12">
        <h4 style="margin-top: 30px;"><small><button class="btn btn-success" v-on:click="navigate()"> View All Posts </button></small></h4>
        <hr>
        <h2>{{ post.title }}</h2>
        <div class="form-group col-md-12">
            <img v-if="(post.file != null)" :src="'data:image/jpeg;base64,'+post.file" alt="Logo" width="200px" height="200px">
        </div>
        <h5><span class="glyphicon glyphicon-time"></span> Post by {{post.author}}, {{post.date_posted}}.</h5>
        <p> {{ post.body }} </p>
    
      </div>
      <hr style="width: 98%;">
      <div class="container">
        <div class="be-comment-block">
          <h1 class="comments-title" style="text-align: left;">Comments ({{ comments.length }})</h1>
          <div class="be-comment" v-for="comment in comments" :key="comment.id">
            <div class="be-img-comment">	
              <a href="blog-detail-2.html">
                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="be-ava-comment">
              </a>
            </div>
            <div class="be-comment-content">
              
                <span class="be-comment-name">
                  <a href="#" style="margin-left: -387px;">No body</a>
                  </span>
                <span class="be-comment-time">
                  <i class="fa fa-clock-o"></i>
                  May 27, 2015 at 3:14am
                </span>

              <p class="be-comment-text" style="text-align: left;">
                {{ comment.content}} 
              </p>
            </div>
          </div>
        </div>
      </div>
      <hr style="width: 98%;">
      <div class="col-md-12 form-wrapper">
        <p style="text-align: left;margin-left:15px"> <b>Comment Post</b> </p>
        <form id="edit-post-form" @submit.prevent="createComment">
          <div class="form-group col-md-12">
              <textarea id="content" style="width: 600px" cols="30" rows="2" v-model="content" class="form-control"></textarea>
          </div>
          <div class="form-group col-md-1 pull-left">
              <button class="btn btn-success" type="submit" style="margin-left: -65px;"> Comment </button>
          </div>
        </form>
      </div>
    </div>
    
</template>

<script>
import { server } from "../../utils/helper";
import axios from "axios";
import router from "../../router";
import qs from 'qs';
export default {
  data() {
    return {
      id: 0,
      post: {},
      comments: [],
      content: '',
    };
  },
  created() {
    this.id = this.$route.params.id;
    this.getPost();
    this.fetchComment();
  },
  methods: {
    fetchComment() {
      axios
        .get(`${server.baseURL}/posts/getCommentByPost/${this.id}`)
        .then(data => (this.comments = data.data));
    },
    getPost() {
      axios
        .get(`${server.baseURL}/posts/getDetail/${this.id}`)
        .then(data => (this.post = data.data));
    },
    navigate() {
      router.go(-1);
    },
    createComment() {
      let postData = {
        content: this.content,
        post_id: this.id
      };
      this.__submitToServer(postData);
    },
    __submitToServer(data) {
      axios.post(`${server.baseURL}/posts/createComment/`, qs.stringify(data), {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
            //'Content-Type': 'multipart/form-data; charset=utf-8; boundary=' + Math.random().toString().substr(2)
        }
      }).then(data => {
        console.log(data);
        this.fetchComment();
        this.content = '';
        // window.location.reload();
      });
    }
  }
};
</script>

