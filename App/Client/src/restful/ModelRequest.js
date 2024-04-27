const axios = require('axios');
import config from '@/config.js'
import router from '@/router/routes'
import useEventBus from '@/composables/useEventBus.js'
const { emitEvent } = useEventBus();
import NProgress from 'nprogress';

export default {
	_getHeaders() {
		var dataUser = window.localStorage.getItem('user');
		var user = null;
		if (dataUser) user = JSON.parse(dataUser);

		let headers = {};
		if (user !== null) {
			headers.Authorization = 'Bearer ' + user.access_token;
		}
		return headers;
	},
	get(url, isLoad = false) {
		this.startLoading(isLoad);
		return new Promise((resolve, reject) => {
			axios.get(
				config.API_Model + url,
				{
					headers: this._getHeaders()
				}
			)
				.then(response => {
					this.endLoading(isLoad);
					resolve(response.data);
				})
				.catch(error => {
					this.endLoading(isLoad);
					if (error.response.status == 401) this.hadleError401();
					else reject(error.response.data);
				})
				.finally(() => {
					this.endLoading(isLoad);
				});
		});
	},
	post(url, data = {}, isLoad = false) {
		this.startLoading(isLoad);
		return new Promise((resolve, reject) => {
			axios.post(
				config.API_Model + url,
				data,
				{
					headers: this._getHeaders()
				}
			)
				.then(response => {
					this.endLoading(isLoad);
					resolve(response.data);
				})
				.catch(error => {
					this.endLoading(isLoad);
					if (error.response.status == 401) this.hadleError401();
					else reject(error.response.data);
				})
		})
	},
	put(url, data = {}, isLoad = false) {
		this.startLoading(isLoad);
		return new Promise((resolve, reject) => {
			axios.put(
				config.API_Model + url,
				data,
				{
					headers: this._getHeaders()
				}
			)
				.then(response => {
					this.endLoading(isLoad);
					resolve(response.data);
				})
				.catch(error => {
					this.endLoading(isLoad);
					if (error.response.status == 401) this.hadleError401();
					else reject(error.response.data);
				})
		})
	},
	patch(url, data = {}, isLoad = false) {
		this.startLoading(isLoad);
		return new Promise((resolve, reject) => {
			axios.patch(
				config.API_Model + url,
				data,
				{
					headers: this._getHeaders()
				}
			)
				.then(response => {
					this.endLoading(isLoad);
					resolve(response.data);
				})
				.catch(error => {
					this.endLoading(isLoad);
					if (error.response.status == 401) this.hadleError401();
					else reject(error.response.data);
				})
		})
	},
	delete(url, data = {}, isLoad = false) {
		this.startLoading(isLoad);
		return new Promise((resolve, reject) => {
			axios.delete(
				config.API_Model + url,
				data,
				{
					headers: this._getHeaders()
				}
			)
				.then(response => {
					this.endLoading(isLoad);
					resolve(response.data);
				})
				.catch(error => {
					this.endLoading(isLoad);
					if (error.response.status == 401) this.hadleError401();
					else reject(error.response.data);
				})
		})
	},

	hadleError401() {
		const { emitEvent } = useEventBus();
		emitEvent('eventError', 'Error Unauthorized !');
		window.localStorage.removeItem('user');
		setTimeout(() => {
			router.push({ name: "UserLogin" });
		}, 1500);
	},

	startLoading(isLoad) {
		NProgress.start() // NProgress thì luôn load thì call api  
		if (isLoad) { // Loading thì có thể load hoặc không tùy yêu cầu 
			emitEvent('eventLoading', true);
		}
	},

	endLoading(isLoad) {
		NProgress.done()
		if (isLoad) {
			emitEvent('eventLoading', false);
		}
	}
}

/*
Note : 
	get(url,isLoad=false){
	post(url, data = {}, isLoad=false){
	put(url, data = {}, isLoad=false){
	patch(url, data = {}, isLoad=false){
	delete(url, data = {}, isLoad=false){

	UserRequest.post('user/login', this.loginUser) // mặc định isLoad = false => không load 
	UserRequest.get('user/profile') // không load 

	UserRequest.post('user/login', this.loginUser, true) // có load 
	UserRequest.get('user/profile', true) // có load 


*/

