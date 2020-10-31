<template>
 <div class="row">
            <div class="col-md-12">
              <div class="card card-plain">
                <div class="card-header card-header-primary">
                  <h4 class="card-title mt-0"> Users Table</h4>
                  <!-- <p class="card-category"> Here is a subtitle for this table</p> -->
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
                          Action
                        </th>
                      </thead>
                      <tbody>
                        <tr  v-for="user in users" :key="user.id">
                          <td>
                            {{user.id}}
                          </td>
                          <td>
                          {{user.name}}
                          </td>
                          <td>
                            Niger
                          </td>
                          <td>
                            Oud-Turnhout
                          </td>
                         <td>
                              <button type="button" rel="tooltip" title="" class="btn btn-primary btn-link btn-sm" data-original-title="Edit Task">
                                <i class="material-icons">edit</i>
                              <div class="ripple-container"></div></button>
                              <button type="button" rel="tooltip" title="" class="btn btn-danger btn-link btn-sm" data-original-title="Remove" aria-describedby="tooltip236571">
                                <i class="material-icons">close</i>
                              </button>
                            </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
</template>

<script>
  export default {

        data() {
            return {
              users: {},
            }
        },
        methods: {
            getUser(){
                axios.get('/profile')
                     .then((response)=>{
                       this.users = response.data.users
                     })
            }
        },
        created() {
            this.getUser()
        }
    }
</script>
