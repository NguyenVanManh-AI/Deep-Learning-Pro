<template>
  <div class="sidebar" :class="isUserOpened ? 'open' : ''" :style="cssVars" ref="sidebar">
    <div class="logo-details" style="margin: 10px 14px 0 14px;">
      <img src="@/assets/logo.png" alt="menu-logo" class="menu-logo icon" style="" @click="compadmin">
      <div class="logo_name" @click="compadmin"> AI System </div>
      <i style="color: var(--user-color);" class="bx" :class="isUserOpened ? 'bx-menu-alt-right' : 'bx-menu'" id="btn"
        @click="openSiderbar"></i>
    </div>
    <div
      style="display: flex ; flex-direction:column; justify-content: space-between; flex-grow: 1; max-height: calc(100% - 60px); ">
      <div id="my-scroll" style="margin: 0px 14px 0 14px;">
        <ul class="nav-list" style="overflow: visible;">
          <span>
            <li>
              <router-link class="links" data-path="account-setting" :to="{ name: 'AccountSetting' }"><i
                  class="fa-solid fa-user-gear"></i><span class="links_name">Account
                  setting</span></router-link>
            </li>
          </span>
          <span>
            <li>
              <router-link class="links" data-path="flower-recognition" :to="{ name: 'FlowerRecognition' }"><i class="fa-solid fa-fan"></i><span class="links_name">Flowers Recognition</span></router-link>
            </li>
          </span>
          <span>
            <li>
              <router-link class="links" data-path="flower-multiple-recognition" :to="{ name: 'MultipleFlowerRecognition' }"><i class="fa-brands fa-pagelines"></i><span class="links_name">My Multiple Flower</span></router-link>
            </li>
          </span>
          <span>
            <li>
              <router-link class="links" data-path="alzheimers-recognition" :to="{ name: 'AlzheimersRecognition' }"><i class="fa-solid fa-brain"></i><span class="links_name">Alzheimers Recog</span></router-link>
            </li>
          </span>
          <span>
            <li>
              <router-link class="links" data-path="image-processing-flowers-recognition" :to="{ name: 'IPMultipleFlowerRecognition' }"><i class="fa-solid fa-clover"></i><span class="links_name">Image Processing</span></router-link>
            </li>
          </span>
          <!-- <span :class="{ 'hide': user.role != 'manager' }">
            <li>
              <router-link class="links" data-path="member-account" :to="{ name: 'MemberAccount' }"><i
                  class="fa-solid fa-users"></i><span class="links_name">Channel manages</span></router-link>
            </li>
          </span>
          <span>
            <li>
              <router-link class="links" data-path="manage-content" :to="{ name: 'ManageContent' }"><i
                  class="fa-solid fa-envelope-open-text"></i><span class="links_name">Manage content</span></router-link>
            </li>
          </span>
          <span>
            <li>
              <router-link class="links" data-path="manage-broadcast" :to="{ name: 'ManageBroadcast' }"><i
                  class="fa-brands fa-line"></i><span class="links_name">Manage
                  broadcast</span></router-link>
            </li>
          </span>
          <span :class="{ 'hide': user.role != 'manager' }">
            <li>
              <router-link class="links" data-path="statistical-channel" :to="{ name: 'StatisticalChannel' }"><i
                  class="fa-solid fa-chart-pie"></i><span class="links_name">Statistical channel</span></router-link>
            </li>
          </span> -->
        </ul>
      </div>
      <div class="profile">
        <div class="profile-details">
          <img id="avatar_sidebar" :src="user.avatar ? user.avatar : require('@/assets/avatar.jpg')" alt="Avatar User">
          <div class="name_job">
            <div class="name">{{ user.name }}</div>
            <div class="job"><i class="fa-solid fa-user-tag"></i> {{ user.role }}</div>
          </div>
        </div>
        <i class="bx bx-log-out" id="log_out" @click="logout"></i>
      </div>
    </div>
  </div>
</template>

<script>

import useEventBus from '@/composables/useEventBus'
import $ from 'jquery';
const { onEvent } = useEventBus()

export default {
  name: 'UserSidebar',
  props: {
    menuOpenedPaddingLeftBody: {
      type: String,
      default: '250px'
    },
    menuClosedPaddingLeftBody: {
      type: String,
      default: '78px'
    },
  },
  data() {
    return {
      isUserOpened: true,
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
    }
  },
  methods: {
    openSiderbar: function () {
      this.isUserOpened = !this.isUserOpened;
      localStorage.setItem('isUserOpened', this.isUserOpened);
    },
    logout: function () {
      window.localStorage.removeItem('user');
      this.$router.push({ name: "UserLogin" });
      var appMain = window.document.getElementById('appMain');
      appMain.style.paddingLeft = '0px'
    },
    updateSidebarVisibility() {
      const isSmallScreen = window.innerWidth < 1200;
      if (isSmallScreen) {
        this.isUserOpened = false;
      } else {
        const storedState = localStorage.getItem('isUserOpened');
        if (storedState) {
          this.isUserOpened = JSON.parse(storedState);
        }
      }
      var appMain = window.document.getElementById('appMain');
      appMain.style.paddingLeft = this.isUserOpened ? this.menuOpenedPaddingLeftBody : this.menuClosedPaddingLeftBody;
    },
    handleOutsideClick(event) {
      const isSmallScreen = window.innerWidth < 1200;
      if (isSmallScreen && this.isAdminOpened && this.$refs.sidebar && !this.$refs.sidebar.contains(event.target)) {
        this.isAdminOpened = false;
        localStorage.setItem('isUserOpened', false);
      }
    },
  },
  mounted() {
    const isUserOpened = localStorage.getItem('isUserOpened');
    if (isUserOpened) {
      this.isUserOpened = JSON.parse(isUserOpened);
    }
    var appMain = window.document.getElementById('appMain');
    appMain.style.paddingLeft = this.isUserOpened ? this.menuOpenedPaddingLeftBody : this.menuClosedPaddingLeftBody
    $('.links').click(function () {
      $('.links').removeClass('active');
      $(this).addClass('active');
    });
    var currentLocation = window.location.pathname;
    $('.links').each(function () {
      var path = $(this).data('path');
      if (currentLocation.includes(path)) {
        $(this).addClass('active');
      }
    });
    this.user = JSON.parse(window.localStorage.getItem('user'));
    onEvent('updateProfileUser', (user) => { this.user = JSON.parse(user); })

    this.updateSidebarVisibility();
    window.addEventListener('resize', this.updateSidebarVisibility);
    document.addEventListener('click', this.handleOutsideClick);
  },
  beforeUnmount() {
    window.removeEventListener('resize', this.updateSidebarVisibility);
    document.removeEventListener('click', this.handleOutsideClick);
  },
  computed: {
    cssVars() {
      return {
        '--secondary-color': '#1d1b31',
        '--logo-title-color': '#fff',
        '--icons-color': 'var(--user-color);',
        '--menu-items-hover-color': '#fff',
        '--menu-items-text-color': 'var(--user-color);',
        '--menu-footer-text-color': '#fff',
      }
    },
  },
  watch: {
    isUserOpened() {
      var appMain = window.document.getElementById('appMain');
      appMain.style.paddingLeft = this.isUserOpened ? this.menuOpenedPaddingLeftBody : this.menuClosedPaddingLeftBody
    }
  }
}
</script>
  
<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
@import url('https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  transition: all 0.5s ease;
}

.hide {
  display: none;
}

.aclick {
  background: #07131d;
}

.line {
  width: 100%;
  height: 2px;
  background-color: silver;
  margin: 6px 0px;
  border: 1px solid silver;

}

.avatar_sidebar {
  height: 30px;
  width: 30px;
  object-fit: fill;
  border-radius: 6px;
  margin-right: 10px;
}

.active {
  background-color: var(--user-color);
}

.active i {
  color: white !important;
}

.active span {
  color: white !important;
}

.menu-logo {
  width: 30px;
  margin: 0 10px 0 10px;
  cursor: pointer;
}

.sidebar {
  border-right: 2px solid var(--user-color);
  position: relative;
  display: flex;
  flex-direction: column;
  position: fixed;
  left: 0;
  top: 0;
  height: 100%;
  min-height: min-content;
  width: 82px;
  background: white;
  z-index: 99;
  transition: all 0.5s ease;
}

.sidebar.open {
  width: 250px;
}

.sidebar .logo-details {
  height: 60px;
  display: flex;
  align-items: center;
  position: relative;
  background-color: white;
  color: var(--user-color);
  border-radius: 12px;
}

.sidebar .logo-details .icon {
  opacity: 0;
  transition: all 0.5s ease;
}

.sidebar .logo-details .logo_name {
  cursor: pointer;
  color: var(--user-color);
  font-size: 20px;
  font-weight: 600;
  opacity: 0;
  transition: all 0.2s ease;
}

.sidebar.open .logo-details .icon,
.sidebar.open .logo-details .logo_name {
  opacity: 1;
}

.sidebar .logo-details #btn {
  position: absolute;
  top: 50%;
  right: 0;
  transform: translateY(-50%);
  font-size: 22px;
  transition: all 0.4s ease;
  font-size: 23px;
  text-align: center;
  cursor: pointer;
  transition: all 0.5s ease;
}

.sidebar.open .logo-details #btn {
  text-align: right;
}

.bx-menu-alt-right::before {
  margin-right: 10px;
}

.sidebar i {
  color: var(--icons-color);
  height: 60px;
  min-width: 50px;
  font-size: 28px;
  text-align: center;
  line-height: 60px;
}

.job i {
  color: #06c755;
  font-size: 12px;
  height: initial;
  min-width: initial;
  text-align: initial;
  line-height: initial;
}

.sidebar li {
  position: relative;
  margin: 8px 0;
  list-style: none;
  cursor: pointer;
}

.sidebar li a {
  display: flex;
  height: 100%;
  width: 100%;
  border-radius: 12px;
  align-items: center;
  text-decoration: none;
  transition: all 0.4s ease;
}

.sidebar li a:hover {
  background-color: var(--user-color);
}

.sidebar li a .links_name {
  color: var(--menu-items-text-color);
  font-size: 15px;
  font-weight: 400;
  white-space: nowrap;
  opacity: 0;
  pointer-events: none;
  transition: 0.4s;
}

.sidebar.open li a .links_name {
  opacity: 1;
  pointer-events: auto;
}

.sidebar li a:hover .links_name,
.sidebar li a:hover i {
  transition: all 0.5s ease;
  color: white;
}

.sidebar li i {
  height: 50px;
  line-height: 50px;
  font-size: 18px;
  border-radius: 12px;
}

.sidebar div.profile {
  position: relative;
  height: 60px;
  width: 82px;
  padding: 10px 14px;
  background: var(--secondary-color);
  transition: all 0.5s ease;
  overflow: hidden;
}

.sidebar.open div.profile {
  width: 250px;
}

.sidebar div .profile-details {
  display: flex;
  align-items: center;
  flex-wrap: nowrap;
}

.sidebar div img {
  height: 45px;
  width: 45px;
  object-fit: cover;
  border-radius: 6px;
  margin-right: 10px;
}

.sidebar div.profile .name,
.sidebar div.profile .job {
  font-size: 15px;
  font-weight: 400;
  color: var(--menu-footer-text-color);
  white-space: nowrap;
}

.sidebar div.profile .name {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 160px;
}


.sidebar div.profile .job {
  font-size: 12px;
}

.sidebar .profile #log_out {
  cursor: pointer;
  color: white;
  position: absolute;
  top: 50%;
  right: 0;
  transform: translateY(-50%);
  background: var(--secondary-color);
  width: 100%;
  height: 60px;
  line-height: 60px;
  border-radius: 0px;
  transition: all 0.5s ease;
}

.sidebar.open .profile #log_out {
  width: 50px;
  background: var(--secondary-color);
  opacity: 0;
}

.sidebar.open .profile:hover #log_out {
  opacity: 1;
}

.sidebar.open .profile #log_out:hover {
  opacity: 1;
  color: red;
}

.sidebar .profile #log_out:hover {
  color: red;
}

.my-scroll-active {
  overflow-y: auto;
}

#my-scroll {
  overflow-y: auto;
  height: calc(100% - 60px);
}

#my-scroll::-webkit-scrollbar {
  display: none;
}

@media screen and (min-width: 993px) and (max-width: 1200px) {}

@media screen and (min-width: 769px) and (max-width: 992px) {
  .fa-solid {
    font-size: 4px;
  }
}

@media screen and (min-width: 577px) and (max-width: 768px) {}

@media screen and (min-width: 425px) and (max-width: 575px) {}

@media screen and (min-width: 375px) and (max-width: 424px) {}
</style>
  