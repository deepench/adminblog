<template>
        <!-- <div class="container h-100"> -->
		<div class="d-flex justify-content-center h-100">
			<div class="user_card">
				<div class="d-flex justify-content-center">
					<div class="brand_logo_container">
						<img src="https://png.pngtree.com/png-clipart/20190516/original/pngtree-users-vector-icon-png-image_3725294.jpg" class="brand_logo" alt="Logo">
					</div>
				</div>
				<div class="d-flex justify-content-center form_container">
					<form @submit.prevent="login" @keydown="form.onKeydown($event)">
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="material-icons">people</i></span>
							</div>
						 <input v-model="form.email" type="text" name="email"
								class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
							<has-error :form="form" field="email"></has-error>
						</div>
						<div class="input-group mb-2">
							<div class="input-group-append">
								<span class="input-group-text"><i class="material-icons">lock</i></span>
									</div>
								<input v-model="form.password" type="password" name="password"
									class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
								<has-error :form="form" field="password"></has-error>
								</div>
						<div class="form-group">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="customControlInline">
								<label class="custom-control-label" for="customControlInline">Remember me</label>
							</div>
						</div>
							<div class="d-flex justify-content-center mt-3 login_container">
				 	<button type="submit" :disabled="form.busy" name="button" class="btn login_btn" >Login</button>
				   </div>
					</form>
				</div>
			</div>
		</div>

</template>
<script>
export default {
	data(){
		return{
			 form: new Form({
			email: '',
			password: '',
     
      }),
			isLoggedIn:false
		}
	},
	methods:{
		login () {
			this.isLoggedIn=true
      	this.form.post('/app/login')
		.then(({ data }) => {  
			 window.location = '/'
			 })
		.catch((e) => {
                console.log(e);
		});
				
			}
	}
}
</script>
<style  scoped>
		.user_card {
			height: 400px;
			width: 350px;
			margin-top: auto;
			margin-bottom: auto;			
			background: #e1e7f0;
			position: relative;
			display: flex;
			justify-content: center;
			flex-direction: column;
			padding: 10px;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			-webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			-moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			border-radius: 5px;

		}
		.brand_logo_container {
			position: absolute;
			height: 170px;
			width: 170px;
			top: -75px;
			border-radius: 50%;
			background: #60a3bc;
			padding: 10px;
			text-align: center;
		}
		.brand_logo {
			height: 150px;
			width: 150px;
			border-radius: 50%;
			border: 2px solid white;
		}
		.form_container {
			margin-top: 100px;
		}
		.login_btn {
			width: 100%;
			background: #c0392b !important;
			color: white !important;
		}
		.login_btn:focus {
			box-shadow: none !important;
			outline: 0px !important;
		}
		.login_container {
			padding: 0 2rem;
		}
		.input-group-text {
			background: #c0392b !important;
			color: white !important;
			border: 0 !important;
			border-radius: 0.25rem 0 0 0.25rem !important;
		}
		.input_user,
		.input_pass:focus {
			box-shadow: none !important;
			outline: 0px !important;
		}
		.custom-checkbox .custom-control-input:checked~.custom-control-label::before {
			background-color: #c0392b !important;
		}
</style>