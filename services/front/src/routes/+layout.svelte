<script>
    import Login from "../Login.svelte";
    import { base } from '$app/paths';
    import {writable} from "svelte/store";
    import {setContext} from "svelte";
    import Video from "../Video.svelte";
    let result = '';
    const authenticated = writable(false);
    setContext('authenticated', authenticated);
</script>

<nav>
    <a href="{base}/">Home</a>
    <a href="{base}/about">About</a>
    <a href="{base}/settings">Settings</a>
    <Login />

    <p>Result: </p>
    <pre>{result}</pre>
    {#if $authenticated}
    <Video />
    {/if}
</nav>

<slot></slot>