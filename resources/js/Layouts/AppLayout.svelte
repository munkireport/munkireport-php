<script lang="ts">
    import Navigation from '../Components/Navigation.svelte'
    import Footer from "../Components/Footer.svelte";
</script>

<div>
    <Navigation />
    <main style="padding-top: 3rem;">
        <slot></slot>
    </main>
    <Footer/>
</div>
