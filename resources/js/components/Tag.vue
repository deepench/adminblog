<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h3 class="card-title mt-0">
                        Tag Table
                        <button
                            class="btn btn-success pull-right m-0"
                            @click="newModal()"
                       v-if="isWritePermitted"  >
                            <span class="material-icons">
                                add </span
                            >Add New
                        </button>
                    </h3>
                    <!-- <div class="card-tools"> -->

                    <!-- </div> -->
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="">
                                <th>
                                    ID
                                </th>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Action
                                </th>
                            </thead>
                            <tbody>
                                <tr v-for="tag in tags.data" :key="tag.id">
                                    <td>
                                        {{tag.id}}
                                    </td>
                                    <td>
                                      {{tag.tagName}}
                                    </td>
                                    <td>
                                        <button
                                            @click="editModal(tag)"
                                            rel="tooltip"
                                            title=""
                                            class="btn btn-primary btn-link btn-sm"
                                            data-original-title="Edit Task"
                                       v-if="isUpdatePermitted" >
                                            <i class="material-icons">edit</i>
                                            <div class="ripple-container"></div>
                                        </button>
                                        <button
                                           @click="deleteTag(tag.id)"
                                            rel="tooltip"
                                            title=""
                                            class="btn btn-danger btn-link btn-sm"
                                            data-original-title="Remove"
                                            aria-describedby="tooltip236571"
                                       v-if="isDeletePermitted" >
                                            <i class="material-icons">close</i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
            <pagination :data="tags"  @pagination-change-page="getResults">
                <span slot="prev-nav">&lt; Previous</span>
                <span slot="next-nav">Next &gt;</span>
            </pagination>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div
            class="modal fade"
            id="addNew"
            tabindex="-1"
            role="dialog"
            aria-labelledby="addNewtitle"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show='!editMode' class="modal-title" id="addNewtitle">Add Tag</h5>
                       <h5 v-show='editMode' class="modal-title" id="addNewtitle">Update Tag</h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode? updateTag():createTag()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input
                                    v-model="form.tagName"
                                    type="text"
                                    name="tagName"
                                    placeholder="Enter the tag name"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('tagName')
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="tagName"
                                ></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal"
                            >
                                Close
                            </button>
                            <button v-show='!editMode' type="submit" class="btn btn-primary">
                                Create
                            </button>
                              <button v-show='editMode' type="submit" class="btn btn-success">
                               Update 
                            </button>
                          
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
// import {appmixin } from '../common.js'
import { mapGetters } from 'vuex'
export default{
   data(){
    return{
        editMode:false,
        tags:{},
    form: new Form({
        id:'',
        tagName: '',
      })
  }
 },
 methods :{
     getResults(page = 1) {
			axios.get('/app/get_tag?page=' + page)
				.then(response => {
					this.tags = response.data;
				});
		},
     updateTag(){
          this.$Progress.start();
      this.form.put('/app/update_tag/'+this.form.id)
      .then(() => {
             $('#addNew').modal('hide')
              toast.fire({
                       icon: 'success',
                        title: 'Tag Upadated in successfully'
                        })
                         this.$Progress.finish();
                            Fire.$emit('AfterCreate');

      })
      .catch(() =>{
            this.$Progress.fail();
      })
      
     },
       editModal(user){
             this.editMode=true;
              this.form.reset();
             $('#addNew').modal('show')
             this.form.fill(user);
     },
    newModal(){
                this.editMode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
     deleteTag(id){
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
                                this.form.delete('app/delete_tag/'+id).then(()=>{
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
      createTag(){
                this.$Progress.start();
                this.form.post('/app/create_tag')
                .then(()=>{
                  Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                    toast.fire({
                       icon: 'success',
                        title: 'Tag Created in successfully'
                        })
                    this.$Progress.finish();
                })
                .catch((e)=>{
                    console.log(e)
                })
            },
           async loadTag(){
             try{
                 await axios.get('/app/get_tag')
               .then(({ data }) => (this.tags = data));
                 }catch(e){
                console.log(e)
               }
           }
},
created(){
    // console.log(this.isReadPermitted);
    Fire.$on('searching',() => {
        let query=this.$parent.search;
       axios.get('/app/findtag/?q=' +query)
      .then((data) => {
          this.tags=data.data
      })
      .catch(() => {
                })
    })
     this.loadTag();
    Fire.$on('AfterCreate',() => {
              this.loadTag();
           });
    }
}
</script>
