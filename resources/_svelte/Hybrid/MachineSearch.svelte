<script lang="ts">
    // MachineSearch wraps Search with specific result formatting for Laravel SCOUT + Machine model search
    import Search from "./Search.svelte";
    import InertiaDropdownItem from "../Components/InertiaDropdownItem.svelte";

    interface MachineSearchResult {
        serial_number: string;
        computer_name: string;
        machine_model: string;
    }

    let data: any[] = [];

    async function doSearch(value) {
        if (value.length === 0) return;

        try {
            const result = await fetch(`/api/v6/search/machine/${value}`, {
                method: 'GET'
            });

            data = await result.json();
        } catch (e) {

        }
    }
</script>

<Search debounce={400} on:type={doSearch} bind:data={data}>
    <!-- Dropdown Result Item -->
    <InertiaDropdownItem slot="item" let:item={item} style="padding: .375rem .75rem" href={"/clients/detail/" + item.serial_number}>
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">{item.computer_name}</h5>
            <small>{item.machine_model}</small>
        </div>
        <p class="mb-1">{item.serial_number}</p>
    </InertiaDropdownItem>
</Search>
