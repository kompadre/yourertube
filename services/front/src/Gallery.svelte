<script>
    import { onMount } from "svelte";
    let images = [];
    let loaded = false;
    onMount(async () => {
        const res = await fetch('/repo/gallery');
        images = await res.json();
        loaded = true;
    });
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
            <div class="image" style="background-image: url({image.replace('/media', '')})"></div>
        {/each}
    {/if}
</div>