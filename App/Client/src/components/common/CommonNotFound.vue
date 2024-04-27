<template>
  <div>
    <div id="page_not_found">
      <h1>PAGE NOT FOUND !</h1>
      <img src="@/assets/404.gif" alt="404" />
      <Link to="/">
      <button className="btn btn-outline-dark">HOME</button>
      </Link>
    </div>
  </div>
</template>
<script>
export default {
  name: "CommonNotFound",
}
</script>

<style scoped>
#page_not_found {
  color: #027CFB;
  text-align: center;
  width: 100%;
  height: 100vh;
  background-color: white;
}

#page_not_found h1 {
  margin-top: 30px;
  font-size: 65px;
  margin-bottom: 20px;
}

#page_not_found {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
  text-align: center;
}

#page_not_found img {
  width: 400px;
  margin-bottom: 20px;
}
</style>