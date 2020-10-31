<template>
   <div class="row">
       <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary">
                       <div class="card-tools">
                         <button
                            class="btn btn-success pull-right m-0"
                            @click="newModal()"
                        v-if="isWritePermitted">
                            <span class="material-icons">
                                add </span
                            >Add New
                        </button>
                  </div>
                  <h4 class="card-title ">Category Table</h4>
                  <p class="card-category">Information about category</p>
               
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-primary">
                        <tr>
                          <th>
                          ID
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Image
                        </th>
                        <th>
                            Action
                        </th>
                      </tr></thead>
                      <tbody>
                       <tr v-for="category in categories.data" :key="category.id">
                           <td>{{category.id}}</td>
                           <td>{{category.categoryName}}</td>
                           <td><img :src="'/img/category/'+ category.iconImage"  width= '100px'
                   height= '100px'></td>
                           <td> 
                                <button
                                            @click="editModal(category)"
                                            rel="tooltip"
                                            title=""
                                            class="btn btn-primary btn-link btn-sm"
                                            data-original-title="Edit Task"
                                       v-if="isUpdatePermitted" >
                                            <i class="material-icons">edit</i>
                                            <div class="ripple-container"></div>
                                        </button>
                               
                                <button
                                           @click="deleteCategory(category.id)"
                                            rel="tooltip"
                                            title=""
                                            class="btn btn-danger btn-link btn-sm"
                                            data-original-title="Remove"
                                            aria-describedby="tooltip236571"
                                       v-if="isDeletePermitted" >
                                            <i class="material-icons">close</i>
                                        </button></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

              </div>
               <div class="modal fade" id="addNew">
                <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                
                    <!-- Modal Header -->
                    <div class="modal-header">
                    <h4  v-show='!editMode' class="modal-title">Add Category</h4>
                     <h4 v-show='editMode' class="modal-title">Edit Category</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    
                    <!-- Modal body -->
                    <form @submit.prevent="editMode? updateCategory():createCategory()">
                          <div class="modal-body">
                                 <div class="form-group">
                                       <input v-model="form.categoryName" type="text" name="categoryName"
                                        class="form-control" placeholder="Enter the Category Name" :class="{ 'is-invalid': form.errors.has('categoryName') }" >
                                        <has-error :form="form" field="categoryName"></has-error>
                                 </div>
                                  <div class="upload-btn-wrapper">
                                        <button class="btns">Choose image</button>
                                        <input  type="file"  @change="upload_image" name="iconImage" :class="{ 'is-invalid': form.errors.has('iconImage') }" />
                                        <has-error :form="form" field="iconImage"></has-error>
                                         <div  class="iconImage img-fluid img-circle" style="margin-top:10px">
                                             <img :src="get_image()" v-bind:style="form.styleObject" width="100px" height="100px"/>
                                        </div>
                                </div>
                          </div>
                              <!-- Modal footer -->
                            <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button v-show='!editMode'   type="submit" class="btn btn-success">Create</button>
                                     <button v-show='editMode'   type="submit" class="btn btn-success">Update</button>
                            </div>
                            
                    </form>
                
                </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            editMode:false,
            categories:{},
            form: new Form({
                id:'',
                categoryName: '',
                iconImage: '',
                 styleObject: {
                   width: '100px',
                   height: '100px'
                }
                   
             })

        }
    },
    methods:{
        updateCategory(){
                    this.form.put('/app/update_category/'+this.form.id)
                      .then(() => {
                                $('#addNew').modal('hide')
                                  toast.fire({
                                          icon: 'success',
                                            title: 'Category Upadated in successfully'
                                            })
                                            this.$Progress.finish();
                                                Fire.$emit('AfterCreate');
                          })
                          .catch(() =>{
                                this.$Progress.fail();
                          })
        },
        
         editModal(category){
             this.editMode=true;
              this.form.reset();
             $('#addNew').modal('show')
             this.form.fill(category);
     },
        newModal(){
                this.editMode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
             deleteCategory(id){
                swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    //server sends the request
               if (result.value) {
                                this.form.delete('app/delete_category/'+id).then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal("Failed!", "There was something wronge.", "warning");
                                });
                         }
                })
     },
            createCategory(){
                // console.log('sadsafd');
                  this.$Progress.start();
                    this.form.post('/app/create_category')
                        .then((data) =>{
                          console.log(data);
                             Fire.$emit('AfterCreate');
                                $('#addNew').modal('hide')
                                toast.fire({
                                icon: 'success',
                                    title: 'Category Created in successfully'
                                    })
                             this.$Progress.finish();
                      })
                      .catch((e) => {
                        console.log(e);
                      });
            },
            upload_image(e){
                console.log('uploading')
                let file=e.target.files[0];
                    let reader = new FileReader();  
                     if(file['size'] < 2111775)
                {
                        reader.onloadend = (file) => {
                    //console.log('RESULT', reader.result)
                     this.form.iconImage = reader.result;
                    }              
                     reader.readAsDataURL(file);
                      }else{
                    alert('File size can not be bigger than 2 MB')
                }
            },
             //For getting Instant Uploaded Photo
            get_image(){
               let iconImage = (this.form.iconImage.length > 100) ? this.form.iconImage : "img/category/"+ this.form.iconImage;
               return iconImage;
            },
            loadCategories(){
                axios.get('/app/get_category')
                 .then(({ data }) => (this.categories = data))
                 .catch((e) =>{

                 });
            }
    },
    created(){
     this.loadCategories();
      Fire.$on('AfterCreate',() => {
              this.loadCategories();
           });
    //  console.log(this.loadCategories);
    }
   
}
</script>
<style  scoped>
.upload-btn-wrapper {
  position: relative;
  overflow: hidden;
  display: inline-block;
}

.btns {
  border: 2px solid gray;
  color: gray;
  background-color: white;
  padding: 8px 20px;
  border-radius: 8px;
  font-size: 11px;
  font-weight: bold;
}

.upload-btn-wrapper input[type=file] {
  font-size: 100px;
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
}
</style>
