<script>
import Video from "../../Upload.svelte";
import {getContext} from "svelte";

const authenticated = getContext('authenticated');
</script>
{#if $authenticated}
    <Video />
{:else}
    You have to authenticate before uploading.
{/if}
