<script>
    import {base} from '$app/paths';
    import {get} from "svelte/store";
    import {getContext} from "svelte";
    let myfiles;
    let uploading = false;
    let result = '';
    let uploadedImage = base + "/pic.jpg";
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
        $refreshGallery = Date.now();
        uploadedImage = "/uploaded/" + result.uploaded_filename;
    }
</script>
<img alt="pic" src="{uploadedImage}" height="100px" /><br />
<input type="file" on:change={(e) => uploadFile(e)} bind:files={myfiles} disabled="{uploading}" />