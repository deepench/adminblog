<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h3 class="card-title mt-0">
                        Role Management Table
                        <button
                            class="btn btn-success pull-right m-0"
                            @click="newModal()"
                        v-if="isWritePermitted">
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
                                <th>Created_At</th>
                                <th>
                                    Action
                                </th>
                            </thead>
                            <tbody>
                                <tr v-for="(role,i) in roles" :key="i">
                                    <td>
                                        {{role.id}}
                                    </td>
                                    <td>
                                      {{role.roleName}}
                                    </td>
                                     <td>
                                         {{role.created_at | myDate}}

                                     </td>
                                    <td>
                                        <button
                                            @click="editModal(role)"
                                            rel="tooltip"
                                            title=""
                                            class="btn btn-primary btn-link btn-sm"
                                            data-original-title="Edit Task"
                                       v-if="isUpdatePermitted" >
                                            <i class="material-icons">edit</i>
                                            <div class="ripple-container"></div>
                                        </button>
                                        <button
                                           @click="deleteRole(role.id)"
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
            <!-- <pagination :data="role"  @pagination-change-page="getResults">
                <span slot="prev-nav">&lt; Previous</span>
                <span slot="next-nav">Next &gt;</span>
            </pagination> -->
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
                        <h5 v-show='!editMode' class="modal-title" id="addNewtitle">Add Role</h5>
                       <h5 v-show='editMode' class="modal-title" id="addNewtitle">Update Role</h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode? updateRole():createRole()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input
                                    v-model="form.roleName"
                                    type="text"
                                    name="roleName"
                                    placeholder="Enter the role name"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('roleName')
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="roleName"
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
export default{
   data(){
    return{
        editMode:false,
        roles:{},
    form: new Form({
        id:'',
        roleName: '',
      })
  }
 },
 methods :{
     getResults(page = 1) {
			axios.get('/app/get_role?page=' + page)
				.then(response => {
					this.roles = response.data;
				});
		},
     updateRole(){
          this.$Progress.start();
      this.form.put('/app/update_role/'+this.form.id)
      .then(() => {
             $('#addNew').modal('hide')
              toast.fire({
                       icon: 'success',
                        title: 'Role Upadated in successfully'
                        })
                         this.$Progress.finish();
                            Fire.$emit('AfterCreate');

      })
      .catch(() =>{
            this.$Progress.fail();
      })
      
     },
       editModal(role){
             this.editMode=true;
              this.form.reset();
             $('#addNew').modal('show')
             this.form.fill(role);
     },
    newModal(){
                this.editMode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
     deleteRole(id){
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
                                this.form.delete('app/delete_role/'+id).then(()=>{
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
      createRole(){
                this.$Progress.start();
                this.form.post('/app/create_role')
                .then(()=>{
                  Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                    toast.fire({
                       icon: 'success',
                        title: 'Role Created in successfully'
                        })
                    this.$Progress.finish();
                })
                .catch((e)=>{
                    console.log(e)
                })
            },
           async loadRole(){
             try{
                 await axios.get('/app/get_role')
               .then(({ data }) => (this.roles = data));
                 }catch(e){
                console.log(e)
               }
           }
},
created(){
    Fire.$on('searching',() => {
        let query=this.$parent.search;
       axios.get('/app/findrole/?q=' +query)
      .then((data) => {
          this.roles=data.data
      })
      .catch(() => {
                })
    })
     this.loadRole();
    Fire.$on('AfterCreate',() => {
              this.loadRole();
           });
    }
}
</script>
