<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title ">Assign Role <span><select name="role_id" @change="onChange()" v-model="data.id" id="type" class="form-control pull-right" style="width:200px">
                            <Option :value="role.id" v-for="(role,i) in roles" :key="i" >{{role.roleName}}</Option>
                        </select></span></h4>
                  <p class="card-category">we Can Assign role for Different User</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="">
                                <th>
                                    Resource Name
                                </th>
                                <th>
                                   Read
                                </th>
                                 <th>
                                    Write
                                </th>
                                <th>
                                    Update
                                </th>
                                <th>
                                  Delete
                                </th>
                                
                            </thead>
                            <tbody>
                                 <tr v-for="(r, i) in resources" :key="i">
                                    <td>{{r.resourceName}}</td>
                                    <td>
                                   <input type="checkbox" v-model="r.read">
                                    </td>
                                    <td>
                                       <input type="checkbox" v-model="r.write">
                                    </td>
                                    <td>
                                    <input type="checkbox" v-model="r.update">
                                    </td>
                                    <td>
                                   <input type="checkbox" v-model="r.delete">
                                    </td>
                                </tr>
                               <div>
                           <button type="button" class="btn btn-primary"  @click="assignRoles">Assign</button>
                               </div>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
    </div>
</template>
<script>
export default{
   data(){
    return{
        data:{
            roleName:'',
            id:null,
                  
        },
         roles:[],
       
        resources:[
              {resourceName: 'Dashboard', read:false, write:false, update:false, delete:false, name:'/'},
               {resourceName: 'Tags', read:false, write:false, update:false, delete:false, name:'tags'},
                {resourceName: 'Category', read:false, write:false, update:false, delete:false, name:'category'},
                 {resourceName: 'User', read:false, write:false, update:false, delete:false, name:'user'},
                  {resourceName: 'Role', read:false, write:false, update:false, delete:false, name:'role'},
                   {resourceName: 'Assignrole', read:false, write:false, update:false, delete:false, name:'assignrole'},
                  
        ],
         defaultResourcesPermission: [
              {resourceName: 'Dashboard', read:false, write:false, update:false,delete:false, name:'/'},
                    {resourceName: 'Tags', read:false, write:false, update:false, delete:false, name:'tags'},
                     {resourceName: 'Category', read:false, write:false, update:false, delete:false, name:'category'},
                        {resourceName: 'User', read:false, write:false, update:false, delete:false, name:'user'},
                          {resourceName: 'Role', read:false, write:false, update:false, delete:false, name:'role'},
                         {resourceName: 'Assignrole', read:false, write:false, update:false, delete:false, name:'assignrole'},
                    
      ],
        
  }
 },
 methods :{
         onChange(event) {
              let index = this.roles.findIndex(role => role.id == this.data.id)
           let permission = this.roles[index].permission
                 if(!permission){
                     this.resources = this.defaultResourcesPermission
              }else{
                 this.resources = JSON.parse(permission)
       }
        },
        async assignRoles(){
              let data = JSON.stringify(this.resources);
                  const res = await axios.post('/app/assign_role',{'permission':data,id:this.data.id})
                      toast.fire({
                       icon: 'success',
                        title: 'Role has been Upadated in successfully'
                        })
                 if(res.status==200){
                        toast.fire({
                       icon: 'success',
                        title: 'Role has been Upadated in successfully'
                        });
                        let index = this.roles.findIndex(role => role.id == this.data.id)
                        this.roles[index].permission = data
         }else{
       console.log('error')
         }
        },
            async loadRole(){
             try{
             const res = await axios.get('/app/get_role')
              if (res.status == 200) {
                this.roles = res.data;
                if(res.data.length){
                    console.log(this.data.id);
                    this.data.id=res.data[0].id
                       if(res.data[0].permission){
                       this.resources = JSON.parse(res.data[0].permission)
           
         }
                }
   }
          }catch(e){
             console.log(e)
         }         
        }
            
           
},
created(){
// const res =  axios.get('/app/get_role')
//     if (res.status == 200) {
//       this.roles = res.data;
this.loadRole();
  
//    }
}
}
</script>
