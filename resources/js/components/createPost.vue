<template>

    <div>
       
        <div class="row">
            <div class="col-lg-12 col-md-12">
                   <div class="card">
                      <div class="card-header card-header-primary">
                          <h4 class="card-title">Add Post</h4>
                          <p class="card-category">We can add different post here</p>
                      </div>
                      <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                            <!-- <div class="form-group"> -->
                                <label for="title">Title:</label>
                                <input type="text" class="form-control" placeholder="Enter title" id="title">
                                <label>Post</label>
                                   <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
                                      <label for="Post Excerpt">Post Excerpt</label>
                                    <textarea class="form-control" rows="4"></textarea>
                                       <label for="Post Excerpt">Meta Description</label>
                                    <textarea class="form-control" rows="4"></textarea>
                             </div>
                             <div class="col-md-4">
                                  <button class="btn btn-sm btn-success ">Save and publish</button>
                                    <button class="btn btn-sm btn-dark ">Save draft</button>
                                   
                            <hr>
                                <vue-avatar :width="200" :height="200" :rotation="rotation_out" :borderRadius="0"
                                        :scale="scale_out" ref="vueavatar" :image="image" border:1
                                        @vue-avatar-editor:image-ready="onImageReady">
                                    </vue-avatar>
                                            <div class="text-center">
                                    <label>
                                        Zoom : {{scale}}x
                                        <br>
                                        <input type="range" min=1 max=3 step=0.02 v-model='scale' />
                                    </label>
                                    <label>
                                        Rotation : {{rotation}}°
                                        <br>
                                        <input type="range" min=0 max=360 step=1 v-model='rotation' />
                                    </label>
                                </div>
                                
                                     <label for="category">Category</label>
                                    <select class="form-control" >
                                    <option>Select the category</option>
                                <Option v-for="(c, i) in category.data" :value="c.id" :key="i">{{ c.categoryName }}</Option>
                                    </select>
                                     <label for="tag">tag</label>
                                    <select class="form-control" >
                                    <option>Select the tag</option>
                                <Option v-for="(t, i) in tag.data" :value="t.id" :key="i">{{ t.tagName }}</Option>
                                    </select>
                             </div>
                        </div>
                     </div>
                 </div>
            </div>
        </div>
    </div>
 
</template>
<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import {VueAvatar} from 'vue-avatar-editor-improved'

    export default {
         components: {
    VueAvatar
  },
        data() {
            return {
                editor: ClassicEditor,
                editorData: '<p>Content of the editor.</p>',
                editorConfig: {
                  
                },
                  image: "/images/icon/plus.jpg",
                rotation: 0,
                scale: 1,
                rotation_out: 0,
                scale_out: 1,
                borderRadius: 0,
                imageLoaded: true,
                dragged: true,
                category:[],
                tag:[]
            };
        },
        methods:{
             onImageReady: function onImageReady() {
                this.scale = 1;
                this.rotation = 0;
            },
               async loadCategory(){
             try{
             const cat = await axios.get('/app/get_category')
              if (cat.status == 200) {
                this.category = cat.data;
              }
          }catch(e){
             console.log(e)
         } 
     },
     async loadTag(){
             try{
             const tag = await axios.get('/app/get_tag')
              if (tag.status == 200) {
                this.tag = tag.data;
              }
          }catch(e){
             console.log(e)
         } 
     }
 },
  watch: {
            scale() {
                this.scale_out = Number(this.scale);
            },
            rotation() {
                this.rotation_out = Number(this.rotation);
            }
        },
        created(){
             this.loadCategory();
             this.loadTag();
          
    }
    }
</script>
<style  scoped>
 
</style>

