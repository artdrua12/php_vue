<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App + Vue CRUD</title>
    <a href="createForm.php">Create</a>
</head>

<body>
    <div id="app"></div>
    <div id="port"></div>
</body>

</html>

<script type="text/javascript">
    import { createApp } from 'vue'
    import App from './App.vue'
    import router from './router'

    createApp(App).use(router).mount('#app')
</script>