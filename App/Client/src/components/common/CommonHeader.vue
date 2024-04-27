<template>
  <div id="header">
    <div class="title">
      <span><i class="fa-solid fa-robot"></i> AI System</span><span>/</span><span v-html="title"></span>
    </div>
    <div id="contact">
      <li class="list_contact">
        <a class="facebook" href="https://www.facebook.com/IcassNiuTon" target="_blank"><i
            class="fa-brands fa-square-facebook"></i></a>
        <a class="instagram" href="https://www.instagram.com/vanmanh_ai_machine_learning/" target="_blank"><i
            class="fa-brands fa-square-instagram"></i></a>
        <a class="linkedin" href="https://www.linkedin.com/in/nvmanhfullstack/" target="_blank"><i
            class="fa-brands fa-linkedin"></i></a>
      </li>
      <div class="email-phone">
        <li class="contact_email"><a href="mailto:ai.system.support@gmail.com"><i class="fa-solid fa-envelope"></i> <span
              class="hidde">Email:</span>
            <span> ai.system.support@gmail.com</span></a></li>
        <li class="contact_phone"><a href="tel:+84702518919"><i class="fa-solid fa-phone"></i> <span
              class="hidde">Hotline
              24/7 Phone:</span>
            <span> +84702518919</span></a></li>
      </div>
      <li class="contact_help"><a href="#"><i class="fa-regular fa-circle-question"></i> <span class="hidde">Help</span>
        </a></li>
    </div>
  </div>
</template>

<script>

import useEventBus from '@/composables/useEventBus';
const { onEvent } = useEventBus()

export default {
  name: "CommonHeader",
  data() {
    return {
      title: 'AI Advanced',
    }
  },
  mounted() {
    onEvent('eventTitleHeader', (title) => {
      this.title = title;
    });
  },
}
</script>

<style scoped>
#header {
  position: sticky;
  top: 6px;
  margin: 0px 10px;
  position: sticky;
  border: 2px solid var(--user-color);
  background-color: white;
  padding: 2px 6px;
  display: flex;
  align-items: center;
  align-content: center;
  border-radius: 4px;
  padding-right: 30px;
  z-index: 1;
}

.title {
  width: 40%;
  font-weight: bold;
}

.title span:nth-child(1) {
  color: var(--user-color);
}

.title span:nth-child(2) {
  margin: 0px 6px;
}

.title span:nth-child(3) {
  color: rgb(80, 80, 80);
}

#contact a {
  transition: all 0.5s ease;
  text-decoration: none;
}

#contact a:hover {
  text-decoration: none;
  transition: all 0.5s ease;
}

#contact {
  width: 100%;
  height: 30px;
  display: flex;
  justify-content: space-around;
  color: black;
  line-height: 30%;
  align-content: center;
  align-items: center;
}

#contact li {
  margin-left: 30px;
}

.contact_email,
.contact_phone,
.contact_help {
  color: rgb(112, 112, 112);
  line-height: 1.5;
}

.contact_email a:hover {
  cursor: pointer;
  color: red;
}

.contact_phone a:hover {
  cursor: pointer;
  color: #0186ce;
}

.contact_help a:hover {
  cursor: pointer;
  color: green;
}

.list_contact a {
  font-size: 24px;
  margin-left: 6px;
  color: rgb(112, 112, 112);
}

.facebook:hover {
  color: #0863F7;
}

.instagram:hover {
  color: #E56D37;
}

.linkedin:hover {
  color: #0073B1;
}

.email-phone {
  display: flex;
}

@media screen and (min-width: 1201px) {
  .list_contact a {
    font-size: 145%;
  }

  .list_contact {
    min-width: 80px;
  }

  #header {
    padding: 7px 6px !important;
  }
}

@media screen and (min-width: 993px) and (max-width: 1200px) {
  #header {
    font-size: 15px;
  }

  .title {
    width: 80%;
  }


  .list_contact a {
    font-size: 140%;
  }

  #contact {
    padding: 20px 0;
    ;
  }

  .email-phone {
    display: block;
  }
}

@media screen and (min-width: 769px) and (max-width: 992px) {
  #header {
    font-size: 80%;
  }

  .title {
    width: 70%;
  }

  #contact li {
    margin-left: 10px;
  }

  .list_contact a {
    font-size: 150%;
  }

  #contact {
    padding: 20px 0;
    ;
  }

  .email-phone {
    display: block;
  }
}

@media screen and (min-width: 577px) and (max-width: 768px) {
  #header {
    font-size: 80%;
    padding: 2px 5px;
  }

  .title {
    width: 50%;
  }

  #contact li {
    margin-left: 10px;
  }

  .list_contact a {
    font-size: 120%;
  }

  #contact {
    padding: 20px 0;
    ;
  }

  .email-phone {
    display: block;
  }

  .hidde {
    display: none;
  }
}

@media screen and (min-width: 425px) and (max-width: 576px) {
  #header {
    font-size: 70%;
    padding: 2px 5px;
    margin-right: 1px;
  }

  .title {
    width: 55%;
  }

  #contact li {
    margin-left: 0px;
  }

  .list_contact a {
    font-size: 120%;
  }

  #contact {
    padding: 20px 0;
    ;
  }

  .email-phone {
    font-size: 95%;
    display: block;
  }

  .hidde {
    display: none;
  }
}

@media screen and (min-width: 375px) and (max-width: 424px) {
  #header {
    font-size: 70%;
    padding: 2px 5px;
    margin-right: 1px;
  }

  .title {
    width: 55%;
  }

  #contact li {
    margin-left: 0px;
  }

  .list_contact a {
    font-size: 120%;
  }

  #contact {
    padding: 20px 0;
    ;
  }

  .email-phone {
    font-size: 95%;
    display: block;
  }

  .hidde {
    display: none;
  }
}
</style>
