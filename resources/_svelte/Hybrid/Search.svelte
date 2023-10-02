<script lang="ts">
    import {
        Button,
        Dropdown,
        DropdownMenu,
        Form,
        Input,
        InputGroup,
        InputGroupAddon,
    } from "sveltestrap";
    import {FontAwesomeIcon} from "@fortawesome/svelte-fontawesome";
    import {faSearch} from "@fortawesome/free-solid-svg-icons";
    import { createEventDispatcher, onMount, afterUpdate } from "svelte";
    import InertiaDropdownItem from "../Components/InertiaDropdownItem.svelte";
    const dispatch = createEventDispatcher();

    // The current search value
    export let value = "";

    // Automatically focus the input on render
    export let autofocus = false;

    // Debounce value (in milliseconds)
    export let debounce = 0;

    // Reference to DOM element
    export let ref = null;

    // Search result data
    export let data = [];

    function onSubmit(e) { e.preventDefault(); }

    let prevValue = value;
    let timeout = undefined;
    let calling = false;

    function debounceFn(fn): any {
        if (calling) return;
        calling = true;
        timeout = setTimeout(() => {
            fn();
            calling = false;
        }, debounce);
    }

    onMount(() => {
        if (autofocus) window.requestAnimationFrame(() => ref.focus());
        return () => clearTimeout(timeout);
    });

    afterUpdate(() => {
        if (value.length > 0 && value !== prevValue) {
            if (debounce > 0) {
                debounceFn(() => dispatch("type", value));
            } else {
                dispatch("type", value);
            }
        }
        if (value.length === 0 && prevValue.length > 0) dispatch("clear");
        prevValue = value;
    });

</script>

<svelte:options accessors/>

<Form inline role="search" on:submit={onSubmit}>
    <InputGroup>
        <Dropdown isOpen={data.length > 0 && value.length > 0} inNavbar group>
            <DropdownMenu style="width: 18rem">
                {#each data as item}
                    <slot name="item" item={item}>
                        <InertiaDropdownItem style="padding: .375rem .75rem" href={"/clients/detail/" + item.serial_number}>
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{item.computer_name}</h5>
                                <small>{item.machine_model}</small>
                            </div>
                            <p class="mb-1">{item.serial_number}</p>
                        </InertiaDropdownItem>
                    </slot>
                {/each}
            </DropdownMenu>
        </Dropdown>
        <Input
                name="search"
                type="search"
                placeholder="Search..."
                autocomplete="off"
                spellcheck="false"
                aria-label="Search"
                aria-haspopup="true"
                aria-expanded="false"
                bind:value
                on:input
                on:change
                on:focus
                on:blur
                on:keydown
                data-toggle="dropdown"
        />
        <InputGroupAddon addonType="append">
            <Button color="primary">
                <FontAwesomeIcon icon={faSearch} />
            </Button>
        </InputGroupAddon>
    </InputGroup>
</Form>
