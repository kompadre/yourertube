<script>
    import Login from "../Login.svelte";
    import { base } from '$app/paths';
    import {writable} from "svelte/store";
    import {setContext} from "svelte";
    import Video from "../Upload.svelte";
    let result = '';
    const authenticated = writable(false);
    setContext('authenticated', authenticated);
</script>
<style lang="scss">
    body {
      font-family: Roboto;
    }
    nav {
        display: flex;
        padding-top: 10px;
        >* {
          margin-left: 10px;
        }
    }
    div#main {
      height: calc( 100vh - 21px );
      display: flex;
      align-items: center;
      justify-items: center;
      justify-content: center;
      flex-direction: column;
    }
</style>

<nav>
    <a href="{base}/">Home</a>
    <a href="{base}/upload">Upload</a>
    <a href="{base}/gallery">Gallery</a>
    <a href="{base}/settings">Settings</a>
    <Login />
</nav>
<div id="main">
{#if $authenticated}
<slot></slot>
{:else}
    You have to be logged in.
{/if}
</div>