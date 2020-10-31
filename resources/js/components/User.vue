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
                  <h4 class="card-title ">UserTable</h4>
                  <p class="card-category">Information about user</p>
               
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
                                    Email
                                </th>
                                <th>
                                    Type
                                </th>
                                <th>
                                    Registered At
                                </th>
                                <th>
                                    Action
                                </th>
                            </thead>
                            <tbody>
                                 <tr v-for="(user,i) in users.data" :key="i++">
                                    <td>
                                       {{i}}
                                    </td>
                                    <td>
                                      {{user.fullName}}
                                    </td>
                                     <td>
                                      {{user.email}}
                                    </td>
                                     <td>
                                      {{user.userType}}
                                    </td>
                                   
                                     <td>{{user.created_at | myDate}}</td>

                                    <td>
                                        <button
                                            @click="editModal(user)"
                                            rel="tooltip"
                                            title=""
                                            class="btn btn-primary btn-link btn-sm"
                                            data-original-title="Edit Task"
                                       v-if="isUpdatePermitted" >
                                            <i class="material-icons">edit</i>
                                            <div class="ripple-container"></div>
                                        </button>
                                        <button
                                           @click="deleteUser(user.id)"
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
            <pagination :data="users"  @pagination-change-page="getResults">
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
                        <h5 v-show='!editMode' class="modal-title" id="addNewtitle">Add User</h5>
                       <h5 v-show='editMode' class="modal-title" id="addNewtitle">Update User</h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                   <form @submit.prevent="editMode ? updateUser() : createUser()">
                <div class="modal-body">
                     <div class="form-group">
                        <input v-model="form.fullName" type="text" name="fullName"
                            placeholder="Name"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('fullName') }">
                        <has-error :form="form" field="fullName"></has-error>
                    </div>

                     <div class="form-group">
                        <input v-model="form.email" type="email" name="email"
                            placeholder="Email Address"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                        <has-error :form="form" field="email"></has-error>
                    </div>

                     <div class="form-group">
                        <textarea v-model="form.bio" name="bio" id="bio"
                        placeholder="Short bio for user (Optional)"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                        <has-error :form="form" field="bio"></has-error>
                    </div>


                    <div class="form-group">
                        <select name="role_id" v-model="form.role_id" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('role_id') }">
                            <Option :value="role.id"  v-for="(role,i) in roles" :key="i">{{role.roleName}}</Option>
                        </select>
                        <has-error :form="form" field="role_id"></has-error>
                    </div>

                    <div class="form-group">
                        <input v-model="form.password" type="password" name="password" id="password" placeholder="password"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                        <has-error :form="form" field="password"></has-error>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button v-show="editMode" type="submit" class="btn btn-success">Update</button>
                    <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
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
          users : {},
                form: new Form({
                    id:'',
                    fullName : '',
                    email: '',
                    password: '',
                    role_id: null,
                    bio: '',
                    photo: '',
                    isActivated:'',
                    passwordResetCode:'',
                    activationCode:'',
                    socialType:''
                }),
                roles:[]
  }
 },
 methods :{
     getResults(page = 1) {
			axios.get('/app/get_user?page=' + page)
				.then(response => {
					this.users = response.data;
				});
		},
     updateUser(){
          this.$Progress.start();
             this.form.put('/app/update_user/'+this.form.id)
            .then(() => {
             $('#addNew').modal('hide')
              toast.fire({
                       icon: 'success',
                        title: 'User Upadated in successfully'
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
     deleteUser(id){
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
                                this.form.delete('app/delete_user/'+id).then(()=>{
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
      createUser(){
                this.$Progress.start();
                this.form.post('/app/create_user')
                .then(()=>{
                  Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                    toast.fire({
                       icon: 'success',
                        title: 'User Created in successfully'
                        })
                    this.$Progress.finish();
                })
                .catch((e)=>{
                    console.log(e)
                })
            },
           async loadUser(){
             try{
                 await axios.get('/app/get_user')
               .then(({ data }) => (this.users = data));
                 }catch(e){
                console.log(e)
               }
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
       axios.get('/app/finduser/?q=' +query)
      .then((data) => {
          this.users=data.data
      })
      .catch(() => {
                })
    });
//    async const [res,roles] = await Promise.all([
        this.loadUser(),
        this.loadRole()
    // ])
    Fire.$on('AfterCreate',() => {
              this.loadUser();
              this.loadRole();
           });
    }
}
</script>
