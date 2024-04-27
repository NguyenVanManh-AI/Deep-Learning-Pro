<template>
  <div id="footer">
    <div id="top-line">
      <div id="big" class="row">
        <div id="logo" class="col-2" @click="gotoAdmin">
          <p>META SHOP</p>
        </div>
        <div id="address" class="col">
          <li id="address_main">
            <span><i class="fa-solid fa-location-dot"></i></span>
            <span>54 Nguyen Luong Bang, Hoa Khanh Bac, Lien Chieu, Da Nang 550000</span>
          </li>
          <li>
            <span><i class="fa-solid fa-phone"></i></span>
            <span>+84 971404372</span>
          </li>
          <li>
            <span><i class="fa-solid fa-envelope"></i></span>
            <span>metashop@gmail.com</span>
          </li>
        </div>
        <div id="contact" class="col">
          <p>About company</p>
          <p>This is the sales page of META company, we sell a lot of products, for details, please contact the address
            above.</p>
          <ul>
            <a href="https://www.facebook.com/IcassNiuTon" target="_blank">
              <li><i class="fa-brands fa-facebook-f"></i></li>
            </a>
            <a href="https://twitter.com/NguyenVanManhft" target="_blank">
              <li><i class="fa-brands fa-twitter"></i></li>
            </a>
            <a href="https://www.linkedin.com/in/nvmanhfullstack/" target="_blank">
              <li><i class="fa-brands fa-linkedin-in"></i></li>
            </a>
            <a href="https://github.com/NguyenVanManh-AI" target="_blank">
              <li><i class="fa-brands fa-github"></i></li>
            </a>
            <a href="https://www.instagram.com/vanmanh_ai_machine_learning/" target="_blank">
              <li><i class="fa-brands fa-instagram"></i></li>
            </a>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
<script>

export default {
  name: "CommonFooter",
  methods: {
    gotoAdmin: function () {
      this.$router.push({ name: "LoginAdmin" });
    }
  }
}
</script>
<style scoped>
* {
  list-style: none;
}

a:hover {
  text-decoration: none;
}

#footer {
  width: 100%;
  background-color: #282C2F;
  position: relative;
}

#top-line {
  padding: 30px 100px;
}

#big {
  display: flex;
  margin: 0px auto;
  align-content: center;
  align-items: center;
  padding-left: 100px;
}

#logo {
  cursor: pointer;
  background-color: white;
  width: 140px;
  margin-right: 30px;
  border-radius: 20px;
}

#logo img {
  width: 80px;
  margin: auto;
  margin-bottom: 10px;
}

#logo p {
  font-weight: bold;
  color: #F84B2F;
  transition: color 0.5s ease;
  width: 100%;
  display: flex;
  justify-content: center;
}

#logo:hover p {
  color: var(--user-color);
}

#address {
  color: white;
  font-weight: bold;
  width: 40%;
}

#address li {
  margin-bottom: 6px;
}

#address li i {
  margin: 0px 6px;
}

#address_main {
  display: flex;
  align-content: center;
  align-items: center;
}

#address_main span:nth-child(2) {
  display: block;
}

#address li:nth-child(3) span:nth-child(2) {
  color: var(--user-color);
}

#contact {
  color: white;
  width: 50%;
}

#contact p:nth-child(1) {
  font-weight: bold;
  font-size: 18px;
}

#contact p:nth-child(2) {
  width: 80%;
}

#contact ul {
  width: 100%;
  display: flex;
  margin-top: 10px;
}

#contact ul li {
  background-color: white;
  border-radius: 6px;
  display: flex;
  justify-content: center;
  align-content: center;
  align-items: center;
  width: 26px;
  height: 26px;
  color: black;
  margin-right: 10px;
  font-size: 18px;
}

#contact ul li:hover {
  color: var(--user-color);
}</style>