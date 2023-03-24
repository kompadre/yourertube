<script>
    import { onMount } from "svelte";
    let images = [];
    let loaded = false;
    onMount(async () => {
        const res = await fetch('/repo/gallery');
        images = await res.json();
        loaded = true;
    });
    async function removeImage(e) {
        const target = e.target;
        console.log(target);
        const parent = target.parentElement;
        console.log(parent);
        const filename = parent.getAttribute('data-filename');
        const res = await fetch('/repo/delete', {
            method: 'POST',
            body: JSON.stringify({ filename: filename })
        })
        const json = await res.json();
        if (json.status == "ok") {
            parent.remove();
        }
    }
</script>
<style lang="scss">
    div.gallery {
        margin-top: 100px;
        margin-bottom: 100px;
        display: grid;
        grid-template-columns: repeat(8, 1fr);
        grid-template-rows: repeat(auto-fill, 1fr);
        width: 75vw;
        >div.image {
            background-size: cover;
            background-repeat: no-repeat;
            background-position-x: center;
            background-position-y: center;
            height: 175px;
        }
    }
</style>
<div class="gallery">
    {#if !loaded}
        Loading...
    {:else}
        {#each images.images as image}
            <div data-filename="{image.replace('/media', '')}" class="image" style="background-image: url({image.replace('/media', '')})">
                <button on:click={removeImage}>(x)</button>
            </div>
        {/each}
    {/if}
</div>