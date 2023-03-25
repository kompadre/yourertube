<script>
    import {getContext, onMount} from "svelte";

    // This can be false if you're using a fallback (i.e. SPA mode)
    let user = ''
    let pass = ''
    let result = ''
    const authenticated = getContext('authenticated');

    async function login () {
        const res = await fetch('/auth/login', {
            method: 'POST',
            body: JSON.stringify({
                user,
                pass
            })
        })

        const json = await res.json()
        result = JSON.stringify(json)
        console.log(result);
        if (json.status == "ok") {
            $authenticated = true;
            user = "";
            pass = "";
        }
    }
    async function logout () {
        const res = await fetch('/auth/logout', {
            method: 'POST',
            body: JSON.stringify({
                "logout": true
            })
        })

        const json = await res.json()
        console.log(result);
        if (json.status == "ok") {
            $authenticated = false;
        }
    }

    onMount(async () => {
        const res = await (await fetch(`/auth/check`)).json();
        console.log(res);
        $authenticated = !(res.result == false);
    });
</script>
<style lang="scss">
    div.root {
      margin-left: auto;
      margin-right: 10px;
    }
</style>

<div class="root">
{#if !$authenticated}
    Login:
    <label>
        Email
        <input name="email" type="text" bind:value={user}>
    </label>
    <label>
        Password
        <input name="password" type="text" bind:value={pass}>
    </label>
    <button on:click={() => login()}>Log in</button>
{:else}
    Logged in <button on:click={() => logout()}>Log out?</button>
{/if}
</div>