const axios = require('axios');
import config from '@/config.js'
import router from '@/router/routes'
import useEventBus from '@/composables/useEventBus.js'
const { emitEvent } = useEventBus();
import NProgress from 'nprogress';

export default {
	_getHeaders() {
		var dataAdmin = window.localStorage.getItem('admin');
		var admin = null;
		if (dataAdmin) admin = JSON.parse(dataAdmin);

		let headers = {};
		if (admin !== null) {
			headers.Authorization = 'Bearer ' + admin.access_token;
		}
		return headers;
	},
	get(url, isLoad = false) {
		this.startLoading(isLoad);
		return new Promise((resolve, reject) => {
			axios.get(
				config.API_URL + url,
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
				config.API_URL + url,
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
				config.API_URL + url,
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
				config.API_URL + url,
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
				config.API_URL + url,
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
		window.localStorage.removeItem('admin');
		setTimeout(() => {
			router.push({ name: "AdminLogin" });
		}, 1500);
	},

	startLoading(isLoad) {
		NProgress.start()
		if (isLoad) {
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


