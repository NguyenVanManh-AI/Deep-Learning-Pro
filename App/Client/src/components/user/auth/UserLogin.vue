<template>
	<div id="main">
		<div id="container-inside">
			<div id="circle-small"></div>
			<div id="circle-medium"></div>
			<div id="circle-large"></div>
			<div id="circle-xlarge"></div>
			<div id="circle-xxlarge"></div>
			<br><br>
			<div id="big">
				<div class="container">
					<form @submit.prevent="login()">
						<h4>AI System</h4><br>
						<div class="input-form">
							<input v-model="loginUser.email" required>
							<div class="underline"></div>
							<label><i class="fa-solid fa-envelope"></i> Email</label>
						</div>
						<span v-if="errors.email" class="text-danger">{{ errors.email[0] }}</span>
						<br>
						<br>
						<div class="input-form">
							<input required id="inputPassword" :type="isShow ? 'text' : 'password'"
								v-model="loginUser.password">
							<strong id="iconEye"><i @click="isShow = !isShow"
									:class="{ 'fa-solid': true, 'fa-eye': !isShow, 'fa-eye-slash': isShow }"></i></strong>
							<div class="underline"></div><label><i class="fa-solid fa-lock"></i> Password</label>
						</div>
						<span v-if="errors.password" class="text-danger">{{ errors.password[0] }}</span>
						<br>
						<a class="under" style="text-decoration: none;color: var(--user-color);" href="#"
							data-toggle="modal" data-target="#exampleModalForgotPassword">Forgot your password ? </a><br>

						<button type="submit" class="mt-4 btn-pers" id="login_button"><i
								class="fa-solid fa-arrow-right-to-bracket"></i> Login</button>
					</form>
				</div>
				<div class="modal fade" id="exampleModalForgotPassword" tabindex="-1" role="dialog"
					aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document" style="margin: 1.75rem auto; ">
						<div class="modal-content">
							<div class="modal-header" style="padding: 10px 20px; ">
								<h5 class="modal-title" id="exampleModalLabel"><i class="fa-brands fa-keycdn"></i>
									Forgot your password !</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
										aria-hidden="true"><i class="fa-regular fa-circle-xmark"></i></span></button>
							</div>
							<form @submit.prevent="userResetPassword">
								<div class="modal-body" style="padding: 20px; ">
									<div class="form-group">
										<label for="recipient-name" class="col-form-label"><i
												class="fa-solid fa-envelope"></i> Email</label>
										<input required v-model="resetPassword.email" type="email" class="form-control" id="input-font-size" style=" margin: 15px 0; padding: 0 10px;">
									</div>
								</div>
								<div class="modal-footer" style=" padding: 15px 20px; gap: 10px;">
									<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" style=" padding: 6px 15px; "
										id="closePW">Close</button>
									<button type="submit" class="btn btn-outline-primary" style=" padding: 6px 15px; ">Submit</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</template>
  
<script>
import UserRequest from '@/restful/UserRequest'
import useEventBus from '@/composables/useEventBus'
const { emitEvent } = useEventBus();

export default {
	name: "UserLogin",
	data() {
		return {
			user: {
				id: null,
				email: null,
				role: null,
				line_user_id: null,
				channel_id: null,
				name: null,
				phone: null,
				avatar: null,
				address: null,
				gender: null,
				date_of_birth: null,
				is_block: null,
				is_delete: null,
				email_verified_at: null,
				created_at: null,
				updated_at: null,
				expires_in: null,
				token_type: null,
				access_token: null,
			},
			isShow: false,
			loginUser: {
				email: 'nguyenvanmanh2001it1@gmail.com',
				password: '123456'
			},
			resetPassword: {
				email: ''
			},
			errors: {
				email: null,
				password: null
			}
		}
	},
	setup() {
		document.title = "AI System | AI System";
	},
	mounted() {
		var appMain = window.document.getElementById('appMain');
		appMain.style.paddingLeft = '0px'
	},
	methods: {
		login: function () {
			UserRequest.post('user/login', this.loginUser, true)
				.then(data => {
					this.user = data.data;
					window.localStorage.setItem('user', JSON.stringify(this.user));
					data.messages.forEach(message => {
						emitEvent('eventSuccess', message);
					});
					this.$router.push({ name: 'AccountSetting' }); 
				})
				.catch(error => {
					if (error.errors) this.errors = error.errors;
					else for (let key in this.errors) this.errors[key] = null;

					if (error.messages) {
						error.messages.forEach(message => {
							emitEvent('eventError', message);
						});
					}
				})
		},
		userResetPassword: async function () {
			try {
				const { messages } = await UserRequest.post('user/forgot-pw-sendcode', this.resetPassword, true)
				this.resetPassword.email = '';
				emitEvent('eventSuccess', messages[0]);
			}
			catch (error) {
				if (error.messages) error.messages.forEach(message => { emitEvent('eventError', message); });
			}
			var closePW = window.document.getElementById('closePW');
			closePW.click();
		}
	},
}
</script>
<style scoped>
h4 {
	letter-spacing: 2.5px;
	font-weight: 700;
	color: var(--user-color);
	text-align: center;
	font-size: 20px;
}

.btn-outline-primary,
.btn-outline-primary:active,
.btn-outline-primary:visited {
	border-color: var(--user-color);
	color: var(--user-color);
	outline-color: var(--user-color);
}

.btn {
	transition: all 0.6s ease;
}

.btn:focus,
.btn:active {
	outline: none !important;
	box-shadow: none !important;
}

.btn-outline-primary:hover {
	background-color: #096a31 !important;
	border-color: var(--user-color);
}

#main {
	background: var(--user-color);
	background: -moz-linear-gradient(-45deg, var(--user-color) 0%, #0076e5 100%);
	background: -webkit-linear-gradient(-45deg, var(--user-color) 0%, #0076e5 100%);
	background: linear-gradient(135deg, var(--user-color) 0%, #0076e5 100%);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#00adef', endColorstr='#0076e5', GradientType=1);
	position: relative;
	width: 100%;
	margin: 0px auto;
	padding: 0px auto;
	overflow: hidden;
}

#container-inside {
	position: relative;
	min-width: 375px;
	max-width: 1280px;
	height: auto;
	min-height: 100%;
	margin: 0px auto;
	padding: 0px auto;
	overflow: visible;
}

#circle-small {
	-webkit-animation: circle-small-scale 3s ease-in-out infinite alternate;
	animation: circle-small-scale 3s ease-in-out infinite alternate;
	animation-timing-function: cubic-bezier(.6, 0, .4, 1);
	animation-delay: 0s;
	position: absolute;
	top: 200px;
	left: -150px;
	background: #fff;
	width: 300px;
	height: 300px;
	border-radius: 50%;
	opacity: 0.4;
}

#circle-medium {
	-webkit-animation: circle-small-scale 3s ease-in-out infinite alternate;
	animation: circle-small-scale 3s ease-in-out infinite alternate;
	animation-timing-function: cubic-bezier(.6, 0, .4, 1);
	animation-delay: 0.3s;
	position: absolute;
	top: 50px;
	left: -300px;
	background: #fff;
	width: 600px;
	height: 600px;
	border-radius: 50%;
	opacity: 0.3;
}

#circle-large {
	-webkit-animation: circle-small-scale 3s ease-in-out infinite alternate;
	animation: circle-small-scale 3s ease-in-out infinite alternate;
	animation-timing-function: cubic-bezier(.6, 0, .4, 1);
	animation-delay: 0.6s;
	position: absolute;
	top: -100px;
	left: -450px;
	background: #fff;
	width: 900px;
	height: 900px;
	border-radius: 50%;
	opacity: 0.2;
}

#circle-xlarge {
	-webkit-animation: circle-small-scale 3s ease-in-out infinite alternate;
	animation: circle-small-scale 3s ease-in-out infinite alternate;
	animation-timing-function: cubic-bezier(.6, 0, .4, 1);
	animation-delay: 0.9s;
	position: absolute;
	top: -250px;
	left: -600px;
	background: #fff;
	width: 1200px;
	height: 1200px;
	border-radius: 50%;
	opacity: 0.1;
}

#circle-xxlarge {
	-webkit-animation: circle-small-scale 3s ease-in-out infinite alternate;
	animation: circle-small-scale 3s ease-in-out infinite alternate;
	animation-timing-function: cubic-bezier(.6, 0, .4, 1);
	animation-delay: 1.2s;
	position: absolute;
	top: -400px;
	left: -750px;
	background: #fff;
	width: 1500px;
	height: 1500px;
	border-radius: 50%;
	opacity: 0.05;
}

@-webkit-keyframes circle-small-scale {
	0% {
		-webkit-transform: scale(1.0);
	}

	100% {
		-webkit-transform: scale(1.1);
	}
}

@keyframes circle-small-scale {
	0% {
		transform: scale(1.0);
	}

	100% {
		transform: scale(1.1);
	}
}

* {
	margin: 0;
	padding: 0;
	outline: none;
	box-sizing: border-box;
	font-family: sans-serif;
}

#main {
	background-color: #F2F4F6;
	padding-top: 10px;
	padding-left: 30px;
	padding-right: 30px;
	height: 100vh;
}

body {
	display: flex;
	align-items: center;
	justify-content: center;
	min-height: 100vh;
	background: linear-gradient(to right, #EF629F, #EECDA3);
}

.container {
	border-radius: 10px;
	width: 450px;
	background: #fff;
	padding: 30px;
	box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

.container .input-form {
	height: 40px;
	width: 100%;
	position: relative;
}

.container .input-form input {
	height: 100%;
	width: 100%;
	border: none;
	font-size: 17px;
	border-bottom: 2px solid silver;
}

.input-form input:focus~label,
.input-form input:valid~label {
	transform: translateY(-20px);
	font-size: 15px;
	color: var(--user-color);
}

.input-form .underline.fix2:before {
	position: absolute;
	content: "";
	height: 100%;
	width: 100%;
	background: var(--user-color);
	transform: scaleX(0);
	transform-origin: center;
	transition: transform 0.3s ease;
}

.container .input-form label {
	position: absolute;
	bottom: 10px;
	left: 0;
	color: grey;
	pointer-events: none;
	transition: all 0.3s ease;
}

.input-form .underline {
	position: absolute;
	height: 2px;
	width: 100%;
	bottom: 0;
}

.input-form .underline:before {
	position: absolute;
	content: "";
	height: 100%;
	width: 100%;
	background: var(--user-color);
	transform: scaleX(0);
	transform-origin: center;
	transition: transform 0.3s ease;
}

.input-form input:focus~.underline:before,
.input-form input:valid~.underline:before {
	transform: scaleX(1);
}

@import url('https://fonts.googleapis.com/css2?family=Reem+Kufi+Ink');

#big {
	justify-content: center;
	display: flex;
	position: relative;
	margin-top: 100px;
}

.btn-pers {
	position: relative;
	left: 50%;
	padding: 1em 2.5em;
	font-size: 12px;
	text-transform: uppercase;
	letter-spacing: 2.5px;
	font-weight: 700;
	color: #000;
	background-color: #fff;
	border: none;
	border-radius: 45px;
	box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
	transition: all 0.3s ease 0s;
	cursor: pointer;
	outline: none;
	transform: translateX(-50%);
}

.btn-pers:hover {
	background-color: var(--user-color);
	box-shadow: 0px 15px 20px #80ffb5;
	color: #fff;
	transform: translate(-50%, -7px);
}

.btn-pers:active {
	transform: translate(-50%, -1px);
}

.under {
	position: relative;
	padding: 0px 0px;
}

.under::after {
	content: ' ';
	position: absolute;
	left: 0;
	bottom: -4px;
	width: 0;
	height: 2px;
	background: var(--user-color);
	transition: width 0.3s;
}

.under:hover::after {
	width: 100%;
	transition: width 0.3s;
}

#iconEye {
	position: absolute;
	top: 10px;
	right: 0px;
	padding-left: 5px;
	cursor: pointer;
}

#inputPassword {
	padding-right: 26px;
}

#input-font-size{
	font-size: 16px !important;
}
</style>
  
  