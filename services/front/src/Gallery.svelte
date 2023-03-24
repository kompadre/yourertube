<script>
    import { onMount } from "svelte";
    import { base } from "$app/paths";
    import Modal from "./Modal.svelte";
    let images = [];
    let loaded = false;
    let modalImageSource = base + '/pic.jpg';
    let showModal = false;
    onMount(async () => {
        const res = await fetch('/repo/gallery');
        images = await res.json();
        loaded = true;
    });
    async function removeImage(e) {
        e.stopPropagation()
        e.preventDefault()
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
            >button {
              background-color: rgba(10, 10, 10, 0.1);
              color: white;
              margin-top: 5px;
              margin-right: 5px;
              float: right;
              font-weight: bold;
            }
        }
    }
</style>
<div class="gallery">
    {#if !loaded}
        Loading...
    {:else}
        {#each images.images as image}
            <div on:click={() => (modalImageSource = image.replace('/media', ''), showModal = true)} data-filename="{image.replace('/media', '')}" class="image" style="background-image: url({image.replace('/media', '')})">
                <button on:click={removeImage}>&times;</button>
            </div>
        {/each}
        <Modal bind:showModal>
            <div style="min-height: 85vh; min-width: 85vw; background-image: url({modalImageSource}); background-size: cover;">&nbsp;</div>
        </Modal>
    {/if}
</div>