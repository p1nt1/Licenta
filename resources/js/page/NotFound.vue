<template>
    <div class="container">
        <h2>404</h2>
        <p>Sorry but this page does not exist</p>
        <router-link to="/" class="btn btnBack">Back to HOME</router-link>
    </div>
</template>

<script>
    export default {
        name: 'not-found'
    }
</script>

<style lang="scss" scoped>
    div.container{
      text-align: center;
      background-color: #161925;
      color: #406E8E;
      max-width: 100vw !important;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }

    h2 {
      font-size: 72px;
    }

    p{
        font-size: 21px;
    }

    .btnBack{
      margin: 12px;
      border: 1px solid #23395B;
      color: #CBF7ED;
      font-size: 24px;
      padding: 8px 38px;
      transition: ease-in all 0.215s;
    }

    .btnBack:hover {
      background-color: #406E8E;
    }
</style>
