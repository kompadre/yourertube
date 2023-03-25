<script>
    import {base} from '$app/paths';
    import {get} from "svelte/store";
    import {getContext} from "svelte";
    let myfiles;
    let uploading = false;
    let result = '';
    let uploadedImage = base + "/pic.png";
    let error = "";
    let isVideo = false;
    const refreshGallery = getContext('refreshGallery');
    async function uploadFile(e) {
        uploading = true;
        const formData = new FormData();
        console.log(myfiles, "myfiles");

        formData.append('file', e.target.files[0]);
        const upload = fetch('/repo/upload', {
            method: 'POST',
            body: formData
        });
        result = await (await upload).json();
        uploading = false;
        if (result.status == "ok") {
            if (refreshGallery) {
                $refreshGallery = Date.now();
            }
            uploadedImage = "/uploaded/" + result.uploaded_filename;
            isVideo = result.is_video;
        }
        else
            error = result.error;
    }
</script>
{#if !isVideo}
<img alt="pic" src="{uploadedImage}" height="100px"  />
{:else}
<img alt="pic" src="/app/processing.thumb.png" height="100px"  />
{/if}
<br />
<input type="file" on:change={(e) => uploadFile(e)} bind:files={myfiles} disabled="{uploading}" />
{#if error !== ""}<div class="error">{error}</div>{/if}
<style>
    .error {
        color: red;
    }
</style>