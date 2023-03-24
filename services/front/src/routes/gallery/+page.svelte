<script>
    import Gallery from "../../Gallery.svelte";
    import {setContext, getContext} from "svelte";
    import Video from "../../Video.svelte";
    import {writable} from "svelte/store";
    const authenticated = getContext('authenticated');
    const refreshGallery = writable(Date.now())
    setContext('refreshGallery', refreshGallery);
</script>
{#if !$authenticated}
    You have to login first
{:else}
    {#key $refreshGallery}
    <Gallery />
    {/key}
    <Video />
{/if}