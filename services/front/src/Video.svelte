<script>
    import {base} from '$app/paths';
    let myfiles;
    let uploading = false;
    let result = '';
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
    }
</script>
<img alt="woman" src="{base}/pic.jpg" /><br />
<input type="file" on:change={(e) => uploadFile(e)} bind:files={myfiles} disabled="{uploading}" />
<p>{result}</p>