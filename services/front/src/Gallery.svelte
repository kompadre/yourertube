<script>
    import {onMount} from "svelte";
    import {base} from "$app/paths";
    import Modal from "./Modal.svelte";

    let images = [];
    let loaded = false;
    let modalImageSource = base + '/pic.jpg';
    let showModal = false;
    let modalShowingVideo = false;
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
            body: JSON.stringify({filename: filename})
        })
        const json = await res.json();
        if (json.status == "ok") {
            parent.remove();
        }
    }
</script>
<div>
    {#if !loaded}
        Loading...
    {:else}
        <div class="module">
            Images
            <div class="gallery">
                {#each images.images as image}
                    <div on:click={() => (modalImageSource = image.replace('/media', ''), showModal = true, modalShowingVideo = false)}
                         data-filename="{image.replace('/media', '')}" class="image"
                         style="background-image: url({image.replace('/media', '')})">
                        <button on:click={removeImage}>&times;</button>
                    </div>
                {/each}
            </div>
        </div>
        <div class="module">
            Videos
            <div class="gallery">
                {#each images.videos as video}
                    <div on:click={() => (modalImageSource = video.path.replace('/media', ''), showModal = true, modalShowingVideo = true)}
                         data-filename="{video.path.replace('/media', '')}" class="video"
                         style="background-image: url({video.thumb.replace('/media', '')})"
                    >
                        <button on:click={removeImage}>&times;</button>
                    </div>
                {/each}
            </div>
        </div>
        <Modal bind:showModal>
            {#if !modalShowingVideo}
                <img alt="rest" style="max-width: 75vw; max-height: 75vh;" src="{modalImageSource}"/>
            {:else}
                <video controls src="{modalImageSource}" autoplay style="max-width: 75vw; max-height: 75vh;">
                    <track kind="captions">
                    <source src="{modalImageSource}"/>
                </video>
            {/if}
        </Modal>
    {/if}
</div>
<style lang="scss">
  div.module {
    margin-top: 10px;
    margin-bottom: 10px;
    > div.gallery {
      display: grid;
      grid-template-columns: repeat(8, 1fr);
      @media (max-width: 1280px) {
        grid-template-columns: repeat(6, 1fr);
      }
      @media (max-width: 800px) {
        grid-template-columns: repeat(4, 1fr);
      }
      @media (max-width: 420px) {
        grid-template-columns: repeat(3, 1fr);
      }
      margin: 0;

      grid-template-rows: 1fr;
      grid-gap: 2px;
      width: 75vw;
      > div.image, div.video {
        aspect-ratio: 1;
        background-size: cover;
        background-repeat: no-repeat;
        background-position-x: center;
        background-position-y: center;
        > button {
          margin-top: 5px;
          margin-right: 5px;
          float: right;
          background-color: rgba(0, 0, 0, 0.3);
          color: white;
          font-weight: bold;
          border: 0;
          border-radius: 50%;
          width: 20px;
          height: 20px;
          padding:0;
          display: flex;
          align-items: center;
          justify-content: center;
          &:hover {
            background-color: rgba(127, 127, 127, 0.3);
          }
        }
      }
    }
  }
</style>