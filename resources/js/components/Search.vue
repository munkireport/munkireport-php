<template>
  <form class="form-inline my-2 my-lg-0">
    <li class="dropdown" data-reference="parent">
      <div class="search-results dropdown-menu">
        <a class="dropdown-item" href="#">No results</a>
      </div>
    </li>
    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
  </form>
</template>

<script>
import 'whatwg-fetch';

export default {
  name: "Search",
  data() {
    return {

    };
  }
}
</script>

<style scoped>

</style>
