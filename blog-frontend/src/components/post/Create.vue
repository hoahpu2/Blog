<template>
   <div>
        <div class="col-md-12 form-wrapper">
          <h2> Create Post </h2>
          <form id="create-post-form" @submit.prevent="createPost">
               <div class="form-group col-md-12">
                <label for="title"> Title </label>
                <input type="text" id="title" v-model="title" name="title" required class="form-control" placeholder="Enter title">
               </div>
              <div class="form-group col-md-12">
                  <label for="description"> Description </label>
                  <input type="text" id="description" v-model="description" required name="description" class="form-control" placeholder="Enter Description">
              </div>
              <div class="form-group col-md-12">
                  <label for="body"> Write Content </label>
                  <textarea id="body" cols="30" rows="5" v-model="body" required class="form-control"></textarea>
              </div>
              <div class="form-group col-md-12">
                  <label for="file"> File </label>
                  <input type="file" id="file" ref="file" v-on:change="handleFileUpload()" class="form-control"/>
              </div>
              <div class="form-group col-md-12">
                  <label for="author"> Author </label>
                  <input type="text" id="author" v-model="author" required name="author" class="form-control">
              </div>
              

              <div class="form-group col-md-4 pull-right">
                  <button class="btn btn-success" type="submit"> Create Post </button>
              </div>          
          </form>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { server } from "../../utils/helper";
import router from "../../router";
import qs from 'qs';
export default {
  data() {
    return {
      title: "",
      description: "",
      body: "",
      author: "Ozawa",
      date_posted: "",
      file: "",
    };
  },
  created() {
    this.date_posted = new Date().toLocaleDateString();
  },
  methods: {
    handleFileUpload(){
      this.file = this.$refs.file.files[0];
    },
    createPost() {
      let postData = {
        title: this.title,
        description: this.description,
        body: this.body,
        author: this.author,
        date_posted: this.date_posted,
        file: this.file,
      };
      let formData = new FormData();

      /* <img :src="'data:image.png;base64,' + imageLink.result" alt="Logo">
          Add the form data we need to submit
      */
      formData.append('file', this.file);
      formData.append('title', this.title);
      formData.append('description', this.description);
      formData.append('body', this.body);
      formData.append('author', this.author);
      formData.append('date_posted', this.date_posted);
      this.__submitToServer(formData);
    },
    __submitToServer(data) {
      axios.post(`${server.baseURL}/posts/create/`, (data), {
        headers: {
            // 'Content-Type': 'application/x-www-form-urlencoded'
            'Content-Type': 'multipart/form-data; charset=utf-8; boundary=' + Math.random().toString().substr(2)
        }
      }).then(data => {
        console.log(data);
        router.push({ name: "home" });
      });
    }
  }
};
</script>

