<template>
  <div>
    <template :class="{ loading: true, hide: !isLoading, show: isLoading }">
      <breeding-rhombus-spinner class="loading-component" :animation-duration="2000" :size="65" color="#06C755" />
      <div id="titleLoading">
        <i class="fa-brands fa-line"></i> AI Advanced
      </div>
    </template>
    <template id="appMain" :class="{ hide: isLoading, show: !isLoading }">
      <router-view></router-view>
      <CommonNotification></CommonNotification>
    </template>
  </div>
</template>
<script>

import CommonNotification from '@/components/common/CommonNotification'
import { BreedingRhombusSpinner } from 'epic-spinners';
import useEventBus from '@/composables/useEventBus'
const { onEvent } = useEventBus()

export default {
  name: 'App',
  components: {
    CommonNotification,
    BreedingRhombusSpinner
  },
  setup() {
    document.title = "AI Advanced";
  },
  data() {
    return {
      isLoading: false,
    }
  },
  mounted() {
    onEvent('eventLoading', (isLoading) => {
      this.isLoading = isLoading;
    })
  },
  watch: {

  }
}
</script>
<style >
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html {
  scroll-behavior: smooth;
}

li {
  list-style: none;
}

a {
  text-decoration: none;
}

body {
  background-color: #F0F2F5 !important;
}

:root {
  --user-color: #06C755;
  --admin-color: #06C755;
  --blue-color: #007BFF;
  --brown-color: #8B4513;
  --yellow-color: #c0b01d;
}

::-webkit-scrollbar {
  width: 13px;
}

::-webkit-scrollbar-track {
  background: white;
}

::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 10px;
  border: 3px solid transparent;
  background-clip: content-box;
}

::-webkit-scrollbar-thumb:hover {
  background: var(--user-color);
  border-radius: 10px;
  border: 3px solid transparent;
  background-clip: content-box;
}

#view {
  display: grid;
  width: 100%;
  overflow-y: scroll;
}

#view::-webkit-scrollbar {
  display: none;
}

#view {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

.modal-open .modal {
  background-color: #0000008a;
}

.btn {
  outline: none;
}

button {
  outline: none !important;
}

.loading-component {
  margin: auto;
  margin-top: 300px;
}

#titleLoading {
  margin-top: 25px;
  text-align: center;
  font-weight: bold;
  color: var(--user-color);
}

#titleLoading {
  animation: titleLoading 2s linear infinite;
}

@keyframes titleLoading {
  50% {
    opacity: 0;
  }
}

.hide {
  display: none;
}

.show {
  display: block;
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

button.close span {
  color: black !important;
  transition: color 0.5s ease !important;
}

button.close:hover span {
  color: red !important;
  transition: color 0.5s ease !important;
}

.color-header {
  color: var(--user-color);
}
</style>
